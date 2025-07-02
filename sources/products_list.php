<?php
/*!
@file products_list.php
@brief 商品一覧ページ
@copyright Copyright (c) 2024 Your Name.
*/

// commonディレクトリにあるcontents_db.phpを読み込む
require_once __DIR__ . '/common/contents_db.php';

// DEBUGモードの定義（config.phpで定義されていることを前提とするが、念のため）
if (!defined('DEBUG')) {
    define('DEBUG', true);
}

// データベースクラスのインスタンスを生成
$product_info_obj = new cproduct_info();
$tags_obj = new ctags_for_products(); // タグ情報を取得するためのインスタンス

// 全ての商品を取得 (ここではclient_idはnullで全件取得)
$products_from_db = $product_info_obj->get_product_list_for_admin(DEBUG, null, 0, 9999); 
if ($products_from_db === false) {
    $products_from_db = []; // 取得失敗時は空の配列を設定
    error_log("Failed to fetch all product data.");
}

// JavaScriptに渡すための商品データを整形
$products_for_js = [];
if (!empty($products_from_db)) {
    foreach ($products_from_db as $product) {
        // created_at が存在しない場合のフォールバックを追加
        $created_at = $product['created_at'] ?? date('Y-m-d H:i:s'); 

        $image_paths_str = $product['image_paths'] ?? '';
        $image_paths = !empty($image_paths_str) ? explode(';', $image_paths_str) : [];
        $main_image = !empty($image_paths[0]) ? htmlspecialchars($image_paths[0]) : 'https://placehold.co/300x200?text=NoImage';
        
        $tags_array = !empty($product['tags_concat']) ? array_map('trim', explode(',', $product['tags_concat'])) : [];

        $products_for_js[] = [
            'id' => (int)$product['product_id'],
            'name' => htmlspecialchars($product['product_name']),
            'image' => $main_image, // 画像のパス
            'volume' => htmlspecialchars($product['product_Contents']),
            'price' => (float)$product['product_price'],
            'tags' => $tags_array, // タグの配列
            'category' => htmlspecialchars($product['category_name']),
            'releaseDate' => date('Y-m-d', strtotime($created_at)), // 登録日時をリリース日として扱う
            'rankingScore' => 100, // 仮のランキングスコア。必要であればDBから取得
            'isFavorite' => false // お気に入り状態はユーザーセッションによるため、ここでは初期値false
        ];
    }
}

// タグカテゴリごとにグループ化されたタグを取得 (フィルターパネル用)
$grouped_tags = $tags_obj->get_all_tags_grouped_by_category(DEBUG);

// ここでセッションを開始（各ファイルで独立して開始する）
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
// ログイン状態に応じた表示の切り替え（products_list.php内で直接使用）
$is_logged_in_local = isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
$login_link_local = $is_logged_in_local ? 'logout.php' : 'login.php';
$login_text_local = $is_logged_in_local ? 'ログアウト' : 'ログイン';
$login_icon_local = $is_logged_in_local ? 'fas fa-sign-out-alt' : 'fas fa-user-circle';

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
    <link rel="stylesheet" href="css/top.css"> </head>

