<?php
/*!
@file api/delete_post.php
@brief 投稿の削除処理を行うAPIエンドポイント
@copyright Copyright (c) 2024 Your Name.
*/

// セッションを開始 (ユーザーIDが必要なため)
session_start();

// エラー表示設定 (開発中のみ)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// ヘッダーを設定してJSONレスポンスを返すことを明示
header('Content-Type: application/json');

// contents_db.php と config.php をインクルード
require_once __DIR__ . '/../common/contents_db.php'; // commonフォルダへのパスを修正
require_once __DIR__ . '/../common/config.php'; // commonフォルダへのパスを修正

// DEBUG_MODE が config.php で定義されていない場合はここで定義
if (!defined('DEBUG_MODE')) {
    define('DEBUG_MODE', false); // 本番環境ではfalseに設定
}

$response = ['success' => false, 'message' => ''];

// ログインしているユーザーのIDを取得
$user_id = $_SESSION['user_id'] ?? null;

if (!$user_id) {
    $response['message'] = 'ログインしていません。投稿を削除するにはログインが必要です。';
    echo json_encode($response);
    exit();
}

// POSTデータをJSONとして受け取る
$json_input = file_get_contents('php://input');
$data = json_decode($json_input, true);

$post_id = $data['postId'] ?? null;

// 入力値の基本的なバリデーション
if (!cutil::is_number($post_id) || $post_id < 1) {
    $response['message'] = '無効なリクエストです。投稿IDが不正です。';
    echo json_encode($response);
    exit();
}

try {
    $posts_db = new cposts();
    $post_images_db = new cpost_images();
    $good_db = new cgood(); // いいねも削除するため
    $heart_db = new cheart(); // ハートも削除するため

    $pdo = $posts_db->get_pdo(); // トランザクションのためにPDOインスタンスを取得
    $pdo->beginTransaction();

    // 投稿が現在のユーザーのものであることを確認
    $post_data = $posts_db->get_tgt(DEBUG_MODE, $post_id);
    if (!$post_data || $post_data['user_id'] != $user_id) {
        throw new Exception("許可されていない操作です。この投稿の所有者ではありません。", 403);
    }

    // 関連する画像を削除
    if (!$post_images_db->delete_images_by_post_id(DEBUG_MODE, $post_id)) {
        throw new Exception("関連画像の削除に失敗しました。", 500);
    }
    
    // 関連するいいねを全て削除 (post_idに紐づく全てのいいねを削除)
    // ここで新しく追加したdelete_all_goods_by_post_idメソッドを呼び出す
    if (!$good_db->delete_all_goods_by_post_id(DEBUG_MODE, $post_id)) {
        throw new Exception("関連するいいねの削除に失敗しました。", 500);
    }

    // 関連するハートを全て削除 (post_idに紐づく全てのハートを削除)
    // ここで新しく追加したdelete_all_hearts_by_post_idメソッドを呼び出す
    if (!$heart_db->delete_all_hearts_by_post_id(DEBUG_MODE, $post_id)) {
        throw new Exception("関連するハートの削除に失敗しました。", 500);
    }

    // 投稿自体を削除 (user_idによる所有者確認を含む)
    $delete_success = $posts_db->delete_post(DEBUG_MODE, $post_id, $user_id);
    if ($delete_success === false) {
        throw new Exception("投稿自体の削除に失敗しました。", 500);
    }

    $pdo->commit();
    $response['success'] = true;
    $response['message'] = '投稿が正常に削除されました。';

} catch (Throwable $e) {
    if (isset($pdo) && $pdo->inTransaction()) {
        $pdo->rollBack();
    }
    error_log("Post deletion error: " . $e->getMessage() . " on line " . $e->getLine() . " in " . $e->getFile());
    $response['message'] = 'エラーが発生しました: ' . $e->getMessage();
    // HTTPステータスコードを設定することも可能だが、今回はJSONレスポンスのみ
    // http_response_code($e->getCode() > 0 ? $e->getCode() : 500);
}

echo json_encode($response);
exit();
?>
