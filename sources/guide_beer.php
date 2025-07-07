<?php
// ヘッダーでセッション開始、DB接続、共通関数の読み込みを行っています。
?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ビールガイド | SAKEBITO</title>
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
        <section class="guide-hero" style="background-image: url('img/beer.png');">
            <div class="guide-hero__inner">
                <h2 class="guide-hero__title">ビールの世界へようこそ</h2>
                <p class="guide-hero__subtitle">クラフトの探求と楽しみ</p>
            </div>
        </section>

        <!-- ビールって何？ -->
        <section class="guide-section intro-section">
            <div class="section-inner">
                <div class="section-title">
                    <h2 class="ja">ビールってなんだろう？</h2>
                    <p class="en">What is Beer?</p>
                </div>
                <div class="intro-content">
                    <div class="intro-content__image-container">
                        <img src="img/craft_beer.png" alt="ビールの紹介画像" class="intro-content__image">
                        <div class="intro-content__overlay">
                            <p>ビールは、主に大麦の麦芽を酵母で発酵させて造られるアルコール飲料です。ホップを加えることで、特有の苦味と香りが生まれます。世界中で最も古くから親しまれているお酒の一つです。</p>
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
                    <div class="type-card type-card--bg" style="background-image: url('img/beer_pilsner.png');">
                        <h4>ピルスナー</h4>
                        <p>世界で最も普及しているスタイル。すっきりとしたのどごしと爽やかなホップの苦みが特徴です。</p>
                    </div>
                    <div class="type-card type-card--bg" style="background-image: url('img/beer_glass.png');">
                        <h4>ヴァイツェン</h4>
                        <p>小麦を主原料とした、フルーティーでバナナのような香りが特徴の白ビール。苦みが少なくまろやかです。</p>
                    </div>
                    <div class="type-card type-card--bg" style="background-image: url('img/beer_ale.png');">
                        <h4>ペールエール</h4>
                        <p>豊かなホップの香りと程よい苦みが特徴。クラフトビールの入門としても人気です。</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- おいしい飲み方を見つけよう -->
        <section class="how-to-drink-section section-inner">
            <div class="section-title">
                <h2 class="ja">おいしい注ぎ方を見つけよう</h2>
                <p class="en">HOW TO POUR</p>
            </div>

            <div class="drink-ways">
                <!-- 三度注ぎ -->
                <div class="drink-way-item">
                    <div class="drink-way-item__title">
                        <h3>三度注ぎ</h3>
                    </div>
                    <div class="drink-way-item__step">
                        <div class="drink-way-item__description">
                            <p>一度目は勢いよく注いで泡を作り、二度目はゆっくり注いで液体を増やし、三度目で泡を整える方法。ビールの炭酸と風味のバランスが絶妙になります。</p>
                        </div>
                        <div class="drink-way-item__image">
                            <img src="img/beer_bottle.png" alt="三度注ぎのイメージ">
                        </div>
                    </div>
                </div>

                <hr>

                <!-- グラス選び -->
                <div class="drink-way-item">
                    <div class="drink-way-item__title">
                        <h3>グラス選び</h3>
                    </div>
                    <div class="drink-way-item__step">
                        <div class="drink-way-item__description">
                            <p>ビールのスタイルに合わせてグラスを選ぶと、香りや味わいが一層引き立ちます。例えば、ピルスナーには細長いグラス、エールには丸みのあるグラスがおすすめです。</p>
                        </div>
                        <div class="drink-way-item__image">
                            <img src="img/beer_collection.png" alt="様々なビアグラス">
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- おすすめビールカルーセル -->
        <section class="guide-section recommended-sake">
            <div class="section-inner">
                <div class="section-title">
                    <h2 class="ja">おすすめのビール</h2>
                    <p class="en">Recommended Beer</p>
                </div>
                <div class="swiper recommended-sake-swiper">
                    <div class="swiper-wrapper">
                        <!-- 商品1 -->
                        <div class="swiper-slide product-item">
                            <a href="product.php?id=201">
                                <div class="product-item__img-wrap">
                                    <img src="img/yebisu.png" alt="ヱビスビール">
                                </div>
                                <h3 class="product-item__name">ヱビスビール</h3>
                                <p class="product-item__price">¥ 350<span>(税込)</span></p>
                                <p class="product-item__tag">#プレミアム #コク</p>
                            </a>
                        </div>
                        <!-- 商品2 -->
                        <div class="swiper-slide product-item">
                            <a href="product.php?id=202">
                                <div class="product-item__img-wrap">
                                    <img src="img/premium_malts.png" alt="ザ・プレミアム・モルツ">
                                </div>
                                <h3 class="product-item__name">ザ・プレミアム・モルツ</h3>
                                <p class="product-item__price">¥ 340<span>(税込)</span></p>
                                <p class="product-item__tag">#華やかな香り #深いコク</p>
                            </a>
                        </div>
                        <!-- 商品3 -->
                        <div class="swiper-slide product-item">
                            <a href="product.php?id=203">
                                <div class="product-item__img-wrap">
                                    <img src="img/indonoaooni.png" alt="インドの青鬼">
                                </div>
                                <h3 class="product-item__name">インドの青鬼</h3>
                                <p class="product-item__price">¥ 450<span>(税込)</span></p>
                                <p class="product-item__tag">#IPA #強烈な苦み</p>
                            </a>
                        </div>
                        <!-- 商品4 -->
                        <div class="swiper-slide product-item">
                            <a href="product.php?id=204">
                                <div class="product-item__img-wrap">
                                    <img src="img/yonayona_ale.png" alt="よなよなエール">
                                </div>
                                <h3 class="product-item__name">よなよなエール</h3>
                                <p class="product-item__price">¥ 420<span>(税込)</span></p>
                                <p class="product-item__tag">#ペールエール #柑橘香</p>
                            </a>
                        </div>
                        <!-- 商品5 -->
                        <div class="swiper-slide product-item">
                            <a href="product.php?id=205">
                                <div class="product-item__img-wrap">
                                    <img src="img/orion.png" alt="オリオンビール">
                                </div>
                                <h3 class="product-item__name">オリオン ザ・ドラフト</h3>
                                <p class="product-item__price">¥ 300<span>(税込)</span></p>
                                <p class="product-item__tag">#沖縄 #爽快</p>
                            </a>
                        </div>
                    </div>
                    <!-- If we need pagination -->
                    <div class="swiper-pagination"></div>
                </div>
                 <a href="products_list.php?category=ビール" class="btn-all-products">ビール一覧を見る</a>
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
