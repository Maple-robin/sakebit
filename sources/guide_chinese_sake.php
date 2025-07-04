<?php
// ヘッダーでセッション開始、DB接続、共通関数の読み込みを行っています。
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
        <section class="guide-hero" style="background-image: url('img/chinese_lantern.png');">
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
                <div class="guide-intro-content">
                    <div class="guide-intro-content__image-container">
                        <img src="img/国花瓷.png" alt="中国酒の紹介画像" class="guide-intro-content__image">
                        <div class="guide-intro-content__overlay">
                            <p>中国酒は、数千年の歴史を持つ中国伝統のお酒の総称です。広大な国土と多様な気候風土を背景に、穀物を主原料とした白酒（バイチュウ）、もち米などを原料とする黄酒（ホアンチュウ）など、多種多様なお酒が造られています。</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- 初心者におすすめの種類 -->
        <section class="guide-section guide-beginner-types">
            <div class="section-inner">
                <div class="section-title">
                    <h2 class="ja">代表的な中国酒</h2>
                    <p class="en">Major Types</p>
                </div>
                <div class="guide-types">
                    <div class="guide-type-card">
                        <h4>白酒（バイチュウ）</h4>
                        <p>高粱（こうりゃん）などを原料とする蒸留酒。アルコール度数が高く、独特の強い香りが特徴です。</p>
                    </div>
                    <div class="guide-type-card guide-type-card--bg" style="background-image: url('img/shaoxing_wine.png');">
                        <h4>黄酒（ホアンチュウ）</h4>
                        <p>もち米やうるち米を原料とする醸造酒。紹興酒が有名で、まろやかな口当たりと深いコクが特徴です。</p>
                    </div>
                    <div class="guide-type-card">
                        <h4>果酒（カシュ）</h4>
                        <p>果実を原料としたお酒。杏露酒（シンルチュウ）などが知られ、フルーティーで甘い味わいが楽しめます。</p>
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
                            <h3>ストレート（常温・ロック）</h3>
                        </div>
                        <div class="drink-way-item__step">
                            <div class="drink-way-item__description">
                                <p>白酒は小さなグラスで、黄酒は常温やロックで、それぞれの本来の風味をじっくりと味わうのが伝統的です。</p>
                            </div>
                            <div class="drink-way-item__image">
                                <img src="img/baijiu_glass.png" alt="ストレートの中国酒">
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
                                <p>特に紹興酒は、温めることで香りが豊かになり、口当たりもまろやかになります。ザラメや干し梅を入れるのもおすすめです。</p>
                            </div>
                            <div class="drink-way-item__image">
                                <img src="img/hot_shaoxing.png" alt="温めた紹興酒">
                            </div>
                        </div>
                    </div>

                    <hr>

                    <!-- 中華料理とのペアリング -->
                    <div class="drink-way-item">
                        <div class="drink-way-item__title">
                            <h3>中華料理とのペアリング</h3>
                        </div>
                        <div class="drink-way-item__step">
                            <div class="drink-way-item__description">
                                <p>中国酒は、やはり中華料理との相性が抜群です。油分の多い料理をすっきりとさせ、料理の旨味を引き立てます。</p>
                            </div>
                            <div class="drink-way-item__image">
                                <img src="img/chinese_food.png" alt="中華料理と中国酒">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- おすすめ中国酒カルーセル -->
        <section class="guide-section recommended-sake">
            <div class="section-inner">
                <div class="section-title">
                    <h2 class="ja">おすすめの中国酒</h2>
                    <p class="en">Recommended Chinese Liquor</p>
                </div>
                <div class="swiper recommended-sake-swiper">
                    <div class="swiper-wrapper">
                        <!-- 商品1 -->
                        <div class="swiper-slide product-item">
                            <a href="product.php?id=901">
                                <div class="product-item__img-wrap">
                                    <img src="img/moutai.png" alt="マオタイ酒">
                                </div>
                                <h3 class="product-item__name">貴州茅台酒 (マオタイ酒)</h3>
                                <p class="product-item__price">¥ 45,000<span>(税込)</span></p>
                                <p class="product-item__tag">#白酒 #国酒</p>
                            </a>
                        </div>
                        <!-- 商品2 -->
                        <div class="swiper-slide product-item">
                            <a href="product.php?id=902">
                                <div class="product-item__img-wrap">
                                    <img src="img/wuliangye.png" alt="五粮液">
                                </div>
                                <h3 class="product-item__name">五粮液 (ゴリョウエキ)</h3>
                                <p class="product-item__price">¥ 30,000<span>(税込)</span></p>
                                <p class="product-item__tag">#白酒 #濃香型</p>
                            </a>
                        </div>
                        <!-- 商品3 -->
                        <div class="swiper-slide product-item">
                            <a href="product.php?id=903">
                                <div class="product-item__img-wrap">
                                    <img src="img/shaoxing_kame.png" alt="紹興酒">
                                </div>
                                <h3 class="product-item__name">古越龍山 陳年8年</h3>
                                <p class="product-item__price">¥ 3,500<span>(税込)</span></p>
                                <p class="product-item__tag">#黄酒 #紹興酒</p>
                            </a>
                        </div>
                        <!-- 商品4 -->
                        <div class="swiper-slide product-item">
                            <a href="product.php?id=904">
                                <div class="product-item__img-wrap">
                                    <img src="img/xinglujiu.png" alt="杏露酒">
                                </div>
                                <h3 class="product-item__name">杏露酒 (シンルチュウ)</h3>
                                <p class="product-item__price">¥ 1,200<span>(税込)</span></p>
                                <p class="product-item__tag">#果酒 #あんず</p>
                            </a>
                        </div>
                        <!-- 商品5 -->
                        <div class="swiper-slide product-item">
                            <a href="product.php?id=905">
                                <div class="product-item__img-wrap">
                                    <img src="img/国花瓷.png" alt="国花瓷西鳳酒">
                                </div>
                                <h3 class="product-item__name">国花瓷西鳳酒</h3>
                                <p class="product-item__price">¥ 8,800<span>(税込)</span></p>
                                <p class="product-item__tag">#白酒 #鳳香型</p>
                            </a>
                        </div>
                    </div>
                    <!-- If we need pagination -->
                    <div class="swiper-pagination"></div>
                </div>
                <a href="products_list.php?category=chinese_sake" class="btn-all-products">中国酒一覧を見る</a>
            </div>
        </section>
    </main>

    <?php require_once 'footer.php'; ?>

    <script src="https://unpkg.com/swiper@8/swiper-bundle.min.js"></script>
    <script src="js/script.js"></script>
    <script src="js/guide.js"></script>
</body>

</html>
