<?php
/**
 * @file client_preview_data.php
 * @brief クライアント向けプレビュー用の商品データを返すAPI
 */
session_start();
header('Content-Type: application/json');

// クライアントとしてログインしているか確認
if (!isset($_SESSION['client_id'])) {
    echo json_encode(['success' => false, 'message' => 'ログインが必要です。']);
    exit();
}

require_once '../common/contents_db.php';

$client_id = $_SESSION['client_id'];
$debug = false;
$product_db = new cproduct_info();
$response = [];

try {
    // プルダウン用の商品リストを要求された場合
    if (isset($_GET['list'])) {
        $products = $product_db->get_products_by_client_id($debug, $client_id);
        $response = ['success' => true, 'products' => $products];
    }
    // 特定の商品の詳細を要求された場合
    elseif (isset($_GET['id']) && is_numeric($_GET['id'])) {
        $product_id = (int)$_GET['id'];
        $details = $product_db->get_full_product_details($debug, $product_id);

        // 念のため、取得した商品がこのクライアントのものであるか確認
        if ($details && $details['client_id'] == $client_id) {
            $response = ['success' => true, 'details' => $details];
        } else {
            $response = ['success' => false, 'message' => '商品が見つからないか、アクセス権がありません。'];
        }
    }
    // 不正なリクエスト
    else {
        $response = ['success' => false, 'message' => '無効なリクエストです。'];
    }

} catch (Exception $e) {
    error_log("API Error in client_preview_data.php: " . $e->getMessage());
    $response = ['success' => false, 'message' => 'データの取得中にサーバーエラーが発生しました。'];
}

echo json_encode($response, JSON_UNESCAPED_UNICODE);
