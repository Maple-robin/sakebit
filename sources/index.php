<?php
/*!
@file index.php
@brief トップページ
@copyright Copyright (c) 2024 Your Name.
*/

// --- トップページ用のデータ取得 ---
require_once __DIR__ . '/common/contents_db.php';
$debug = false;

$product_db = new cproduct_info();

// ★★★ ここからが修正箇所 ★★★

// 1. ランキング商品をまとめて取得 (上位3件 + 次の5件 = 合計8件)
$all_ranked_products = $product_db->get_ranked_products($debug, 8, 0);

// 2. 取得した配列をPHPで分割する
// メインカルーセル用: 最初の3件 (0番目から3つ)
$top_products = array_slice($all_ranked_products, 0, 3);

// ランキングセクション用: 4番目以降の5件 (3番目から5つ)
$ranking_products = array_slice($all_ranked_products, 3, 5);

// ★★★ ここまで修正 ★★★


// 初心者向けセクション用の商品を取得
$beginner_products = $product_db->get_top_selling_products_by_tag($debug, '初心者向け', 5);

?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OUR BRAND | 伊勢の地酒と和食にこだわった料亭</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@300;400;500;700&family=Zen+Old+Mincho:wght@400;500;700&display=swap"
        rel="stylesheet">

    <link rel="stylesheet" href="https://unpkg.com/swiper@8/swiper-bundle.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="css/top.css">
    <style>
        /* カスタムメッセージボックスのスタイル */
        .custom-message-box {
            position: fixed;
            top: 20px;
            left: 50%;
            transform: translateX(-50%);
            padding: 15px 25px;
            border-radius: 8px;
            font-size: 1.6rem;
            color: #fff;
            z-index: 10000;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
            opacity: 0;
            animation: fadeInOut 3s forwards;
            min-width: 300px;
            text-align: center;
        }
        .custom-message-box.success {
            background-color: #28a745; /* 緑色 */
        }
        .custom-message-box.error {
            background-color: #dc3545; /* 赤色 */
        }
        @keyframes fadeInOut {
            0% { opacity: 0; transform: translateX(-50%) translateY(-20px); }
            10% { opacity: 1; transform: translateX(-50%) translateY(0); }
            90% { opacity: 1; transform: translateX(-50%) translateY(0); }
            100% { opacity: 0; transform: translateX(-50%) translateY(-20px); }
        }
    </style>
</head>

