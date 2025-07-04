<?php
// ヘッダーでセッション開始、DB接続、共通関数の読み込みを行っています。
?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ワインガイド | SAKEBITO</title>
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
        <section class="guide-hero" style="background-image: url('img/wine_barrel.png');">
            <div class="guide-hero__inner">
                <h2 class="guide-hero__title">ワインの世界へようこそ</h2>
                <p class="guide-hero__subtitle">ブドウ畑からの物語</p>
            </div>
        </section>

        <!-- ワインって何？ -->
        <section class="guide-section intro-section">
            <div class="section-inner">
                <div class="section-title">
                    <h2 class="ja">ワインってなんだろう？</h2>
                    <p class="en">What is Wine?</p>
                </div>
                <div class="intro-content">
                    <div class="intro-content__image-container">
                        <img src="img/wine_bottle.png" alt="ワインの紹介画像" class="intro-content__image">
                        <div class="intro-content__overlay">
                            <p>ワインは、主にブドウの果汁を発酵させて造られるアルコール飲料です。ブドウの品種、産地、製造方法によって、味わい、色、香りが大きく異なります。赤、白、ロゼなど様々な種類があります。</p>
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
                        <h4>ソーヴィニヨン・ブラン</h4>
                        <p>爽やかな柑橘系の香りと軽快な酸味が特徴の白ワイン。シーフードなどと相性抜群です。</p>
                    </div>
                    <div class="type-card type-card--bg" style="background-image: url('img/wine_glass.png');">
                        <h4>メルロー</h4>
                        <p>渋みが少なく、果実味豊かでまろやかな口当たりの赤ワイン。肉料理全般によく合います。</p>
                    </div>
                    <div class="type-card">
                        <h4>プロセッコ</h4>
                        <p>イタリア産のスパークリングワイン。フルーティーで飲みやすく、お祝いの席にもぴったりです。</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- おいしい飲み方を見つけよう -->
        <section class="how-to-drink-section section-inner">
            <div class="section-title">
                <h2 class="ja">おいしいペアリングを見つけよう</h2>
                <p class="en">HOW TO PAIRING</p>
            </div>

            <div class="drink-ways">
                <!-- 赤ワインと料理 -->
                <div class="drink-way-item">
                    <div class="drink-way-item__title">
                        <h3>赤ワインと料理</h3>
                    </div>
                    <div class="drink-way-item__step">
                        <div class="drink-way-item__description">
                            <p>フルボディの赤ワインにはステーキなどの濃厚な肉料理、ライトボディならトマトソースのパスタなどがおすすめです。</p>
                        </div>
                        <div class="drink-way-item__image">
                            <img src="img/red_wine.png" alt="赤ワインと料理">
                        </div>
                    </div>
                </div>

                <hr>

                <!-- 白ワインと料理 -->
                <div class="drink-way-item">
                    <div class="drink-way-item__title">
                        <h3>白ワインと料理</h3>
                    </div>
                    <div class="drink-way-item__step">
                        <div class="drink-way-item__description">
                            <p>辛口の白ワインは魚介類や鶏肉料理、甘口ならデザートやフルーツとよく合います。特に牡蠣とシャブリの組み合わせは有名です。</p>
                        </div>
                        <div class="drink-way-item__image">
                            <img src="img/white_wine.png" alt="白ワインと料理">
                        </div>
                    </div>
                </div>

                <hr>

                <!-- チーズとのペアリング -->
                <div class="drink-way-item">
                    <div class="drink-way-item__title">
                        <h3>チーズとのペアリング</h3>
                    </div>
                    <div class="drink-way-item__step">
                        <div class="drink-way-item__description">
                            <p>ワインとチーズは最高の組み合わせ。産地を合わせるのが基本です。例えば、フランスのロックフォールには、同じ地方の甘口ワイン「ソーテルヌ」がよく合います。</p>
                        </div>
                        <div class="drink-way-item__image">
                            <img src="img/cheese.png" alt="ワインとチーズ">
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- おすすめワインカルーセル -->
        <section class="guide-section recommended-sake">
            <div class="section-inner">
                <div class="section-title">
                    <h2 class="ja">おすすめのワイン</h2>
                    <p class="en">Recommended Wine</p>
                </div>
                <div class="swiper recommended-sake-swiper">
                    <div class="swiper-wrapper">
                        <!-- 商品1 -->
                        <div class="swiper-slide product-item">
                            <a href="product.php?id=301">
                                <div class="product-item__img-wrap">
                                    <img src="img/wine_red_bottle.png" alt="カサーレヴェッキオ">
                                </div>
                                <h3 class="product-item__name">カサーレ・ヴェッキオ モンテプルチアーノ</h3>
                                <p class="product-item__price">¥ 2,500<span>(税込)</span></p>
                                <p class="product-item__tag">#赤ワイン #フルボディ</p>
                            </a>
                        </div>
                        <!-- 商品2 -->
                        <div class="swiper-slide product-item">
                            <a href="product.php?id=302">
                                <div class="product-item__img-wrap">
                                    <img src="img/wine_white_bottle.png" alt="クラウディベイ">
                                </div>
                                <h3 class="product-item__name">クラウディ・ベイ ソーヴィニヨン・ブラン</h3>
                                <p class="product-item__price">¥ 4,800<span>(税込)</span></p>
                                <p class="product-item__tag">#白ワイン #爽やか</p>
                            </a>
                        </div>
                        <!-- 商品3 -->
                        <div class="swiper-slide product-item">
                            <a href="product.php?id=303">
                                <div class="product-item__img-wrap">
                                    <img src="img/wine_sparkling_bottle.png" alt="モエ・エ・シャンドン">
                                </div>
                                <h3 class="product-item__name">モエ・エ・シャンドン ブリュット</h3>
                                <p class="product-item__price">¥ 7,500<span>(税込)</span></p>
                                <p class="product-item__tag">#シャンパン #辛口</p>
                            </a>
                        </div>
                        <!-- 商品4 -->
                        <div class="swiper-slide product-item">
                            <a href="product.php?id=304">
                                <div class="product-item__img-wrap">
                                    <img src="img/wine_rose_bottle.png" alt="ミラヴァル・ロゼ">
                                </div>
                                <h3 class="product-item__name">ミラヴァル・ロゼ</h3>
                                <p class="product-item__price">¥ 4,500<span>(税込)</span></p>
                                <p class="product-item__tag">#ロゼ #プロヴァンス</p>
                            </a>
                        </div>
                        <!-- 商品5 -->
                        <div class="swiper-slide product-item">
                            <a href="product.php?id=305">
                                <div class="product-item__img-wrap">
                                    <img src="img/wine_orange_bottle.png" alt="オレンジワイン">
                                </div>
                                <h3 class="product-item__name">ラディコン ヤコット</h3>
                                <p class="product-item__price">¥ 6,200<span>(税込)</span></p>
                                <p class="product-item__tag">#オレンジワイン #自然派</p>
                            </a>
                        </div>
                    </div>
                    <!-- If we need pagination -->
                    <div class="swiper-pagination"></div>
                </div>
                 <a href="products_list.php?category=ワイン" class="btn-all-products">ワイン一覧を見る</a>
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
