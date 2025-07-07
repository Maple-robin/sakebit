<?php
/*!
@file wishlist.php
@brief お気に入り一覧ページ
@copyright Copyright (c) 2024 Your Name.
*/

// ★★★ ここから修正 ★★★
// ヘッダー(HTML)を読み込む前に、まずPHPの処理を先に実行します。

// データベース接続情報やクラス定義を先に読み込む
require_once __DIR__ . '/common/contents_db.php';

// セッションを開始 (header.phpでも呼ばれますが、チェックのためにここで先に開始します)
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// ログインチェック
if (!isset($_SESSION['user_id'])) {
    // ログインしていなければ、リダイレクトして処理を完全に終了
    header('Location: login.php?redirect_url=wishlist.php');
    exit();
}

// --- これ以降はログインしているユーザー向けの処理 ---

// ログインしているユーザーのIDを取得
$current_user_id = $_SESSION['user_id'];
$debug_mode = defined('DEBUG') ? DEBUG : false;

// DBクラスのインスタンスを生成
$favorites_db = new cproduct_favorites();
$product_info_obj = new cproduct_info();

// ログインユーザーのお気に入り商品IDリストを取得
$favorite_ids = $favorites_db->get_favorite_product_ids_by_user_id($debug_mode, $current_user_id);

$favorite_products_for_js = [];

// お気に入り商品が1つ以上ある場合のみ、商品情報を取得
if (!empty($favorite_ids)) {
    // 全商品情報を取得
    $all_products = $product_info_obj->get_product_list_for_admin($debug_mode, null, 0, 99999);
    
    $favorited_products_map = [];
    foreach ($all_products as $product) {
        if (in_array($product['product_id'], $favorite_ids)) {
            $tags_array = !empty($product['tags_concat']) ? array_map('trim', explode(',', $product['tags_concat'])) : [];
            $image_paths = !empty($product['image_paths']) ? explode(';', $product['image_paths']) : [];
            $main_image = !empty($image_paths[0]) ? htmlspecialchars($image_paths[0]) : 'https://placehold.co/300x200?text=NoImage';

            $favorited_products_map[$product['product_id']] = [
                'id' => (int)$product['product_id'],
                'name' => htmlspecialchars($product['product_name']),
                'image' => $main_image,
                'volume' => htmlspecialchars($product['product_Contents']),
                'price' => (float)$product['product_price'],
                'tags' => $tags_array,
                'isFavorite' => true
            ];
        }
    }
    
    // お気に入りに追加した最新の順序を維持
    foreach($favorite_ids as $id) {
        if(isset($favorited_products_map[$id])) {
            $favorite_products_for_js[] = $favorited_products_map[$id];
        }
    }
}

// ★★★ ここまでがPHPの事前処理 ★★★
?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>お気に入り | OUR BRAND</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@300;400;500;700&family=Zen+Old+Mincho:wght@400;500;700&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="css/wishlist.css">
    <link rel="stylesheet" href="css/top.css">
</head>

<body>
    <?php 
    // 共通ヘッダーを読み込む
    require_once 'header.php'; 
    ?>

    <main>
        <div class="ranking-container">
            <h1 class="page-title">
                <span class="en">FAVORITES LIST</span>
                <span class="ja">( お気に入り一覧 )</span>
            </h1>

            <section class="ranking-list-section">
                <div class="product-grid" id="product-list">
                    <!-- JavaScriptによってお気に入り商品がここに描画されます -->
                </div>
                <!-- お気に入りがない場合のメッセージはJSで制御 -->
            </section>
        </div>
    </main>

    <?php 
    // 共通フッターを読み込む
    require_once 'footer.php'; 
    ?>

    <script>
        // PHPからJavaScriptへお気に入り商品データを渡す
        const initialFavoriteProducts = <?= json_encode($favorite_products_for_js, JSON_UNESCAPED_UNICODE); ?>;
    </script>
    <script src="js/wishlist.js"></script>
    <script src="js/script.js"></script>
</body>

</html>
