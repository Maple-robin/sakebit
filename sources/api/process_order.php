<?php
/**
 * @file process_order.php
 * @brief 注文処理API (おつまみ登録処理のロジックを参考)
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

    $pdo = $orders_db->get_pdo();
    if (!$pdo) {
        throw new Exception('データベース接続の取得に失敗しました。');
    }

    // --- トランザクション1: 注文(orders)の登録 ---
    $pdo->beginTransaction();
    
    // カート情報を取得
    $cart_id = $carts_db->get_or_create_cart_by_user_id($debug_mode, $current_user_id);
    if (!$cart_id) throw new Exception('カートの取得に失敗しました。');
    
    $cart_items = $cart_items_db->get_items_by_cart_id($debug_mode, $cart_id);
    if (empty($cart_items)) throw new Exception('カートが空です。決済処理を中断しました。');

    // ordersテーブルに記録
    $order_id = $orders_db->create_order($debug_mode, $current_user_id, $total_amount, $shipping_address);
    if (!$order_id) {
        throw new Exception('注文の作成(ordersテーブルへのINSERT)に失敗しました。');
    }
    $pdo->commit(); // ★ここで一度コミット

    // --- トランザクション2: 注文商品(order_items)の登録とカートのクリア ---
    $pdo->beginTransaction();

    // order_itemsテーブルに記録
    $result_add_items = $order_items_db->add_items_to_order($debug_mode, $order_id, $cart_items);
    if (!$result_add_items) {
        // この例外がスローされている
        throw new Exception('注文商品の登録(order_itemsテーブルへのINSERT)に失敗しました。');
    }

    // カートを空にする
    $result_clear_cart = $cart_items_db->clear_items_by_cart_id($debug_mode, $cart_id);
    if (!$result_clear_cart) {
        error_log("注文完了後のカートクリアに失敗しました。 order_id: {$order_id}, cart_id: {$cart_id}");
    }
    $pdo->commit(); // ★ここで二度目のコミット

    ob_end_clean();
    echo json_encode(['success' => true, 'order_id' => $order_id]);

} catch (Exception $e) {
    // どちらかのトランザクションでエラーが起きた場合
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
