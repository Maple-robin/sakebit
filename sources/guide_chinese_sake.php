<?php
// ヘッダーでセッション開始、DB接続、共通関数の読み込みを行っています。

// ★★★【変更箇所】★★★
// データベースクラスを直接使用するため、contents_db.php を読み込みます。
require_once 'common/contents_db.php';

// 「初心者向け」タグを持つ商品を優先的に取得し、足りない分は売上順で補完
$chinese_sake_category_id = 2;
$priority_tag_name = '初心者向け'; // 優先したいタグ名を指定
$product_db = new cproduct_info();
$recommended_chinese_sake_products = $product_db->get_recommended_products_for_guide(false, $chinese_sake_category_id, $priority_tag_name, 5);
?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>中国酒ガイド - SAKEBIT</title>
    <link rel="stylesheet" href="https://unpkg.com/swiper@8/swiper-bundle.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="css/top.css">
    <link rel="stylesheet" href="css/guide.css">
</head>

<body>
    <?php require_once 'header.php'; ?>

    <main>
        <!-- ヒーローセクション -->
        <section class="guide-hero" style="background-image: url('img/Chinese_hukei.png');">
            <div class="guide-hero__inner">
                <h2 class="guide-hero__title">中国酒の世界へようこそ</h2>
                <p class="guide-hero__subtitle">悠久の歴史と多様な香り</p>
            </div>
        </section>

        <!-- 中国酒って何？ -->
        <section class="guide-section guide-intro-section">
            <div class="section-inner">
                <div class="section-title">
                    <h2 class="ja">中国酒ってなんだろう？</h2>
                    <p class="en">What is Chinese Liquor?</p>
                </div>
                <div class="intro-content">
                    <div class="intro-content__image-container">
                        <img src="img/Chinese_sake_all.png" alt="中国酒の紹介画像" class="intro-content__image">
                        <div class="intro-content__overlay">
                            <p>中国酒は、数千年の歴史を持つ中国のお酒の総称です。<br>広大な国土と多様な気候風土を背景に、穀物を主原料とした白酒、もち米などを原料とする黄酒など、多種多様なお酒が造られています。</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- 主な種類 -->
        <section class="guide-section guide-beginner-types">
            <div class="section-inner">
                <div class="section-title">
                    <h2 class="ja">主な種類</h2>
                    <p class="en">Major Types</p>
                </div>
                <div class="alcohol-types">
                    <div class="type-card type-card--bg" style="background-image: url('img/白酒.png');">
                        <h4>白酒（バイチュウ）</h4>
                        <p>アルコール度数が高く、刺激的で力強い味わいと独特の香りが特徴です。</p>
                    </div>
                    <div class="type-card type-card--bg" style="background-image: url('img/黄酒.png');">
                        <h4>黄酒（ホアンチュウ）</h4>
                        <p>まろやかな口当たりと深いコク、やさしい甘みが特徴です。</p>
                    </div>
                    <div class="type-card type-card--bg" style="background-image: url('img/果酒.png');">
                        <h4>果酒（カシュ）</h4>
                        <p>果実の風味が豊かで、フルーティーな甘さが楽しめます。</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- おいしい飲み方を見つけよう -->
        <section class="guide-section guide-how-to-drink">
            <div class="section-inner">
                <div class="section-title">
                    <h2 class="ja">おいしい飲み方を見つけよう</h2>
                    <p class="en">HOW TO DRINK</p>
                </div>

                <div class="drink-ways">
                    <!-- ストレート -->
                    <div class="drink-way-item">
                        <div class="drink-way-item__title">
                            <h3>ストレート</h3>
                        </div>
                        <div class="drink-way-item__step">
                            <div class="drink-way-item__description">
                                <p>そのまま飲むと、しっかりした味わいや香りをダイレクトに感じられます。</p>
                            </div>
                            <div class="drink-way-item__image">
                                <img src="img/中国酒ストレート.png" alt="ストレートの中国酒">
                            </div>
                        </div>
                    </div>

                    <hr>

                    <!-- 温めて飲む -->
                    <div class="drink-way-item">
                        <div class="drink-way-item__title">
                            <h3>温めて飲む（熱燗）</h3>
                        </div>
                        <div class="drink-way-item__step">
                            <div class="drink-way-item__description">
                                <p>温めると香りや甘みが増し、まろやかな味わいになります。</p>
                            </div>
                            <div class="drink-way-item__image">
                                <img src="img/中国酒熱燗.png" alt="温めた紹興酒">
                            </div>
                        </div>
                    </div>

                    <hr>

                    <!-- ソーダ割り -->
                    <div class="drink-way-item">
                        <div class="drink-way-item__title">
                            <h3>ソーダ割り</h3>
                        </div>
                        <div class="drink-way-item__step">
                            <div class="drink-way-item__description">
                                <p>炭酸水で割るとすっきりとした飲み口になり、軽やかな味わいが楽しめます。</p>
                            </div>
                            <div class="drink-way-item__image">
                                <img src="img/中国酒ソーダ割り.png" alt="中国酒のソーダ割り">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- ★★★【変更箇所】おすすめの中国酒 (動的カルーセル) ★★★ -->
        <?php if (!empty($recommended_chinese_sake_products)) : ?>
            <section class="guide-section recommended-sake">
                <div class="section-inner">
                    <div class="section-title">
                        <h2 class="ja">おすすめの中国酒</h2>
                        <p class="en">Recommended Chinese Sake</p>
                    </div>
                    <div class="swiper recommended-sake-swiper">
                        <div class="swiper-wrapper">
                            <?php foreach ($recommended_chinese_sake_products as $product) : ?>
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
                    <a href="products_list.php?category=<?php echo $chinese_sake_category_id; ?>" class="btn-all-products">中国酒一覧を見る</a>
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

    <?php require_once 'footer.php'; ?>

    <script src="https://unpkg.com/swiper@8/swiper-bundle.min.js"></script>
    <script src="js/script.js"></script>
    <script src="js/guide.js"></script>
</body>

</html>