<?php
/*!
@file api_cart_manager.php
@brief カート操作API (追加・更新・削除)
@copyright Copyright (c) 2024 Your Name.
*/

// セッションを開始
session_start();

// ヘッダーを設定
header('Content-Type: application/json');

// 必要なファイルをインクルード
// apiフォルダの中から、一つ上の階層にあるcommonフォルダを参照
require_once __DIR__ . '/../common/contents_db.php';

// レスポンス用の配列
$response = ['success' => false, 'message' => ''];

// ログイン状態をチェック
if (!isset($_SESSION['user_id'])) {
    $response['message'] = 'カート機能を利用するにはログインが必要です。';
    echo json_encode($response);
    exit();
}

// POSTデータを取得
$json_data = file_get_contents('php://input');
$data = json_decode($json_data, true);

// アクションと商品情報を取得
$action = $data['action'] ?? '';
$user_id = $_SESSION['user_id'];

try {
    $debug_mode = false;
    $carts_db = new ccarts();
    $cart_items_db = new ccart_items();
    $product_info_db = new cproduct_info();

    // ユーザーのカートIDを取得（なければ作成）
    $cart_id = $carts_db->get_or_create_cart_by_user_id($debug_mode, $user_id);

    if (!$cart_id) {
        throw new Exception('カートの取得または作成に失敗しました。');
    }

    // アクションに応じて処理を分岐
    switch ($action) {
        case 'add':
            $product_id = $data['product_id'] ?? null;
            $quantity = $data['quantity'] ?? 1;
            if (!$product_id || !is_numeric($product_id) || !is_numeric($quantity) || $quantity < 1) {
                throw new Exception('無効な商品情報です。');
            }
            $product_data = $product_info_db->get_tgt($debug_mode, $product_id);
            if (!$product_data) {
                throw new Exception('対象の商品が見つかりません。');
            }
            $current_price = $product_data['product_price'];
            if ($cart_items_db->add_or_update_item($debug_mode, $cart_id, $product_id, $quantity, $current_price)) {
                $response['success'] = true;
                $response['message'] = 'カートに商品を追加しました。';
            } else {
                $response['message'] = 'カートへの追加に失敗しました。';
            }
            break;

        case 'update':
            $cart_item_id = $data['cart_item_id'] ?? null;
            $quantity = $data['quantity'] ?? null;
            if (!$cart_item_id || !is_numeric($cart_item_id) || !is_numeric($quantity) || $quantity < 1) {
                throw new Exception('無効なリクエストです。');
            }
            if ($cart_items_db->update_item_quantity($debug_mode, $cart_item_id, $quantity)) {
                $response['success'] = true;
                $response['message'] = '数量を更新しました。';
            } else {
                $response['message'] = '数量の更新に失敗しました。';
            }
            break;

        case 'delete':
            $cart_item_id = $data['cart_item_id'] ?? null;
            if (!$cart_item_id || !is_numeric($cart_item_id)) {
                throw new Exception('無効なリクエストです。');
            }
            if ($cart_items_db->remove_item($debug_mode, $cart_item_id)) {
                $response['success'] = true;
                $response['message'] = '商品をカートから削除しました。';
            } else {
                $response['message'] = '商品の削除に失敗しました。';
            }
            break;

        default:
            $response['message'] = '不明なアクションです。';
            break;
    }

} catch (Exception $e) {
    error_log("Cart API Error: " . $e->getMessage());
    $response['message'] = 'サーバーエラーが発生しました。';
}

// 結果をJSON形式で返す
echo json_encode($response);
