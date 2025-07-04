<?php
/*!
@file api_toggle_favorite.php
@brief お気に入り登録/解除API
@copyright Copyright (c) 2024 Your Name.
*/

// セッションを開始
session_start();

// ヘッダーを設定
header('Content-Type: application/json');

// ★★★ ここを修正 ★★★
// apiフォルダの中から、一つ上の階層にあるcommonフォルダを参照するようにパスを修正
require_once __DIR__ . '/../common/contents_db.php';

// レスポンス用の配列
$response = ['success' => false, 'message' => ''];

// ログイン状態をチェック
if (!isset($_SESSION['user_id'])) {
    $response['message'] = 'ログインが必要です。';
    echo json_encode($response);
    exit();
}

// POSTデータを取得
$json_data = file_get_contents('php://input');
$data = json_decode($json_data, true);

$product_id = $data['product_id'] ?? null;
$is_currently_favorited = $data['is_favorited'] ?? false;
$user_id = $_SESSION['user_id'];

// バリデーション
if (!$product_id || !is_numeric($product_id)) {
    $response['message'] = '無効な商品IDです。';
    echo json_encode($response);
    exit();
}

try {
    $favorites_db = new cproduct_favorites();
    $debug_mode = false;

    if ($is_currently_favorited) {
        // 現在お気に入りなら、削除する
        if ($favorites_db->remove_favorite($debug_mode, $user_id, $product_id)) {
            $response['success'] = true;
            $response['message'] = 'お気に入りから削除しました。';
        } else {
            $response['message'] = 'お気に入りの削除に失敗しました。';
        }
    } else {
        // 現在お気に入りでないなら、追加する
        if ($favorites_db->add_favorite($debug_mode, $user_id, $product_id)) {
            $response['success'] = true;
            $response['message'] = 'お気に入りに追加しました。';
        } else {
            $response['message'] = 'お気に入りの追加に失敗しました。';
        }
    }

} catch (Exception $e) {
    error_log("Favorite API Error: " . $e->getMessage());
    $response['message'] = 'サーバーエラーが発生しました。';
}

// 結果をJSON形式で返す
echo json_encode($response);
