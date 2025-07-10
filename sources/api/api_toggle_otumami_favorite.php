<?php
/**
 * @file api_toggle_otumami_favorite.php
 * @brief おつまみのお気に入り状態を切り替えるAPI
 */
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'お気に入り機能を利用するにはログインが必要です。']);
    exit();
}

require_once '../common/contents_db.php';

$input = json_decode(file_get_contents('php://input'), true);
$otumami_id = $input['otumami_id'] ?? null;
$is_favorited = $input['is_favorited'] ?? null;
$user_id = $_SESSION['user_id'];

if (!is_numeric($otumami_id) || is_null($is_favorited)) {
    echo json_encode(['success' => false, 'message' => '無効なリクエストです。']);
    exit();
}

$debug = false;
$favorites_db = new cotumami_favorites();
$result = false;

try {
    if ($is_favorited) {
        // 現在お気に入り済みなら、削除する
        $result = $favorites_db->remove_favorite($debug, $user_id, $otumami_id);
    } else {
        // 現在お気に入りでないなら、追加する
        $result = $favorites_db->add_favorite($debug, $user_id, $otumami_id);
    }

    if ($result) {
        echo json_encode(['success' => true]);
    } else {
        throw new Exception('データベースの更新に失敗しました。');
    }
} catch (Exception $e) {
    error_log("Favorite API Error: " . $e->getMessage());
    echo json_encode(['success' => false, 'message' => '処理中にエラーが発生しました。']);
}
