<?php
/*!
@file api/reaction_process.php
@brief 投稿に対するいいね/ハートの処理を行うAPIエンドポイント
@copyright Copyright (c) 2024 Your Name.
*/

// セッションを開始 (ユーザーIDが必要なため)
session_start();

// エラー表示設定 (開発中のみ)
// 本番環境では 'display_errors', 0 に設定し、error_log を使用
error_reporting(E_ALL); // 全てのエラーを報告
ini_set('display_errors', 1); // エラー表示を有効にする (開発時のみ)

// ヘッダーを設定してJSONレスポンスを返すことを明示
header('Content-Type: application/json');

// contents_db.php と config.php をインクルード
// reaction_process.php が public_html/api/ にあり、
// contents_db.php と config.php が public_html/common/ にあると仮定した場合、
// api から common へは一つ上の階層に戻ってから common に入る必要がある
require_once __DIR__ . '/../common/contents_db.php';
require_once __DIR__ . '/../common/config.php';

// DEBUG_MODE が config.php で定義されていない場合はここで定義
if (!defined('DEBUG_MODE')) {
    define('DEBUG_MODE', false); // 本番環境ではfalseに設定
}

$response = ['success' => false, 'message' => '', 'newLikes' => 0, 'newHearts' => 0, 'isLiked' => false, 'isHearted' => false];

// ログインしているユーザーのIDを取得
$user_id = $_SESSION['user_id'] ?? null;

if (!$user_id) {
    $response['message'] = 'ログインしていません。リアクションするにはログインが必要です。';
    echo json_encode($response);
    exit();
}

// POSTデータをJSONとして受け取る
$json_input = file_get_contents('php://input');
$data = json_decode($json_input, true);

$post_id = $data['postId'] ?? null;
$reaction_type = $data['reactionType'] ?? null;

// 入力値の基本的なバリデーション
if (!cutil::is_number($post_id) || $post_id < 1 || !in_array($reaction_type, ['good', 'heart'])) {
    $response['message'] = '無効なリクエストです。';
    echo json_encode($response);
    exit();
}

try {
    $good_db = new cgood();
    $heart_db = new cheart();

    // トランザクションを開始するために、いずれかのDBクラスインスタンスからPDOを取得
    // good_db と heart_db は同じPDOインスタンスを共有しているはず
    $pdo = $good_db->get_pdo();
    $pdo->beginTransaction();

    // 現在の状態を取得
    $is_liked = $good_db->is_good_by_user(DEBUG_MODE, $user_id, $post_id);
    $is_hearted = $heart_db->is_heart_by_user(DEBUG_MODE, $user_id, $post_id);

    if ($reaction_type === 'good') {
        if ($is_liked) {
            // 既にいいねしている場合は取り消し
            $result = $good_db->delete_good(DEBUG_MODE, $user_id, $post_id);
            if ($result === false) throw new Exception("Failed to delete good.");
            $response['isLiked'] = false;
        } else {
            // いいねしていない場合は登録
            $result = $good_db->insert_good(DEBUG_MODE, $user_id, $post_id);
            if ($result === false) throw new Exception("Failed to insert good.");
            $response['isLiked'] = true;
            // ★ここから排他制御ロジックを削除しました★
            // if ($is_hearted) {
            //      $result = $heart_db->delete_heart(DEBUG_MODE, $user_id, $post_id);
            //      if ($result === false) throw new Exception("Failed to delete heart during good operation.");
            //      $response['isHearted'] = false; // ハートの状態も更新
            // }
        }
    } elseif ($reaction_type === 'heart') {
        if ($is_hearted) {
            // 既にハートしている場合は取り消し
            $result = $heart_db->delete_heart(DEBUG_MODE, $user_id, $post_id);
            if ($result === false) throw new Exception("Failed to delete heart.");
            $response['isHearted'] = false;
        } else {
            // ハートしていない場合は登録
            $result = $heart_db->insert_heart(DEBUG_MODE, $user_id, $post_id);
            if ($result === false) throw new Exception("Failed to insert heart.");
            $response['isHearted'] = true;
            // ★ここから排他制御ロジックを削除しました★
            // if ($is_liked) {
            //     $result = $good_db->delete_good(DEBUG_MODE, $user_id, $post_id);
            //     if ($result === false) throw new Exception("Failed to delete good during heart operation.");
            //     $response['isLiked'] = false; // いいねの状態も更新
            // }
        }
    }

    // トランザクションをコミット
    $pdo->commit();

    // 最新のいいね数とハート数を再取得
    $response['newLikes'] = $good_db->count_good_by_post_id(DEBUG_MODE, $post_id);
    $response['newHearts'] = $heart_db->count_heart_by_post_id(DEBUG_MODE, $post_id);
    // 最新のいいね/ハート状態も再取得 (排他解除されたので、現在のユーザーの状態を正確に反映)
    $response['isLiked'] = $good_db->is_good_by_user(DEBUG_MODE, $user_id, $post_id);
    $response['isHearted'] = $heart_db->is_heart_by_user(DEBUG_MODE, $user_id, $post_id);

    $response['success'] = true;
    
} catch (Throwable $e) { // ExceptionだけでなくErrorも捕捉
    if (isset($pdo) && $pdo->inTransaction()) {
        $pdo->rollBack(); // エラー時はロールバック
    }
    error_log("Reaction process error: " . $e->getMessage() . " on line " . $e->getLine() . " in " . $e->getFile());
    // DEBUG_MODE が true の場合のみ詳細なエラーメッセージを返す
    $response['message'] = 'サーバー内部エラーが発生しました。' . (DEBUG_MODE ? ' 詳細: ' . $e->getMessage() . ' (File: ' . $e->getFile() . ' Line: ' . $e->getLine() . ')' : '');
}

echo json_encode($response);
exit();
?>
