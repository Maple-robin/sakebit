<?php
/**
 * @file process_order.php
 * @brief 注文処理API (お酒・おつまみ両方の在庫削減に対応)
 */

ob_start();
require_once __DIR__ . '/../common/contents_db.php';
require_once __DIR__ . '/../common/config.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => '無効なリクエストです。']);
    exit();
}
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'ログインセッションが切れました。再度ログインしてください。']);
    exit();
}

$input = json_decode(file_get_contents('php://input'), true);
if (json_last_error() !== JSON_ERROR_NONE) {
    echo json_encode(['success' => false, 'message' => 'リクエストデータの形式が正しくありません。']);
    exit();
}

$shipping_address = $input['shipping_address'] ?? null;
$total_amount = $input['total_amount'] ?? null;

if (empty($shipping_address) || !is_numeric($total_amount) || $total_amount <= 0) {
    echo json_encode(['success' => false, 'message' => '送信された注文データ（住所または合計金額）が無効です。']);
    exit();
}

$current_user_id = $_SESSION['user_id'];
$debug_mode = defined('DEBUG') && DEBUG === true;
$pdo = null;

try {
    // DBインスタンスの準備
    $carts_db = new ccarts();
    $cart_items_db = new ccart_items();
    $orders_db = new corders();
    $order_items_db = new corder_items();
    $product_info_db = new cproduct_info();
    $otumami_db = new cotumami(); // ★おつまみDB操作のために追加

    $pdo = $orders_db->get_pdo();
    if (!$pdo) {
        throw new Exception('データベース接続の取得に失敗しました。');
    }

    // --- トランザクション1: 注文(orders)の登録 ---
    $pdo->beginTransaction();
    
    $cart_id = $carts_db->get_or_create_cart_by_user_id($debug_mode, $current_user_id);
    if (!$cart_id) throw new Exception('カートの取得に失敗しました。');
    
    $cart_items = $cart_items_db->get_items_by_cart_id($debug_mode, $cart_id);
    if (empty($cart_items)) throw new Exception('カートが空です。決済処理を中断しました。');

    $order_id = $orders_db->create_order($debug_mode, $current_user_id, $total_amount, $shipping_address);
    if (!$order_id) throw new Exception('注文の作成(ordersテーブルへのINSERT)に失敗しました。');
    
    $pdo->commit();

    // --- トランザクション2: 注文商品登録、在庫削減、カートクリア ---
    $pdo->beginTransaction();

    $result_add_items = $order_items_db->add_items_to_order($debug_mode, $order_id, $cart_items);
    if (!$result_add_items) throw new Exception('注文商品の登録(order_itemsテーブルへのINSERT)に失敗しました。');

    // ★★★ ここからが在庫削減処理 ★★★
    foreach ($cart_items as $item) {
        // 商品(product)の場合
        if (isset($item['product_id']) && !empty($item['product_id'])) {
            $product_id = $item['product_id'];
            $quantity = $item['cart_quantity'];
            $stock_decreased = $product_info_db->decrease_stock($debug_mode, $product_id, $quantity);
            
            if (!$stock_decreased) {
                $product_details = $product_info_db->get_tgt($debug_mode, $product_id);
                $product_name = $product_details ? $product_details['product_name'] : "商品ID: {$product_id}";
                throw new Exception("在庫不足のため注文を完了できませんでした。商品:「{$product_name}」");
            }
        } 
        // おつまみ(otumami)の場合
        elseif (isset($item['otumami_id']) && !empty($item['otumami_id'])) {
            $otumami_id = $item['otumami_id'];
            $quantity = $item['cart_quantity'];
            $stock_decreased = $otumami_db->decrease_stock($debug_mode, $otumami_id, $quantity);

            if (!$stock_decreased) {
                $otumami_details = $otumami_db->get_tgt($debug_mode, $otumami_id);
                $otumami_name = $otumami_details ? $otumami_details['otumami_name'] : "おつまみID: {$otumami_id}";
                throw new Exception("在庫不足のため注文を完了できませんでした。商品:「{$otumami_name}」");
            }
        }
    }
    // ★★★ 在庫削減処理ここまで ★★★

    $result_clear_cart = $cart_items_db->clear_items_by_cart_id($debug_mode, $cart_id);
    if (!$result_clear_cart) {
        error_log("注文完了後のカートクリアに失敗しました。 order_id: {$order_id}, cart_id: {$cart_id}");
    }
    
    $pdo->commit();

    ob_end_clean();
    echo json_encode(['success' => true, 'order_id' => $order_id]);

} catch (Exception $e) {
    if ($pdo && $pdo->inTransaction()) {
        $pdo->rollBack();
    }
    
    error_log('注文処理APIエラー: ' . $e->getMessage());
    ob_end_clean();
    
    $error_message = 'サーバー内部でエラーが発生しました。時間をおいて再度お試しください。';
    if (defined('DEBUG') && DEBUG === true) {
        $error_message = 'デバッグ情報: ' . $e->getMessage();
    }
    
    echo json_encode(['success' => false, 'message' => $error_message]);
}

exit();
