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
        <!-- ヒーローセクション -->
        <section class="guide-hero" style="background-image: url('img/缶チューハイいっぱい.png');">
            <div class="guide-hero__inner">
                <h2 class="guide-hero__title">缶チューハイの世界へようこそ</h2>
                <p class="guide-hero__subtitle">手軽に楽しむ、無限のフレーバー</p>
            </div>
        </section>

        <!-- 缶チューハイって何？ -->
        <section class="guide-section intro-section">
            <div class="section-inner">
                <div class="section-title">
                    <h2 class="ja">缶チューハイってなんだろう？</h2>
                    <p class="en">What is Canned Chuhai?</p>
                </div>
                <div class="intro-content">
                    <div class="intro-content__image-container">
                        <img src="img/氷缶チューハイ.png" alt="缶チューハイの紹介画像" class="intro-content__image">
                        <div class="intro-content__overlay">
                            <p>缶チューハイは、焼酎やウォッカなどのスピリッツをベースに、果汁や炭酸水、フレーバーなどを加えて作られた、すぐに飲めるタイプのアルコール飲料です。手軽さと種類の豊富さから、幅広い層に人気があります。</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- 初心者におすすめの種類 -->
        <section class="guide-section beginner-types">
            <div class="section-inner">
                <div class="section-title">
                    <h2 class="ja">主な種類</h2>
                    <p class="en">Major Types</p>
                </div>
                <div class="alcohol-types">
                    <div class="type-card type-card--bg" style="background-image: url('img/檸檬サワー.png');">
                        <h4>レモンサワー</h4>
                        <p>定番の味で、甘いものから甘くないものまで種類が豊富です。食事にも合わせやすく、迷ったらこれを選ぶのがおすすめです。</p>
                    </div>
                    <div class="type-card type-card--bg" style="background-image: url('img/お茶ハイ.png');">
                        <h4>お茶系</h4>
                        <p>お茶の爽やかな香りとすっきりした甘くない味わい。食事のお供にぴったりです。</p>
                    </div>
                    <div class="type-card type-card--bg" style="background-image: url('img/ストゼロ.png');">
                        <h4>ストロング系</h4>
                        <p>甘さがなく、アルコール度数が高めです。キリッとした飲みごたえがあり、甘くない味を好む方におすすめです。</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- おいしい飲み方を見つけよう -->
        <section class="how-to-drink-section section-inner">
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
        </section>

        <!-- おすすめ缶チューハイカルーセル -->
        <section class="guide-section recommended-sake">
            <div class="section-inner">
                <div class="section-title">
                    <h2 class="ja">おすすめの缶チューハイ</h2>
                    <p class="en">Recommended Canned Chuhai</p>
                </div>
                <div class="swiper recommended-sake-swiper">
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
                <a href="products_list.php?category=chuhai" class="btn-all-products">缶チューハイ一覧を見る</a>
            </div>
        </section>
    </main>

    <?php require_once 'footer.php'; ?>

    <script src="https://unpkg.com/swiper@8/swiper-bundle.min.js"></script>
    <script src="js/script.js"></script>
    <script src="js/guide.js"></script>
</body>

</html>