<body>
    <?php 
    // 共通ヘッダーを読み込む
    require_once 'header.php'; 
    ?>

    <main>
        <section class="main-visual swiper mySwiperHero">
            <div class="swiper-wrapper">
                <?php if (!empty($top_products)): ?>
                    <?php foreach ($top_products as $product): ?>
                        <div class="swiper-slide main-visual__item">
                            <a href="product.php?id=<?php echo htmlspecialchars($product['product_id']); ?>">
                                <img src="<?php echo htmlspecialchars($product['main_image_path'] ?? 'img/no-image.png'); ?>" alt="<?php echo htmlspecialchars($product['product_name']); ?>">
                            </a>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <!-- ランキング商品がない場合のデフォルト表示 -->
                    <div class="swiper-slide main-visual__item">
                        <img src="img/gingerale.png" alt="雄大な自然">
                    </div>
                    <div class="swiper-slide main-visual__item">
                        <img src="img/osake.png" alt="お酒と料理">
                    </div>
                    <div class="swiper-slide main-visual__item">
                        <img src="img/sake.png" alt="グラス">
                    </div>
                <?php endif; ?>
            </div>
            <div class="swiper-pagination"></div>
        </section>

        <section class="products">
            <div class="products__inner">
                <h2 class="section-title">
                    <span class="en">RANKING</span>
                    <span class="ja">( ランキング )</span>
                </h2>
                <div class="swiper mySwiperProducts">
                    <div class="swiper-wrapper">
                        <?php if (!empty($ranking_products)): ?>
                            <?php foreach ($ranking_products as $product): ?>
                                <?php
                                    $tags_display = '';
                                    if (!empty($product['tags'])) {
                                        $tags_array = explode(', ', $product['tags']);
                                        $tags_to_show = array_slice($tags_array, 0, 3);
                                        $tags_display = '#' . implode(' #', array_map('htmlspecialchars', $tags_to_show));
                                    }
                                ?>
                                <div class="swiper-slide product-item">
                                    <a href="product.php?id=<?php echo htmlspecialchars($product['product_id']); ?>">
                                        <div class="product-item__img-wrap">
                                            <img src="<?php echo htmlspecialchars($product['main_image_path'] ?? 'img/no-image.png'); ?>" alt="<?php echo htmlspecialchars($product['product_name']); ?>">
                                        </div>
                                        <h3 class="product-item__name"><?php echo htmlspecialchars($product['product_name']); ?></h3>
                                        <p class="product-item__price">¥ <?php echo number_format($product['product_price']); ?><span>(税込)</span></p>
                                        <p class="product-item__tag"><?php echo $tags_display; ?></p>
                                    </a>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p style="text-align:center;">現在、ランキングデータを集計中です。</p>
                        <?php endif; ?>
                    </div>
                    <div class="swiper-pagination"></div>
                </div>
                <a href="products_list.php?sort=ranking" class="btn-all-products">ランキング一覧を見る</a>
            </div>
        </section>
        <section class="products beginner-friendly">
            <div class="products__inner">
                <h2 class="section-title">
                    <span class="en">FOR BEGINNERS</span>
                    <span class="ja">( 初めての方へおすすめの一杯 )</span>
                </h2>
                <div class="swiper mySwiperBeginners">
                    <div class="swiper-wrapper">
                        <?php if (!empty($beginner_products)): ?>
                            <?php foreach ($beginner_products as $product): ?>
                                <?php
                                    $tags_display = '';
                                    if (!empty($product['tags'])) {
                                        $tags_array = explode(', ', $product['tags']);
                                        $tags_to_show = array_slice($tags_array, 0, 3);
                                        $tags_display = '#' . implode(' #', array_map('htmlspecialchars', $tags_to_show));
                                    }
                                ?>
                                <div class="swiper-slide product-item">
                                    <a href="product.php?id=<?php echo htmlspecialchars($product['product_id']); ?>">
                                        <div class="product-item__img-wrap">
                                            <img src="<?php echo htmlspecialchars($product['main_image_path'] ?? 'img/no-image.png'); ?>" alt="<?php echo htmlspecialchars($product['product_name']); ?>">
                                        </div>
                                        <h3 class="product-item__name"><?php echo htmlspecialchars($product['product_name']); ?></h3>
                                        <p class="product-item__price">¥ <?php echo number_format($product['product_price']); ?><span>(税込)</span></p>
                                        <p class="product-item__tag"><?php echo $tags_display; ?></p>
                                    </a>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p style="text-align:center;">現在、おすすめ商品を準備中です。</p>
                        <?php endif; ?>
                    </div>
                    <div class="swiper-pagination"></div>
                </div>
                <a href="products_list.php?tag=初心者向け" class="btn-all-products">初心者向け一覧を見る</a>
            </div>
        </section>
        <section class="categories">
            <div class="categories__inner">
                <h2 class="section-title">
                    <span class="en">CATEGORIES</span>
                    <span class="ja">カテゴリー</span>
                </h2>
                <ul class="category-list">
                    <li class="category-list__item">
                        <a href="products_list.php?category=日本酒" class="category-card">
                            <img src="img/sake.png" alt="日本酒" class="category-card__img">
                            <h3 class="category-card__name">日本酒</h3>
                        </a>
                    </li>
                    <li class="category-list__item">
                        <a href="products_list.php?category=中国酒" class="category-card">
                            <img src="img/国花瓷(こっかじ) 中国酒　斜めを向いて倒れている　中国風のものに囲まれている.png" alt="中国酒" class="category-card__img">
                            <h3 class="category-card__name">中国酒</h3>
                        </a>
                    </li>
                    <li class="category-list__item">
                        <a href="products_list.php?category=梅酒" class="category-card">
                            <img src="img/梅酒原酒_image1.png" alt="梅酒" class="category-card__img">
                            <h3 class="category-card__name">梅酒</h3>
                        </a>
                    </li>
                    <li class="category-list__item">
                        <a href="products_list.php?category=缶チューハイ" class="category-card">
                            <img src="img/chuhai.png" alt="缶チューハイ" class="category-card__img">
                            <h3 class="category-card__name">缶チューハイ</h3>
                        </a>
                    </li>
                    <li class="category-list__item">
                        <a href="products_list.php?category=焼酎" class="category-card">
                            <img src="img/shochu.png" alt="焼酎" class="category-card__img">
                            <h3 class="category-card__name">焼酎</h3>
                        </a>
                    </li>
                    <li class="category-list__item">
                        <a href="products_list.php?category=ウィスキー" class="category-card">
                            <img src="img/whisky.png" alt="ウィスキー" class="category-card__img">
                            <h3 class="category-card__name">ウィスキー</h3>
                        </a>
                    </li>
                    <li class="category-list__item">
                        <a href="products_list.php?category=スピリッツ" class="category-card">
                            <img src="img/ボンベイ・サファイア スピリッツ　大きく表示　斜めをむいて倒れている　レモンに囲まれている.png" alt="スピリッツ" class="category-card__img">
                            <h3 class="category-card__name">スピリッツ</h3>
                        </a>
                    </li>
                    <li class="category-list__item">
                        <a href="products_list.php?category=リキュール" class="category-card">
                            <img src="img/liqueur.png" alt="リキュール" class="category-card__img">
                            <h3 class="category-card__name">リキュール</h3>
                        </a>
                    </li>
                    <li class="category-list__item">
                        <a href="products_list.php?category=ワイン" class="category-card">
                            <img src="img/wine.png" alt="ワイン" class="category-card__img">
                            <h3 class="category-card__name">ワイン</h3>
                        </a>
                    </li>
                    <li class="category-list__item">
                        <a href="products_list.php?category=ビール" class="category-card">
                            <img src="img/beer.png" alt="ビール" class="category-card__img">
                            <h3 class="category-card__name">ビール</h3>
                        </a>
                    </li>
                </ul>
                <a href="products_list.php?sort=newest" class="btn-all-products" style="margin-top: 32px;">すべての商品を見る</a>
            </div>
        </section>
    </main>

    <?php 
    // 共通フッターを読み込む
    require_once 'footer.php'; 
    ?>

    <script src="https://unpkg.com/swiper@8/swiper-bundle.min.js"></script>
    <script src="js/script.js"></script>
</body>

</html>
