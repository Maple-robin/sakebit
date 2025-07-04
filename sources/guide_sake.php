<?php
/*!
@file guide_sake.php
@brief 日本酒ガイドページ
@copyright Copyright (c) 2024 Your Name.
*/

// ★注意: DB接続やセッション開始は header.php で行われるため、このファイルでの処理は不要です。

?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>日本酒ガイド | OUR BRAND</title>
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
        <section class="guide-hero" style="background-image: url('img/sake.png');">
            <div class="guide-hero__inner">
                <h2 class="guide-hero__title">日本酒の世界へようこそ</h2>
                <p class="guide-hero__subtitle">奥深い味わいの探求</p>
            </div>
        </section>

        <!-- 日本酒って何？ -->
        <section class="guide-section intro-section">
            <div class="section-inner">
                <div class="section-title">
                    <h2 class="ja">日本酒ってなんだろう？</h2>
                    <p class="en">What is Japanese Sake?</p>
                </div>
                <div class="intro-content">
                    <div class="intro-content__image-container">
                        <img src="img/osake.png" alt="日本酒の紹介画像" class="intro-content__image">
                        <div class="intro-content__overlay">
                            <p>日本酒は、米、米麹、水を主原料として発酵させて造られる、日本古来の醸造酒です。「清酒」とも呼ばれ、日本の食文化に深く根付いています。地域ごとの気候や水、米の違いが、多様な風味や香りを生み出します。</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- 初心者におすすめの日本酒 -->
        <section class="guide-section beginner-types">
            <div class="section-inner">
                <div class="section-title">
                    <h2 class="ja">初心者におすすめの種類</h2>
                    <p class="en">For Beginners</p>
                </div>
                <div class="alcohol-types">
                    <div class="type-card">
                        <h4>純米酒</h4>
                        <p>米と米麹、水だけで造られた、米本来の旨味やコクが味わえるタイプ。料理との相性も抜群です。</p>
                    </div>
                    <div class="type-card type-card--bg" style="background-image: url('img/osyareOsake.png');">
                        <h4>吟醸酒</h4>
                        <p>よく磨いた米を低温でゆっくり発酵させて造られ、フルーティーで華やかな香りが特徴です。</p>
                    </div>
                    <div class="type-card">
                        <h4>本醸造酒</h4>
                        <p>すっきりとキレのある味わいが特徴。冷やしても燗にしても美味しく、日常的に楽しめます。</p>
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
                <!-- 冷やして飲む -->
                <div class="drink-way-item">
                    <div class="drink-way-item__title">
                        <h3>冷やして（冷酒）</h3>
                    </div>
                    <div class="drink-way-item__step">
                        <div class="drink-way-item__description">
                            <p>冷蔵庫で5〜10℃に冷やして。吟醸酒など香りの高いお酒は、その華やかさが引き立ちます。すっきりとした飲み口に。</p>
                        </div>
                        <div class="drink-way-item__image">
                            <img src="img/sake-cold.jpg" alt="グラスに注がれた冷たい日本酒">
                        </div>
                    </div>
                </div>

                <hr>

                <!-- 常温で飲む -->
                <div class="drink-way-item">
                    <div class="drink-way-item__title">
                        <h3>常温で（冷や）</h3>
                    </div>
                    <div class="drink-way-item__step">
                        <div class="drink-way-item__description">
                            <p>15〜20℃くらいの温度帯。お酒本来の味と香りが最もよくわかります。純米酒など、米の旨味をじっくり味わいたい時に。</p>
                        </div>
                        <div class="drink-way-item__image">
                            <img src="img/sake-normal.jpg" alt="常温の日本酒">
                        </div>
                    </div>
                </div>

                <hr>

                <!-- 温めて飲む -->
                <div class="drink-way-item">
                    <div class="drink-way-item__title">
                        <h3>温めて（燗酒）</h3>
                    </div>
                    <div class="drink-way-item__step">
                        <div class="drink-way-item__description">
                            <p>徳利に入れて湯煎で温めます。温度によって「ぬる燗」「熱燗」など呼び名が変わり、風味も豊かになります。寒い日にぴったり。</p>
                        </div>
                        <div class="drink-way-item__image">
                            <img src="img/sake-warm.jpg" alt="温かいお酒、熱燗">
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- 初心者におすすめの日本酒カルーセル -->
        <section class="guide-section recommended-sake">
            <div class="section-inner">
                <div class="section-title">
                    <h2 class="ja">初心者におすすめの日本酒</h2>
                    <p class="en">Recommended Sake for Beginners</p>
                </div>
                <div class="swiper recommended-sake-swiper">
                    <div class="swiper-wrapper">
                        <!-- 商品1 -->
                        <div class="swiper-slide product-item">
                            <a href="product.php?id=1">
                                <div class="product-item__img-wrap">
                                    <img src="img/sake_1.jpg" alt="商品1">
                                </div>
                                <h3 class="product-item__name">獺祭 純米大吟醸45</h3>
                                <p class="product-item__price">¥ 3,300<span>(税込)</span></p>
                                <p class="product-item__tag">#フルーティー #定番</p>
                            </a>
                        </div>
                        <!-- 商品2 -->
                        <div class="swiper-slide product-item">
                            <a href="product.php?id=2">
                                <div class="product-item__img-wrap">
                                    <img src="img/sake_2.jpg" alt="商品2">
                                </div>
                                <h3 class="product-item__name">久保田 千寿</h3>
                                <p class="product-item__price">¥ 2,800<span>(税込)</span></p>
                                <p class="product-item__tag">#すっきり #食事と</p>
                            </a>
                        </div>
                        <!-- 商品3 -->
                        <div class="swiper-slide product-item">
                            <a href="product.php?id=3">
                                <div class="product-item__img-wrap">
                                    <img src="img/sake_3.jpg" alt="商品3">
                                </div>
                                <h3 class="product-item__name">八海山 普通酒</h3>
                                <p class="product-item__price">¥ 2,500<span>(税込)</span></p>
                                <p class="product-item__tag">#淡麗辛口 #定番</p>
                            </a>
                        </div>
                        <!-- 商品4 -->
                        <div class="swiper-slide product-item">
                            <a href="product.php?id=4">
                                <div class="product-item__img-wrap">
                                    <img src="img/sake_4.jpg" alt="商品4">
                                </div>
                                <h3 class="product-item__name">出羽桜 桜花吟醸酒</h3>
                                <p class="product-item__price">¥ 3,000<span>(税込)</span></p>
                                <p class="product-item__tag">#華やか #吟醸香</p>
                            </a>
                        </div>
                        <!-- 商品5 -->
                        <div class="swiper-slide product-item">
                            <a href="product.php?id=5">
                                <div class="product-item__img-wrap">
                                    <img src="img/sake_5.jpg" alt="商品5">
                                </div>
                                <h3 class="product-item__name">作 穂乃智</h3>
                                <p class="product-item__price">¥ 2,700<span>(税込)</span></p>
                                <p class="product-item__tag">#軽快 #透明感</p>
                            </a>
                        </div>
                    </div>
                    <!-- If we need pagination -->
                    <div class="swiper-pagination"></div>
                </div>
                 <a href="products_list.php?tag=初心者向け" class="btn-all-products">初心者向け一覧を見る</a>
            </div>
        </section>
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
