<?php
// ヘッダーでセッション開始、DB接続、共通関数の読み込みを行っています。

// ★★★【変更箇所】★★★
// データベースクラスを直接使用するため、contents_db.php を読み込みます。
require_once 'common/contents_db.php';

// 「初心者向け」タグを持つ商品を優先的に取得し、足りない分は売上順で補完
$shochu_category_id = 5;
$priority_tag_name = '初心者向け'; // 優先したいタグ名を指定
$product_db = new cproduct_info();
$recommended_shochu_products = $product_db->get_recommended_products_for_guide(false, $shochu_category_id, $priority_tag_name, 5);
?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>焼酎ガイド | SAKE BIT</title>
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
        <section class="guide-hero" style="background-image: url('img/焼酎製造.png');">
            <div class="guide-hero__inner">
                <h2 class="guide-hero__title">焼酎の世界へようこそ</h2>
                <p class="guide-hero__subtitle">伝統と革新の味わい</p>
            </div>
        </section>

        <!-- 焼酎って何？ -->
        <section class="guide-section guide-intro-section">
            <div class="section-inner">
                <div class="section-title">
                    <h2 class="ja">焼酎ってなんだろう？</h2>
                    <p class="en">What is Shochu?</p>
                </div>
                <div class="intro-content">
                    <div class="intro-content__image-container">
                        <img src="img/焼酎.png" alt="焼酎の紹介画像" class="intro-content__image">
                        <div class="intro-content__overlay">
                            <p>焼酎は、米、麦、芋、そばなど様々な原料を蒸留して造られる日本の伝統的なお酒です。原料由来の豊かな風味とクリアな味わいが特徴で、多様な飲み方で楽しまれています。</p>
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
                    <div class="type-card type-card--bg" style="background-image: url('img/麦と焼酎.png');">
                        <h4>麦焼酎</h4>
                        <p>麦を原料とし、軽やかで香ばしい風味が特徴。<br>クセが少なく、すっきりとした味わいです。</p>
                    </div>
                    <div class="type-card type-card--bg" style="background-image: url('img/米と焼酎.png');">
                        <h4>米焼酎</h4>
                        <p>日本酒のような香りと、上品な甘みが特徴。<br>フルーティーで飲みやすいものも多いです。</p>
                    </div>
                    <div class="type-card type-card--bg" style="background-image: url('img/芋焼酎.png');">
                        <h4>芋焼酎</h4>
                        <p>さつまいもを原料とし、華やかな香りが魅力。<br>近年は飲みやすくされたものも増えています。</p>
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
                <!-- 水割り -->
                <div class="drink-way-item">
                    <div class="drink-way-item__title">
                        <h3>水割り</h3>
                    </div>
                    <div class="drink-way-item__step">
                        <div class="drink-way-item__description">
                            <p>焼酎と水を6:4で割ります。焼酎本来の味わいを楽しみながら、さっぱりとした飲み口に仕上げます。</p>
                        </div>
                        <div class="drink-way-item__image">
                            <img src="img/焼酎水割り.png" alt="水割り">
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
                            <p>水ではなくお湯で割ることで、焼酎の香りがより一層引き立ち、焼酎本来の深みが際立つ飲み方です。</p>
                        </div>
                        <div class="drink-way-item__image">
                            <img src="img/焼酎お湯割り.png" alt="お湯割り">
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
                            <p>焼酎を氷とグラスに注ぎシンプルに味わう飲み方です。時間と共に味が変化し、焼酎本来の味を楽しめます。</p>
                        </div>
                        <div class="drink-way-item__image">
                            <img src="img/焼酎ロック.png" alt="ロック">
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- ★★★【変更箇所】おすすめの焼酎 (動的カルーセル) ★★★ -->
        <?php if (!empty($recommended_shochu_products)) : ?>
            <section class="guide-section recommended-sake">
                <div class="section-inner">
                    <div class="section-title">
                        <h2 class="ja">おすすめの焼酎</h2>
                        <p class="en">Recommended Shochu</p>
                    </div>
                    <div class="swiper recommended-sake-swiper">
                        <div class="swiper-wrapper">
                            <?php foreach ($recommended_shochu_products as $product) : ?>
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
                    <a href="products_list.php?category=<?php echo $shochu_category_id; ?>" class="btn-all-products">焼酎一覧を見る</a>
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