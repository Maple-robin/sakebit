<?php
// JSON形式のレスポンスであることと、文字コードを明示
header('Content-Type: application/json; charset=utf-8');

// --- パスの修正 ---
// client/auth_check.php を読み込む
require_once __DIR__ . '/../client/auth_check.php';
// common/contents_db.php を読み込む
require_once __DIR__ . '/../common/contents_db.php';

// レスポンス用の連想配列を初期化
$response = ['success' => false, 'message' => '不明なエラーです。'];

// POSTリクエスト以外は処理を中断
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $response['message'] = '無効なリクエストです。';
    echo json_encode($response);
    exit;
}

// POSTされたJSONデータを取得してデコード
$json_data = file_get_contents('php://input');
$data = json_decode($json_data, true);

// ★ 必要なデータを order_item_ids に変更
if (!isset($data['order_item_ids']) || !isset($data['status']) || !is_array($data['order_item_ids'])) {
    $response['message'] = '送信されたデータが正しくありません。';
    echo json_encode($response);
    exit;
}

$order_item_ids = $data['order_item_ids'];
$status = $data['status'];
$valid_statuses = ['pending', 'shipped', 'delivered', 'cancelled'];

// ステータスの値が不正な場合は処理を中断
if (empty($order_item_ids) || !in_array($status, $valid_statuses)) {
    $response['message'] = '選択された注文またはステータスが無効です。';
    echo json_encode($response);
    exit;
}

try {
    // ★ corder_items クラスのインスタンスを作成
    $order_items_db = new corder_items();
    $pdo = $order_items_db->get_pdo();
    
    $pdo->beginTransaction();

    $all_updates_succeeded = true;
    
    // auth_check.phpで定義されている$client_idと$debugを使用
    foreach ($order_item_ids as $order_item_id) {
        // ★ 商品アイテムごとのステータス更新メソッドを呼び出す
        $result = $order_items_db->update_item_status($debug, $order_item_id, $status, $client_id);
        if (!$result) {
            // 権限がないか、更新に失敗した場合
            $all_updates_succeeded = false;
            // エラーメッセージをより具体的に
            $response['message'] = "商品アイテムID:{$order_item_id} の更新に失敗しました。操作権限がない可能性があります。";
            break;
        }
    }

    if ($all_updates_succeeded) {
        $pdo->commit();
        $response['success'] = true;
        $response['message'] = '商品ステータスを正常に更新しました。';
    } else {
        $pdo->rollBack();
        // ループ内で設定されたメッセージが使われる
    }

} catch (Exception $e) {
    if (isset($pdo) && $pdo->inTransaction()) {
        $pdo->rollBack();
    }
    error_log($e->getMessage());
    $response['message'] = "サーバーエラーが発生しました: " . $e->getMessage();
}

// 最終的なレスポンスをJSON形式で出力
echo json_encode($response, JSON_UNESCAPED_UNICODE);
?>
