<?php
/*!
@file wishlist.php
@brief お気に入り一覧ページ (お酒・おつまみ両対応版)
@copyright Copyright (c) 2024 Your Name.
*/

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php?redirect_url=wishlist.php');
    exit();
}

require_once __DIR__ . '/common/contents_db.php';

$current_user_id = $_SESSION['user_id'];
$debug_mode = defined('DEBUG') ? DEBUG : false;

// DBクラスのインスタンスを生成
$product_favorites_db = new cproduct_favorites();
$otumami_favorites_db = new cotumami_favorites();
$product_info_db = new cproduct_info();
$otumami_db = new cotumami();

// --- 1. お酒とおつまみのお気に入り情報を、登録日時を含めて取得 ---
$favorite_products = $product_favorites_db->get_favorite_products_by_user_id($debug_mode, $current_user_id);
$favorite_otumami = $otumami_favorites_db->get_favorite_otumami_by_user_id($debug_mode, $current_user_id);

// --- 2. 取得したお酒とおつまみを一つの配列にまとめる ---
$all_favorite_items = array_merge($favorite_products, $favorite_otumami);

// --- 3. 登録日時（created_at）が新しい順に並び替える ---
usort($all_favorite_items, function($a, $b) {
    return strtotime($b['favorited_at']) - strtotime($a['favorited_at']);
});


// --- 4. JavaScriptに渡すための配列を整形 ---
$favorite_items_for_js = [];
foreach ($all_favorite_items as $item) {
    // お酒かおつまみかを判定
    if (isset($item['product_id'])) { // お酒の場合
        $favorite_items_for_js[] = [
            'id' => (int)$item['product_id'],
            'name' => htmlspecialchars($item['product_name']),
            'image' => htmlspecialchars($item['main_image_path'] ?? 'https://placehold.co/300x200?text=NoImage'),
            'price' => (float)$item['product_price'],
            'tags' => !empty($item['tags']) ? array_map('trim', explode(', ', $item['tags'])) : [],
            'type' => 'product', // 種類を判別するためのキー
            'isFavorite' => true
        ];
    } elseif (isset($item['otumami_id'])) { // おつまみの場合
         $favorite_items_for_js[] = [
            'id' => (int)$item['otumami_id'],
            'name' => htmlspecialchars($item['otumami_name']),
            'image' => htmlspecialchars($item['main_image_path'] ?? 'https://placehold.co/300x200?text=NoImage'),
            'price' => (float)$item['otumami_price'],
            'tags' => !empty($item['tags']) ? array_map('trim', explode(', ', $item['tags'])) : [],
            'type' => 'otumami', // 種類を判別するためのキー
            'isFavorite' => true
        ];
    }
}

$is_logged_in = true; // このページはログインが必須
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>お気に入り | SAKE BIT</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@300;400;500;700&family=Zen+Old+Mincho:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="css/wishlist.css">
    <link rel="stylesheet" href="css/top.css">
</head>
<body>
    <?php require_once 'header.php'; ?>

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

    <?php require_once 'footer.php'; ?>

    <script>
        // PHPからJavaScriptへお気に入り商品データを渡す
        const initialFavoriteItems = <?= json_encode($favorite_items_for_js, JSON_UNESCAPED_UNICODE); ?>;
        const isUserLoggedIn = <?= json_encode($is_logged_in); ?>;
    </script>
    <script src="js/wishlist.js"></script>
    <script src="js/script.js"></script>
</body>
</html>
