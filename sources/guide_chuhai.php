<?php
// ヘッダーでセッション開始、DB接続、共通関数の読み込みを行っています。
?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>缶チューハイガイド - SAKEBIT</title>
    <link rel="stylesheet" href="https://unpkg.com/swiper@8/swiper-bundle.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="css/top.css">
    <link rel="stylesheet" href="css/guide.css">
</head>

<body>
    <?php require_once 'header.php'; ?>

    <main>
        <div class="container">
            <!-- ヒーローセクション -->
            <section class="sake-hero" style="background-image: url('img/kanchuhai_bg.png');">
                <div class="sake-hero__inner">
                    <h2 class="sake-hero__title">缶チューハイの世界へようこそ</h2>
                    <p class="sake-hero__subtitle">手軽に楽しむ、無限のフレーバー</p>
                </div>
            </section>

            <!-- 缶チューハイって何？ -->
            <section class="sake-section sake-intro-section">
                <div class="section-inner">
                    <div class="section-title">
                        <h2 class="ja">缶チューハイってなんだろう？</h2>
                        <p class="en">What is Canned Chuhai?</p>
                    </div>
                    <div class="sake-intro-content">
                        <div class="sake-intro-content__image-container">
                            <img src="img/chuhai_can.png" alt="缶チューハイの紹介画像" class="sake-intro-content__image">
                            <div class="sake-intro-content__overlay">
                                <p>缶チューハイは、焼酎やウォッカなどのスピリッツをベースに、果汁や炭酸水、フレーバーなどを加えて作られた、すぐに飲めるタイプのアルコール飲料です。手軽さと種類の豊富さから、幅広い層に人気があります。</p>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- 初心者におすすめの種類 -->
            <section class="sake-section sake-beginner-types">
                <div class="section-inner">
                    <div class="section-title">
                        <h2 class="ja">人気のフレーバー</h2>
                        <p class="en">Popular Flavors</p>
                    </div>
                    <div class="sake-types">
                        <div class="sake-type-card">
                            <h4>レモンサワー</h4>
                            <p>定番中の定番。甘さ控えめのドライなものから、はちみつレモンのような甘いものまで様々です。</p>
                        </div>
                        <div class="sake-type-card sake-type-card--bg" style="background-image: url('img/chuhai_glass.png');">
                            <h4>グレープフルーツ</h4>
                            <p>爽やかな酸味とほろ苦さが特徴。食事にも合わせやすく、さっぱりと飲みたいときにおすすめです。</p>
                        </div>
                        <div class="sake-type-card">
                            <h4>ピーチ・ブドウ</h4>
                            <p>果物の甘みをしっかりと感じられるフレーバー。ジュース感覚で飲めるため、女性にも人気です。</p>
                        </div>
                    </div>
                </div>
            </section>

            <!-- おいしい飲み方を見つけよう -->
            <section class="sake-section sake-how-to-drink">
                <div class="section-inner">
                    <div class="section-title">
                        <h2 class="ja">もっとおいしく楽しむ</h2>
                        <p class="en">ENJOY MORE</p>
                    </div>

                    <div class="drink-ways">
                        <!-- グラスに注ぐ -->
                        <div class="drink-way-item">
                            <div class="drink-way-item__title">
                                <h3>グラスに注いで飲む</h3>
                            </div>
                            <div class="drink-way-item__step">
                                <div class="drink-way-item__description">
                                    <p>缶のまま飲むのも手軽で良いですが、氷を入れたグラスに注ぐと、炭酸の泡が立ち上り、香りも楽しめます。冷たさもキープできます。</p>
                                </div>
                                <div class="drink-way-item__image">
                                    <img src="img/lemon_sour.png" alt="グラスに注いだレモンサワー">
                                </div>
                            </div>
                        </div>

                        <hr>

                        <!-- フルーツをプラス -->
                        <div class="drink-way-item">
                            <div class="drink-way-item__title">
                                <h3>フルーツをプラス</h3>
                            </div>
                            <div class="drink-way-item__step">
                                <div class="drink-way-item__description">
                                    <p>レモンサワーにカットレモン、ピーチ味に冷凍ピーチを入れるなど、生のフルーツを加えると、見た目も華やかになり、フレッシュな風味がアップします。</p>
                                </div>
                                <div class="drink-way-item__image">
                                    <img src="img/grape_sour.png" alt="フルーツを入れたチューハイ">
                                </div>
                            </div>
                        </div>

                        <hr>

                        <!-- アレンジレシピ -->
                        <div class="drink-way-item">
                            <div class="drink-way-item__title">
                                <h3>アレンジレシピ</h3>
                            </div>
                            <div class="drink-way-item__step">
                                <div class="drink-way-item__description">
                                    <p>カルピスサワーにアイスの実を入れたり、塩をひとつまみ加えてソルティテイストにしたりと、簡単なアレンジで新しいおいしさが発見できます。</p>
                                </div>
                                <div class="drink-way-item__image">
                                    <img src="img/calpis_sour.png" alt="アレンジチューハイ">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- おすすめ缶チューハイカルーセル -->
            <section class="sake-section recommended-sake">
                <div class="section-inner">
                    <div class="section-title">
                        <h2 class="ja">おすすめの缶チューハイ</h2>
                        <p class="en">Recommended Canned Chuhai</p>
                    </div>
                    <div class="swiper sake-swiper">
                        <div class="swiper-wrapper">
                            <!-- 商品1 -->
                            <div class="swiper-slide product-item">
                                <a href="product.php?id=801">
                                    <div class="product-item__img-wrap">
                                        <img src="img/hyoketsu.png" alt="氷結シチリア産レモン">
                                    </div>
                                    <h3 class="product-item__name">キリン 氷結® シチリア産レモン</h3>
                                    <p class="product-item__price">¥ 150<span>(税込)</span></p>
                                    <p class="product-item__tag">#定番 #レモン</p>
                                </a>
                            </div>
                            <!-- 商品2 -->
                            <div class="swiper-slide product-item">
                                <a href="product.php?id=802">
                                    <div class="product-item__img-wrap">
                                        <img src="img/strong_zero.png" alt="-196℃ ストロングゼロ">
                                    </div>
                                    <h3 class="product-item__name">-196℃ ストロングゼロ〈ダブルレモン〉</h3>
                                    <p class="product-item__price">¥ 160<span>(税込)</span></p>
                                    <p class="product-item__tag">#高アルコール #ガツン</p>
                                </a>
                            </div>
                            <!-- 商品3 -->
                            <div class="swiper-slide product-item">
                                <a href="product.php?id=803">
                                    <div class="product-item__img-wrap">
                                        <img src="img/horoyoi.png" alt="ほろよい 白いサワー">
                                    </div>
                                    <h3 class="product-item__name">ほろよい〈白いサワー〉</h3>
                                    <p class="product-item__price">¥ 140<span>(税込)</span></p>
                                    <p class="product-item__tag">#低アルコール #やさしい</p>
                                </a>
                            </div>
                            <!-- 商品4 -->
                            <div class="swiper-slide product-item">
                                <a href="product.php?id=804">
                                    <div class="product-item__img-wrap">
                                        <img src="img/kodawari_sakaba.png" alt="こだわり酒場のレモンサワー">
                                    </div>
                                    <h3 class="product-item__name">こだわり酒場のレモンサワー</h3>
                                    <p class="product-item__price">¥ 155<span>(税込)</span></p>
                                    <p class="product-item__tag">#レモンサワー #本格</p>
                                </a>
                            </div>
                            <!-- 商品5 -->
                            <div class="swiper-slide product-item">
                                <a href="product.php?id=805">
                                    <div class="product-item__img-wrap">
                                        <img src="img/kanjyuku_ume.png" alt="贅沢搾り 完熟うめ">
                                    </div>
                                    <h3 class="product-item__name">アサヒ 贅沢搾りプレミアム 完熟うめ</h3>
                                    <p class="product-item__price">¥ 170<span>(税込)</span></p>
                                    <p class="product-item__tag">#梅 #果実感</p>
                                </a>
                            </div>
                        </div>
                        <!-- If we need pagination -->
                        <div class="swiper-pagination"></div>
                    </div>
                    <a href="products_list.php?category=chuhai" class="btn-view-all">缶チューハイ一覧を見る</a>
                </div>
            </section>
        </div>
    </main>

    <?php require_once 'footer.php'; ?>

    <script src="https://unpkg.com/swiper@8/swiper-bundle.min.js"></script>
    <script src="js/script.js"></script>
    <script src="js/guide.js"></script>
</body>

</html>
