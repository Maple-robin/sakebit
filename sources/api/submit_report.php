<?php
/*!
@file submit_report.php
@brief 投稿の通報を処理するAPI
@copyright Copyright (c) 2024 Your Name.
*/

header('Content-Type: application/json');

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../common/contents_db.php';
require_once __DIR__ . '/../common/config.php';

if (!defined('DEBUG_MODE')) {
    define('DEBUG_MODE', false);
}

$response = ['success' => false, 'message' => '不明なエラーが発生しました。'];

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $response['message'] = '無効なリクエストです。';
    echo json_encode($response);
    exit();
}

if (!isset($_SESSION['user_id'])) {
    $response['message'] = '通報するにはログインが必要です。';
    echo json_encode($response);
    exit();
}

$user_id = $_SESSION['user_id'];
$input = json_decode(file_get_contents('php://input'), true);

$post_id = $input['postId'] ?? null;
$category = $input['category'] ?? null;
$content = $input['content'] ?? '';

if (empty($post_id) || empty($category)) {
    $response['message'] = '必須項目が不足しています。';
    echo json_encode($response);
    exit();
}

if ($category === 'その他' && empty($content)) {
    $response['message'] = '「その他」を選択した場合は、通報内容を入力してください。';
    echo json_encode($response);
    exit();
}

try {
    // ★★★ ここを修正 ★★★
    // creports から creport_info に変更
    $report_db = new creport_info();
    $success = $report_db->insert_report(DEBUG_MODE, $post_id, $user_id, $category, $content);

    if ($success) {
        $response['success'] = true;
        $response['message'] = '通報が送信されました。ご協力ありがとうございます。';
    } else {
        $response['message'] = 'データベースへの保存に失敗しました。';
    }
} catch (Exception $e) {
    error_log("Report submission error: " . $e->getMessage());
    $response['message'] = 'サーバーエラーが発生しました。';
}

echo json_encode($response);
