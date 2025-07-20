<?php
/*!
@file submit_contact.php
@brief お問い合わせ内容を処理するAPI
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
    $response['message'] = 'お問い合わせにはログインが必要です。';
    echo json_encode($response);
    exit();
}

$user_id = $_SESSION['user_id'];
$input = json_decode(file_get_contents('php://input'), true);

$title = $input['title'] ?? null;
$content = $input['content'] ?? null;

if (empty($title) || empty($content)) {
    $response['message'] = '件名と内容は必須です。';
    echo json_encode($response);
    exit();
}

try {
    $contacts_db = new ccontacts();
    // ログインユーザーID、件名、内容をデータベースに保存
    $success = $contacts_db->insert_contact(DEBUG_MODE, $user_id, $title, $content);

    if ($success) {
        $response['success'] = true;
        $response['message'] = 'お問い合わせありがとうございます。内容を確認の上、担当者よりご連絡いたします。';
    } else {
        $response['message'] = 'データベースへの保存に失敗しました。';
    }
} catch (Exception $e) {
    error_log("Contact submission error: " . $e->getMessage());
    $response['message'] = 'サーバーエラーが発生しました。';
}

echo json_encode($response);
