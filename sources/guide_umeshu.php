<?php
/*!
@file guide_umeshu.php
@brief 梅酒ガイドページ
@copyright Copyright (c) 2024 Your Name.
*/

// ★★★【変更箇所】★★★
// データベースクラスを直接使用するため、contents_db.php を読み込みます。
require_once 'common/contents_db.php';

// 「初心者向け」タグを持つ商品を優先的に取得し、足りない分は売上順で補完
$umeshu_category_id = 3;
$priority_tag_name = '初心者向け'; // 優先したいタグ名を指定
$product_db = new cproduct_info();
$recommended_umeshu_products = $product_db->get_recommended_products_for_guide(false, $umeshu_category_id, $priority_tag_name, 5);
?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>梅酒ガイド - SAKEBIT</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@300;400;500;700&family=Zen+Old+Mincho:wght@400;500;700&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/swiper@8/swiper-bundle.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="css/top.css">
    <link rel="stylesheet" href="css/guide.css">
</head>

<body>
    <?php
    // 共通ヘッダーを読み込む
    require_once 'header.php';
    ?>

    <main>
        <!-- ヒーローセクション -->
        <section class="guide-hero" style="background-image: url('img/梅酒梅たくさん.png');">
            <div class="guide-hero__inner">
                <h2 class="guide-hero__title">梅酒の世界へようこそ</h2>
                <p class="guide-hero__subtitle">梅が織りなす、癒やしの甘み</p>
            </div>
        </section>

        <!-- 梅酒って何？ -->
        <section class="guide-section guide-intro-section">
            <div class="section-inner">
                <div class="section-title">
                    <h2 class="ja">梅酒ってなんだろう？</h2>
                    <p class="en">What is Umeshu?</p>
                </div>
                <div class="intro-content">
                    <div class="intro-content__image-container">
                        <img src="img/梅酒コップ.png" alt="梅酒の紹介画像" class="intro-content__image">
                        <div class="intro-content__overlay">
                            <p>梅酒は、青梅を氷砂糖と共に焼酎などの蒸留酒（または醸造酒）に漬け込んで造られる日本の伝統的なリキュールです。梅の爽やかな酸味と、氷砂糖の優しい甘みが調和した、親しみやすい味わいが特徴です。</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- 主な種類 -->
        <section class="guide-section beginner-types">
            <div class="section-inner">
                <div class="section-title">
                    <h2 class="ja">主な種類</h2>
                    <p class="en">Major Types</p>
                </div>
                <div class="alcohol-types">
                    <div class="type-card type-card--bg" style="background-image: url('img/梅酒ホワイトリカー.png');">
                        <h4>ホワイトリカーベース</h4>
                        <p>最も一般的で、すっきりとクリアな味わい。梅本来の風味をストレートに楽しめます。</p>
                    </div>
                    <div class="type-card type-card--bg" style="background-image: url('img/梅酒ブランデー.png');">
                        <h4>ブランデーベース</h4>
                        <p>ブランデーの芳醇な香りとコクが加わり、重厚で贅沢な味わいの梅酒になります。</p>
                    </div>
                    <div class="type-card type-card--bg" style="background-image: url('img/梅酒日本酒.png');">
                        <h4>日本酒ベース</h4>
                        <p>日本酒の米の旨味と梅の酸味が調和し、まろやかで深みのある味わいが楽しめます。</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- おいしい飲み方を見つけよう -->
        <section class="how-to-drink-section section-inner">
            <div class="section-title">
                <h2 class="ja">おいしい飲み方を見つけよう</h2>
                <p class="en">HOW TO DRINK</p>
            </div>

            <div class="drink-ways">
                <!-- ソーダ割り -->
                <div class="drink-way-item">
                    <div class="drink-way-item__title">
                        <h3>ソーダ割り</h3>
                    </div>
                    <div class="drink-way-item__step">
                        <div class="drink-way-item__description">
                            <p>梅酒の甘みと酸味がソーダの炭酸で引き立ち、爽快なのどごしに。食前酒にもぴったりです。</p>
                        </div>
                        <div class="drink-way-item__image">
                            <img src="img/梅酒ソーダ割り.png" alt="梅酒ソーダ割り">
                        </div>
                    </div>
                </div>

                <hr>

                <!-- ロック -->
                <div class="drink-way-item">
                    <div class="drink-way-item__title">
                        <h3>ロック</h3>
                    </div>
                    <div class="drink-way-item__step">
                        <div class="drink-way-item__description">
                            <p>氷がゆっくり溶けることで、味わいの変化を楽しめます。梅酒本来の濃厚な味わいをじっくりと楽しみたい方へ。</p>
                        </div>
                        <div class="drink-way-item__image">
                            <img src="img/梅酒ロック.png" alt="梅酒ロック">
                        </div>
                    </div>
                </div>

                <hr>

                <!-- お湯割り -->
                <div class="drink-way-item">
                    <div class="drink-way-item__title">
                        <h3>お湯割り</h3>
                    </div>
                    <div class="drink-way-item__step">
                        <div class="drink-way-item__description">
                            <p>梅の香りがふわりと立ち上り、心も体も温まる飲み方。寝る前のリラックスタイムにもおすすめです。</p>
                        </div>
                        <div class="drink-way-item__image">
                            <img src="img/梅酒お湯割り.png" alt="梅酒お湯割り">
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- ★★★【変更箇所】おすすめの梅酒 (動的カルーセル) ★★★ -->
        <?php if (!empty($recommended_umeshu_products)) : ?>
            <section class="guide-section recommended-sake">
                <div class="section-inner">
                    <div class="section-title">
                        <h2 class="ja">おすすめの梅酒</h2>
                        <p class="en">Recommended Umeshu</p>
                    </div>
                    <div class="swiper recommended-sake-swiper">
                        <div class="swiper-wrapper">
                            <?php foreach ($recommended_umeshu_products as $product) : ?>
                                <div class="swiper-slide product-item">
                                    <a href="product.php?id=<?php echo htmlspecialchars($product['product_id'], ENT_QUOTES, 'UTF-8'); ?>">
                                        <div class="product-item__img-wrap">
                                            <img src="<?php echo htmlspecialchars($product['main_image_path'] ?? 'img/no-image.png', ENT_QUOTES, 'UTF-8'); ?>" alt="<?php echo htmlspecialchars($product['product_name'], ENT_QUOTES, 'UTF-8'); ?>">
                                        </div>
                                        <h3 class="product-item__name"><?php echo htmlspecialchars($product['product_name'], ENT_QUOTES, 'UTF-8'); ?></h3>
                                        <p class="product-item__price">¥ <?php echo number_format($product['product_price']); ?><span>(税込)</span></p>
                                    </a>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        <!-- If we need pagination -->
                        <div class="swiper-pagination"></div>
                    </div>
                    <a href="products_list.php?category=<?php echo $umeshu_category_id; ?>" class="btn-all-products">梅酒一覧を見る</a>
                </div>
            </section>
        <?php endif; ?>
        <!-- ★★★【変更箇所ここまで】★★★ -->

        <!-- ガイド一覧セクション -->
        <section class="categories">
            <div class="categories__inner">
                <h2 class="section-title">
                    <span class="ja">ガイド一覧</span>
                    <span class="en">GUIDE LIST</span>
                </h2>
                <ul class="category-list">
                    <li class="category-list__item">
                        <a href="guide_sake.php" class="category-card">
                            <img src="img/日本酒カテゴリ.png" alt="日本酒ガイド" class="category-card__img">
                            <h3 class="category-card__name">日本酒</h3>
                        </a>
                    </li>
                    <li class="category-list__item">
                        <a href="guide_chinese_sake.php" class="category-card">
                            <img src="img/国花瓷(こっかじ) 中国酒　斜めを向いて倒れている　中国風のものに囲まれている.png" alt="中国酒ガイド" class="category-card__img">
                            <h3 class="category-card__name">中国酒</h3>
                        </a>
                    </li>
                    <li class="category-list__item">
                        <a href="guide_umeshu.php" class="category-card">
                            <img src="img/梅酒原酒_image1.png" alt="梅酒ガイド" class="category-card__img">
                            <h3 class="category-card__name">梅酒</h3>
                        </a>
                    </li>
                    <li class="category-list__item">
                        <a href="guide_chuhai.php" class="category-card">
                            <img src="img/缶チューハイカテゴリ.png" alt="缶チューハイガイド" class="category-card__img">
                            <h3 class="category-card__name">缶チューハイ</h3>
                        </a>
                    </li>
                    <li class="category-list__item">
                        <a href="guide_shochu.php" class="category-card">
                            <img src="img/金次郎1.png" alt="焼酎ガイド" class="category-card__img">
                            <h3 class="category-card__name">焼酎</h3>
                        </a>
                    </li>
                    <li class="category-list__item">
                        <a href="guide_whisky.php" class="category-card">
                            <img src="img/ウイスキーカテゴリ.png" alt="ウィスキーガイド" class="category-card__img">
                            <h3 class="category-card__name">ウィスキー</h3>
                        </a>
                    </li>
                    <li class="category-list__item">
                        <a href="guide_spirits.php" class="category-card">
                            <img src="img/ボンベイ・サファイア スピリッツ　大きく表示　斜めをむいて倒れている　レモンに囲まれている.png" alt="スピリッツガイド" class="category-card__img">
                            <h3 class="category-card__name">スピリッツ</h3>
                        </a>
                    </li>
                    <li class="category-list__item">
                        <a href="guide_liquor.php" class="category-card">
                            <img src="img/バラ梅酒1.png" alt="リキュールガイド" class="category-card__img">
                            <h3 class="category-card__name">リキュール</h3>
                        </a>
                    </li>
                    <li class="category-list__item">
                        <a href="guide_wine.php" class="category-card">
                            <img src="img/ワインカテゴリ.png" alt="ワインガイド" class="category-card__img">
                            <h3 class="category-card__name">ワイン</h3>
                        </a>
                    </li>
                    <li class="category-list__item">
                        <a href="guide_beer.php" class="category-card">
                            <img src="img/ビールカテゴリ.png" alt="ビールガイド" class="category-card__img">
                            <h3 class="category-card__name">ビール</h3>
                        </a>
                    </li>
                </ul>
            </div>
        </section>
        <!-- /ガイド一覧セクション -->
    </main>

    <?php
    // 共通フッターを読み込む
    require_once 'footer.php';
    ?>

    <script src="https://unpkg.com/swiper@8/swiper-bundle.min.js"></script>
    <script src="js/script.js"></script>
    <script src="js/guide.js"></script>
</body>

</html>