<body>
    <header class="header">
        <div class="header__inner">
            <button class="hamburger-menu">
                <span></span>
                <span></span>
                <span></span>
            </button>
            <h1 class="header__logo">
                <a href="index.php">OUR BRAND</a>
            </h1>
            <nav class="header__nav">
                <ul class="nav__list pc-only">
                    <li><a href="products_list.php">商品一覧</a></li>
                    <li><a href="contact.php">お問い合わせ</a></li>
                </ul>
                <div class="header__icons">
                    <a href="wishlist.php" class="header__icon-link">
                        <i class="fas fa-heart"></i>
                    </a>
                    <a href="cart.php" class="header__icon-link">
                        <i class="fas fa-shopping-cart"></i>
                    </a>
                </div>
            </nav>
        </div>
    </header>

    <nav class="sp-menu">
        <div class="sp-menu__header">
            <div class="sp-menu__login">
                <i class="<?php echo $login_icon_local; ?>"></i> <a href="<?php echo $login_link_local; ?>"><?php echo $login_text_local; ?></a>
            </div>
        </div>
        <div class="sp-menu__search">
            <input type="text" placeholder="検索...">
            <button type="submit"><i class="fas fa-search"></i></button>
        </div>
        <ul class="sp-menu__list">
            <li class="sp-menu__category-toggle">
                商品カテゴリ <i class="fas fa-chevron-down category-icon"></i>
                <ul class="sp-menu__sub-list">
                    <li><a href="products_list.php?category=日本酒">日本酒</a></li>
                    <li><a href="products_list.php?category=中国酒">中国酒</a></li>
                    <li><a href="products_list.php?category=梅酒">梅酒</a></li>
                    <li><a href="products_list.php?category=缶チューハイ">缶チューハイ</a></li>
                    <li><a href="products_list.php?category=焼酎">焼酎</a></li>
                    <li><a href="products_list.php?category=ウィスキー">ウィスキー</a></li>
                    <li><a href="products_list.php?category=スピリッツ">スピリッツ</a></li>
                    <li><a href="products_list.php?category=リキュール">リキュール</a></li>
                    <li><a href="products_list.php?category=ワイン">ワイン</a></li>
                    <li><a href="products_list.php?category=ビール">ビール</a></li>
                </ul>
            </li>
            <li class="sp-menu__category-toggle">
                商品タグ <i class="fas fa-chevron-down category-icon"></i>
                <ul class="sp-menu__sub-list">
                    <?php if (!empty($grouped_tags)): // products_list.phpで取得した$grouped_tagsを使用 ?>
                        <?php foreach ($grouped_tags as $category): ?>
                            <li>
                                <span class="sp-menu__tag-category-toggle">
                                    <?php echo htmlspecialchars($category['tag_category_name']); ?> <i class="fas fa-chevron-down category-icon"></i>
                                </span>
                                <ul class="sp-menu__sub-sub-list">
                                    <?php if (!empty($category['tags'])): ?>
                                        <?php foreach ($category['tags'] as $tag): ?>
                                            <li><a href="products_list.php?tag=<?php echo urlencode($tag['tag_name']); ?>"><?php echo htmlspecialchars($tag['tag_name']); ?></a></li>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <li><span>タグなし</span></li>
                                    <?php endif; ?>
                                </ul>
                            </li>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <li><span>タグ情報がありません。</span></li>
                    <?php endif; ?>
                </ul>
            </li>
            <li class="sp-menu__item"><a href="posts.php">投稿ページ</a></li>
            <li class="sp-menu__item"><a href="MyPage.php">マイページ</a></li>
        </ul>
        <div class="sp-menu__divider"></div>
        <ul class="sp-menu__list sp-menu__list--bottom">
            <li class="sp-menu__item"><a href="faq.php">よくある質問</a></li>
            <li class="sp-menu__item"><a href="contact.php">お問い合わせ</a></li>
        </ul>
    </nav>

    <main>        <div class="ranking-container">
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
                            <?php if (isset($grouped_tags) && !empty($grouped_tags)): // products_list.phpで取得した$grouped_tagsを使用 ?>
                                <?php foreach ($grouped_tags as $category): ?>
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
                </div>
                <div id="pagination-container" class="pagination-container"></div>
            </section>
        </div>
    </main>

    <footer class="footer">
        <div class="footer__inner">
            <ul class="footer__nav">
                <li>
                    <span class="footer__nav-title">商品一覧</span>
                    <ul class="footer__subnav">
                        <li><a href="products_list.php?category=日本酒">日本酒</a></li>
                        <li><a href="products_list.php?category=中国酒">中国酒</a></li>
                        <li><a href="products_list.php?category=梅酒">梅酒</a></li>
                        <li><a href="products_list.php?category=缶チューハイ">缶チューハイ</a></li>
                        <li><a href="products_list.php?category=焼酎">焼酎</a></li>
                        <li><a href="products_list.php?category=ウィスキー">ウィスキー</a></li>
                        <li><a href="products_list.php?category=スピリッツ">スピリッツ</a></li>
                        <li><a href="products_list.php?category=リキュール">リキュール</a></li>
                        <li><a href="products_list.php?category=ワイン">ワイン</a></li>
                        <li><a href="products_list.php?category=ビール">ビール</a></li>
                    </ul>
                </li>
                <li><a href="faq.php">よくあるご質問／お問合せ</a></li>
                <li><a href="MyPage.php">会員登録・ログイン</a></li>
                <li><a href="history.php">購入履歴</a></li>
                <li><a href="cart.php">買い物かごを見る</a></li>
                <li><a href="privacy.php">プライバシーポリシー</a></li>
                <li><a href="terms.php">利用規約</a></li>
            </ul>            <div class="footer__logo" style="margin: 24px 0 12px;">
                <a href="index.php">
                    <img src="img/logo.png" alt="OUR BRAND" style="height:32px;">
                </a>
            </div>
            <p class="footer__copyright">© OUR BRAND All Rights Reserved.</p>
        </div>
    </footer>

    <script>
        // PHPから商品データをJSON形式で受け取る
        const initialProductsData = <?php echo json_encode($products_for_js, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT); ?>;
    </script>
    <script src="js/products_list.js"></script>
    <script src="js/sticky-controls.js"></script>
    <script src="js/script.js"></script>
</body>

</html>