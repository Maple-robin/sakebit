<?php
/*!
@file products_list.php
@brief 商品一覧ページ
@copyright Copyright (c) 2024 Your Name.
*/

require_once 'header.php';

if (!defined('DEBUG')) {
    define('DEBUG', true);
}

$is_logged_in = isset($_SESSION['user_id']);
$current_user_id = $_SESSION['user_id'] ?? null;

$product_info_obj = new cproduct_info();
$tags_obj = new ctags_for_products();
$favorites_db = new cproduct_favorites();

$favorite_product_ids = [];
if ($is_logged_in) {
    $favorite_product_ids = $favorites_db->get_favorite_product_ids_by_user_id(DEBUG, $current_user_id);
}

// 新しいメソッドで、販売数を含む全商品データを取得
$products_from_db = $product_info_obj->get_all_products_for_list(DEBUG); 
if ($products_from_db === false) {
    $products_from_db = [];
    error_log("Failed to fetch all product data for list page.");
}

// JavaScriptに渡すための商品データを整形
$products_for_js = [];
if (!empty($products_from_db)) {
    foreach ($products_from_db as $product) {
        $created_at = $product['created_at'] ?? date('Y-m-d H:i:s'); 
        $main_image = !empty($product['main_image_path']) ? htmlspecialchars($product['main_image_path']) : 'https://placehold.co/300x200?text=NoImage';
        $tags_array = !empty($product['tags']) ? array_map('trim', explode(', ', $product['tags'])) : [];

        $products_for_js[] = [
            'id' => (int)$product['product_id'],
            'name' => htmlspecialchars($product['product_name']),
            'image' => $main_image,
            'volume' => htmlspecialchars($product['product_Contents']),
            'price' => (float)$product['product_price'],
            'tags' => $tags_array,
            'category' => htmlspecialchars($product['category_name']),
            'releaseDate' => date('Y-m-d', strtotime($created_at)),
            // 'rankingScore' を実際の販売数 'sales' に変更
            'sales' => (int)$product['total_sold'],
            'isFavorite' => in_array($product['product_id'], $favorite_product_ids)
        ];
    }
}

// フィルターパネル用にタグカテゴリを取得
$grouped_tags_for_panel = $tags_obj->get_all_tags_grouped_by_category(DEBUG);
?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>商品一覧 | OUR BRAND</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@300;400;500;700&family=Zen+Old+Mincho:wght@400;500;700&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="css/products_list.css">
    <link rel="stylesheet" href="css/top.css">
</head>

<body>

    <?php // ヘッダーは読み込み済み ?>

    <main>
        <div class="ranking-container">
            <h1 class="page-title">
                <span class="en">PRODUCTS LIST</span>
                <span class="ja">( 商品一覧 )</span>
            </h1>
            
            <div class="category-description" id="category-description" style="display: none;">
                <p class="description-text" id="description-text"></p>
            </div>

            <div class="guide-button-container">
                <a href="#" id="guide-button" class="guide-button">お酒ガイドを見る</a>
            </div>

            <div class="controls-section">
                <div class="filter-sort-buttons">
                    <button class="filter-button" id="filter-button">
                        絞り込み <i class="fas fa-bars"></i>
                    </button>
                    <button class="sort-button" id="sort-button">
                        並び替え <i class="fas fa-sort-amount-down"></i>
                    </button>
                </div>
                <div class="display-mode-buttons">
                    <button class="display-grid" id="display-grid"><i class="fas fa-th-large"></i></button>
                    <button class="display-list active" id="display-list"><i class="fas fa-list"></i></button>
                </div>
            </div>

            <div class="overlay" id="filter-overlay">
                <div class="filter-panel">
                    <div class="filter-panel__header">
                        <h2>絞り込み</h2>
                        <button class="close-button" id="filter-close-button"><i class="fas fa-times"></i></button>
                    </div>
                    <div class="filter-panel__content">
                        <div class="filter-group">
                            <h3>商品カテゴリ</h3>
                            <label><input type="checkbox" name="category" value="すべて"> すべての商品</label>
                            <label><input type="checkbox" name="category" value="日本酒"> 日本酒</label>
                            <label><input type="checkbox" name="category" value="中国酒"> 中国酒</label>
                            <label><input type="checkbox" name="category" value="梅酒"> 梅酒</label>
                            <label><input type="checkbox" name="category" value="缶チューハイ"> 缶チューハイ</label>
                            <label><input type="checkbox" name="category" value="焼酎"> 焼酎</label>
                            <label><input type="checkbox" name="category" value="ウィスキー"> ウィスキー</label>
                            <label><input type="checkbox" name="category" value="スピリッツ"> スピリッツ</label>
                            <label><input type="checkbox" name="category" value="リキュール"> リキュール</label>
                            <label><input type="checkbox" name="category" value="ワイン"> ワイン</label>
                            <label><input type="checkbox" name="category" value="ビール"> ビール</label>
                        </div>
                        
                        <div class="filter-group">
                            <h3>商品タグ</h3>
                            <?php if (isset($grouped_tags_for_panel) && !empty($grouped_tags_for_panel)): ?>
                                <?php foreach ($grouped_tags_for_panel as $category): ?>
                                    <details class="tag-category-group">
                                        <summary><?php echo htmlspecialchars($category['tag_category_name']); ?></summary>
                                        <div class="tag-list">
                                            <?php if (!empty($category['tags'])): ?>
                                                <?php foreach ($category['tags'] as $tag): ?>
                                                    <label><input type="checkbox" name="tag" value="<?php echo htmlspecialchars($tag['tag_name']); ?>"> <?php echo htmlspecialchars($tag['tag_name']); ?></label>
                                                <?php endforeach; ?>
                                            <?php else: ?>
                                                <p class="no-tags">このカテゴリにタグはありません。</p>
                                            <?php endif; ?>
                                        </div>
                                    </details>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <p>タグ情報がありません。</p>
                            <?php endif; ?>
                        </div>

                        <button class="apply-filter-button">絞り込む</button>
                    </div>
                </div>
            </div>

            <div class="overlay" id="sort-overlay">
                <div class="sort-panel">
                    <div class="sort-panel__header">
                        <h2>並び替え</h2>
                        <button class="close-button" id="sort-close-button"><i class="fas fa-times"></i></button>
                    </div>
                    <div class="sort-panel__content">
                        <label><input type="radio" name="sort_order" value="newest"> 新しい順</label>
                        <label><input type="radio" name="sort_order" value="highest_price"> 価格の高い順</label>
                        <label><input type="radio" name="sort_order" value="lowest_price"> 価格の安い順</label>
                        <label><input type="radio" name="sort_order" value="ranking" checked> ランキング順</label>
                        <button class="apply-sort-button">並び替える</button>
                    </div>
                </div>
            </div>

            <section class="ranking-list-section">
                <div class="product-list" id="product-list">
                    <!-- 商品リストはJavaScriptによってここに描画されます -->
                </div>
                <div id="pagination-container" class="pagination-container"></div>
            </section>
        </div>
    </main>

    <?php 
    // 共通フッターを読み込む
    require_once 'footer.php'; 
    ?>

    <script>
        // PHPからJavaScriptへデータを渡す
        const initialProductsData = <?= json_encode($products_for_js, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT); ?>;
        const isUserLoggedIn = <?= json_encode($is_logged_in); ?>;
    </script>
    <script src="js/products_list.js"></script>
    <script src="js/sticky-controls.js"></script>
    <script src="js/script.js"></script>
</body>

</html>
