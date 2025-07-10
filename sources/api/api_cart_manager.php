<?php
/**
 * @file api_cart_manager.php
 * @brief カート操作API (お酒・おつまみ両対応版)
 */
session_start();
header('Content-Type: application/json');

// ログイン必須
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'カート機能を利用するにはログインが必要です。']);
    exit();
}

require_once '../common/contents_db.php';

$input = json_decode(file_get_contents('php://input'), true);
$action = $input['action'] ?? '';
$current_user_id = $_SESSION['user_id'];
$debug = false;

$carts_db = new ccarts();
$cart_items_db = new ccart_items();

try {
    // ユーザーのカートIDを取得または作成
    $cart_id = $carts_db->get_or_create_cart_by_user_id($debug, $current_user_id);
    if (!$cart_id) {
        throw new Exception('カートの準備に失敗しました。');
    }

    switch ($action) {
        // --- 商品(お酒・おつまみ)を追加 ---
        case 'add':
            $quantity = $input['quantity'] ?? 0;
            if (!is_numeric($quantity) || $quantity <= 0) {
                throw new Exception('数量が正しくありません。');
            }

            $item_id = null;
            $item_type = null;
            $item_info = null;
            $price = 0;

            // 商品IDかおつまみIDかによって処理を分岐
            if (isset($input['product_id']) && !empty($input['product_id'])) {
                $item_id = $input['product_id'];
                $item_type = 'product';
                $product_db = new cproduct_info();
                $item_info = $product_db->get_tgt($debug, $item_id);
                if ($item_info) {
                    $price = $item_info['product_price'];
                }
            } elseif (isset($input['otumami_id']) && !empty($input['otumami_id'])) {
                $item_id = $input['otumami_id'];
                $item_type = 'otumami';
                $otumami_db = new cotumami();
                $item_info = $otumami_db->get_tgt($debug, $item_id);
                if ($item_info) {
                    $price = $item_info['otumami_price'];
                }
            } else {
                throw new Exception('商品が指定されていません。');
            }

            if (!$item_info || $price <= 0) {
                throw new Exception('商品情報の取得に失敗したか、価格が不正です。');
            }

            $result = $cart_items_db->add_or_update_item($debug, $cart_id, $item_id, $item_type, $quantity, $price);
            if ($result) {
                echo json_encode(['success' => true, 'message' => 'カートに追加しました。']);
            } else {
                throw new Exception('カートへの追加処理に失敗しました。');
            }
            break;

        // --- 商品の数量を更新 ---
        case 'update':
            $cart_item_id = $input['cart_item_id'] ?? null;
            $quantity = $input['quantity'] ?? null;
            if (!$cart_item_id || !is_numeric($cart_item_id) || !is_numeric($quantity) || $quantity < 1) {
                throw new Exception('無効なリクエストです。');
            }
            if ($cart_items_db->update_item_quantity($debug, $cart_item_id, $quantity)) {
                echo json_encode(['success' => true, 'message' => '数量を更新しました。']);
            } else {
                throw new Exception('数量の更新に失敗しました。');
            }
            break;

        // --- 商品を削除 ---
        case 'delete':
            $cart_item_id = $input['cart_item_id'] ?? null;
            if (!$cart_item_id || !is_numeric($cart_item_id)) {
                throw new Exception('無効なリクエストです。');
            }
            if ($cart_items_db->remove_item($debug, $cart_item_id)) {
                echo json_encode(['success' => true, 'message' => '商品をカートから削除しました。']);
            } else {
                throw new Exception('商品の削除に失敗しました。');
            }
            break;

        default:
            throw new Exception('無効な操作です。');
    }

} catch (Exception $e) {
    // エラーが発生した場合は、そのメッセージを返す
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
