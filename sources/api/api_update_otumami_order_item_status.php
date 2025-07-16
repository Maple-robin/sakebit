<?php
/*!
@file api_update_otumami_order_item_status.php
@brief API: おつまみ注文商品のステータスを更新
@copyright Copyright (c) 2024 Your Name.
*/

header('Content-Type: application/json');

// 管理者用のセッションチェックなどをここに入れることを推奨
// session_start();
// if (!isset($_SESSION['admin_user_id'])) {
//     echo json_encode(['success' => false, 'message' => '管理者としてログインしていません。']);
//     exit;
// }

require_once __DIR__ . '/../common/contents_db.php';

// DEBUGモード
if (!defined('DEBUG')) {
    define('DEBUG', false);
}
$debug = DEBUG;

$json_data = file_get_contents('php://input');
$data = json_decode($json_data, true);

$item_ids = $data['order_item_ids'] ?? [];
$status = $data['status'] ?? '';

if (empty($item_ids) || !is_array($item_ids) || empty($status)) {
    echo json_encode(['success' => false, 'message' => '無効なリクエストです。']);
    exit;
}

try {
    $order_items_db = new corder_items();
    $pdo = $order_items_db->get_pdo();
    $pdo->beginTransaction();

    $all_success = true;
    foreach ($item_ids as $item_id) {
        // おつまみ用のステータス更新メソッドを呼び出し
        $result = $order_items_db->update_otumami_item_status($debug, $item_id, $status);
        if (!$result) {
            $all_success = false;
            // 1件でも失敗したらループを抜ける
            break; 
        }
    }

    if ($all_success) {
        $pdo->commit();
        echo json_encode(['success' => true, 'message' => count($item_ids) . '件のステータスを更新しました。']);
    } else {
        $pdo->rollBack();
        echo json_encode(['success' => false, 'message' => 'ステータスの更新に失敗しました。対象の商品が見つからないか、更新権限がありません。']);
    }

} catch (Exception $e) {
    if (isset($pdo) && $pdo->inTransaction()) {
        $pdo->rollBack();
    }
    error_log("Otumami status update API Error: " . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'データベースエラーが発生しました。']);
}