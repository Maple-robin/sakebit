<?php
// ヘッダーでセッション開始、DB接続、共通関数の読み込みを行っています。
?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>焼酎ガイド | OUR BRAND</title>
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
        <section class="guide-hero" style="background-image: url('img/shochu_barrel.png');">
            <div class="guide-hero__inner">
                <h2 class="guide-hero__title">焼酎の世界へようこそ</h2>
                <p class="guide-hero__subtitle">伝統と革新の味わい</p>
            </div>
        </section>

        <!-- 焼酎って何？ -->
        <section class="guide-section intro-section">
            <div class="section-inner">
                <div class="section-title">
                    <h2 class="ja">焼酎ってなんだろう？</h2>
                    <p class="en">What is Shochu?</p>
                </div>
                <div class="intro-content">
                    <div class="intro-content__image-container">
                        <img src="img/shochu_bottle.png" alt="焼酎の紹介画像" class="intro-content__image">
                        <div class="intro-content__overlay">
                            <p>焼酎は、米、麦、芋、そばなど様々な原料を蒸留して造られる日本の伝統的なお酒です。原料由来の豊かな風味とクリアな味わいが特徴で、多様な飲み方で楽しまれています。</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- 初心者におすすめの種類 -->
        <section class="guide-section beginner-types">
            <div class="section-inner">
                <div class="section-title">
                    <h2 class="ja">初心者におすすめの種類</h2>
                    <p class="en">Easy to Drink</p>
                </div>
                <div class="alcohol-types">
                    <div class="type-card">
                        <h4>麦焼酎</h4>
                        <p>麦を原料とし、軽やかで香ばしい風味が特徴。クセが少なく、すっきりとした味わいで飲みやすいです。</p>
                    </div>
                    <div class="type-card type-card--bg" style="background-image: url('img/shochu_glass.png');">
                        <h4>米焼酎</h4>
                        <p>日本酒のような吟醸香と、まろやかで上品な甘みが特徴。フルーティーで飲みやすいものも多いです。</p>
                    </div>
                    <div class="type-card">
                        <h4>芋焼酎</h4>
                        <p>さつまいもを原料とし、独特の甘く華やかな香りが魅力。近年は飲みやすく改良されたものも増えています。</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- おいしい飲み方を見つけよう -->
        <section class="guide-section how-to-drink">
            <div class="section-inner">
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
                                <p>焼酎の定番の飲み方。焼酎と水の割合を変えることで、好みの濃さに調整できます。一般的には焼酎6：水4がおすすめです。</p>
                            </div>
                            <div class="drink-way-item__image">
                                <img src="img/mizuwari.png" alt="水割り">
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
                                <p>焼酎の香りが引き立ち、体が温まる飲み方。お湯を先に注ぎ、後から焼酎を注ぐと対流が起きて自然に混ざります。</p>
                            </div>
                            <div class="drink-way-item__image">
                                <img src="img/oyuwari.png" alt="お湯割り">
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
                                <p>氷で冷やすことで、口当たりがまろやかになります。焼酎本来の味わいをダイレクトに楽しみたい方におすすめです。</p>
                            </div>
                            <div class="drink-way-item__image">
                                <img src="img/rock_glass.png" alt="ロック">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- おすすめ焼酎カルーセル -->
        <section class="guide-section recommended-sake">
            <div class="section-inner">
                <div class="section-title">
                    <h2 class="ja">おすすめの焼酎</h2>
                    <p class="en">Recommended Shochu</p>
                </div>
                <div class="swiper recommended-sake-swiper">
                    <div class="swiper-wrapper">
                        <!-- 商品1 -->
                        <div class="swiper-slide product-item">
                            <a href="product.php?id=401">
                                <div class="product-item__img-wrap">
                                    <img src="img/iichiko.png" alt="いいちこ">
                                </div>
                                <h3 class="product-item__name">いいちこ 25度</h3>
                                <p class="product-item__price">¥ 1,200<span>(税込)</span></p>
                                <p class="product-item__tag">#麦焼酎 #すっきり</p>
                            </a>
                        </div>
                        <!-- 商品2 -->
                        <div class="swiper-slide product-item">
                            <a href="product.php?id=402">
                                <div class="product-item__img-wrap">
                                    <img src="img/kurokirishima.png" alt="黒霧島">
                                </div>
                                <h3 class="product-item__name">黒霧島 25度</h3>
                                <p class="product-item__price">¥ 1,300<span>(税込)</span></p>
                                <p class="product-item__tag">#芋焼酎 #トロッとキリッと</p>
                            </a>
                        </div>
                        <!-- 商品3 -->
                        <div class="swiper-slide product-item">
                            <a href="product.php?id=403">
                                <div class="product-item__img-wrap">
                                    <img src="img/shiro.png" alt="白岳しろ">
                                </div>
                                <h3 class="product-item__name">白岳しろ</h3>
                                <p class="product-item__price">¥ 1,400<span>(税込)</span></p>
                                <p class="product-item__tag">#米焼酎 #上品な香り</p>
                            </a>
                        </div>
                        <!-- 商品4 -->
                        <div class="swiper-slide product-item">
                            <a href="product.php?id=404">
                                <div class="product-item__img-wrap">
                                    <img src="img/murao.png" alt="村尾">
                                </div>
                                <h3 class="product-item__name">村尾</h3>
                                <p class="product-item__price">¥ 15,000<span>(税込)</span></p>
                                <p class="product-item__tag">#芋焼酎 #プレミアム</p>
                            </a>
                        </div>
                        <!-- 商品5 -->
                        <div class="swiper-slide product-item">
                            <a href="product.php?id=405">
                                <div class="product-item__img-wrap">
                                    <img src="img/maou.png" alt="魔王">
                                </div>
                                <h3 class="product-item__name">魔王</h3>
                                <p class="product-item__price">¥ 18,000<span>(税込)</span></p>
                                <p class="product-item__tag">#芋焼酎 #熟成</p>
                            </a>
                        </div>
                    </div>
                    <!-- If we need pagination -->
                    <div class="swiper-pagination"></div>
                </div>
                 <a href="products_list.php?category=shochu" class="btn-all-products">焼酎一覧を見る</a>
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
