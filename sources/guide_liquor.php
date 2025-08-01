<?php
/*!
@file guide_liquor.php
@brief リキュールガイドページ
@copyright Copyright (c) 2024 Your Name.
*/

// ★★★【変更箇所】★★★
// データベースクラスを直接使用するため、contents_db.php を読み込みます。
require_once 'common/contents_db.php';

// 「初心者向け」タグを持つ商品を優先的に取得し、足りない分は売上順で補完
$liquor_category_id = 8;
$priority_tag_name = '初心者向け'; // 優先したいタグ名を指定
$product_db = new cproduct_info();
$recommended_liquor_products = $product_db->get_recommended_products_for_guide(false, $liquor_category_id, $priority_tag_name, 5);
?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>リキュールガイド | SAKE BIT</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@300;400;500;700&family=Zen+Old+Mincho:wght@400;500;700&display=swap"
        rel="stylesheet">

    <link rel="stylesheet" href="https://unpkg.com/swiper@8/swiper-bundle.min.css" />
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
        <section class="guide-hero" style="background-image: url('img/リキュール.png');">
            <div class="guide-hero__inner">
                <h2 class="guide-hero__title">リキュールの世界へようこそ</h2>
                <p class="guide-hero__subtitle">創造的なカクテルの探求</p>
            </div>
        </section>

        <!-- リキュールって何？ -->
        <section class="guide-section guide-intro-section">
            <div class="section-inner">
                <div class="section-title">
                    <h2 class="ja">リキュールってなんだろう？</h2>
                    <p class="en">What is Liqueur?</p>
                </div>
                <div class="intro-content">
                    <div class="intro-content__image-container">
                        <img src="img/リキュールとは.png" alt="リキュールの紹介画像" class="intro-content__image">
                        <div class="intro-content__overlay">
                            <p>リキュールは、蒸留酒に果物やハーブ、スパイスなどの風味を加え、砂糖やシロップで甘みをつけたお酒です。多彩なフレーバーが特徴で、カクテルのベースとして、また食後酒としても楽しまれます。</p>
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
                    <div class="type-card type-card--bg" style="background-image: url('img/フルーツ系.png');">
                        <h4>フルーツ系</h4>
                        <p>カシスやピーチ、オレンジなど、様々な果物の甘みと香りが楽しめます。ジュース割りやソーダ割りに最適です。</p>
                    </div>
                    <div class="type-card type-card--bg" style="background-image: url('img/ハーブ系.png');">
                        <h4>ハーブ・薬草系</h4>
                        <p>独特のハーブやスパイスの香りが特徴で、食後酒としても人気です。ストレートやロックでじっくり味わえます。</p>
                    </div>
                    <div class="type-card type-card--bg" style="background-image: url('img/クリーム系.png');">
                        <h4>クリーム系</h4>
                        <p>ミルクやクリームをベースにした、甘くてまろやかな味わいです。デザート感覚で楽しめ、ホットカクテルにも向いています。</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- おいしい飲み方を見つけよう -->
        <section class="guide-section how-to-drink">
            <div class="section-inner">
                <div class="section-title">
                    <h2 class="ja">おいしい割り方を見つけよう</h2>
                    <p class="en">HOW TO MIX</p>
                </div>

                <div class="drink-ways">
                    <!-- 炭酸で割る -->
                    <div class="drink-way-item">
                        <div class="drink-way-item__title">
                            <h3>炭酸で割る（ソーダ、トニック）</h3>
                        </div>
                        <div class="drink-way-item__step">
                            <div class="drink-way-item__description">
                                <p>リキュール1に対して、炭酸水を3〜4の割合で。清涼感あふれる定番のスタイル。レモンやライムを搾るとさらに爽やかに。</p>
                            </div>
                            <div class="drink-way-item__image">
                                <img src="img/リキュールソーダ割り.png" alt="炭酸で割ったリキュール">
                            </div>
                        </div>
                    </div>

                    <hr>

                    <!-- ジュースで割る -->
                    <div class="drink-way-item">
                        <div class="drink-way-item__title">
                            <h3>ジュースで割る</h3>
                        </div>
                        <div class="drink-way-item__step">
                            <div class="drink-way-item__description">
                                <p>オレンジ、グレープフルーツ、パインなどお好みのジュースで。甘みが加わり、アルコール感が和らぐので初心者にもおすすめです。</p>
                            </div>
                            <div class="drink-way-item__image">
                                <img src="img/リキュールグレフル割り.png" alt="ジュースで割ったリキュール">
                            </div>
                        </div>
                    </div>

                    <hr>

                    <!-- ミルクで割る -->
                    <div class="drink-way-item">
                        <div class="drink-way-item__title">
                            <h3>ミルクで割る</h3>
                        </div>
                        <div class="drink-way-item__step">
                            <div class="drink-way-item__description">
                                <p>カシスやカルーア、マリブなど、フルーツ系やコーヒー系のリキュールと相性抜群。デザート感覚で楽しめるまろやかなカクテルに。</p>
                            </div>
                            <div class="drink-way-item__image">
                                <img src="img/リキュールカルーアミルク.png" alt="ミルクで割ったリキュール">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- ★★★【変更箇所】おすすめのリキュール (動的カルーセル) ★★★ -->
        <?php if (!empty($recommended_liquor_products)) : ?>
            <section class="guide-section recommended-sake">
                <div class="section-inner">
                    <div class="section-title">
                        <h2 class="ja">おすすめのリキュール</h2>
                        <p class="en">Recommended Liqueur</p>
                    </div>
                    <div class="swiper recommended-sake-swiper">
                        <div class="swiper-wrapper">
                            <?php foreach ($recommended_liquor_products as $product) : ?>
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
                    <a href="products_list.php?category=<?php echo $liquor_category_id; ?>" class="btn-all-products">リキュール一覧を見る</a>
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