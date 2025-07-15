<?php
// ヘッダーでセッション開始、DB接続、共通関数の読み込みを行っています。
?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>スピリッツガイド - SAKEBIT</title>
    <link rel="stylesheet" href="https://unpkg.com/swiper@8/swiper-bundle.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="css/top.css">
    <link rel="stylesheet" href="css/guide.css">
</head>

<body>
    <?php require_once 'header.php'; ?>

    <main>
        <!-- ヒーローセクション -->
        <section class="guide-hero" style="background-image: url('img/スピリッツ.png');">
            <div class="guide-hero__inner">
                <h2 class="guide-hero__title">スピリッツの世界へようこそ</h2>
                <p class="guide-hero__subtitle">カクテルの無限の可能性</p>
            </div>
        </section>

        <!-- スピリッツって何？ -->
        <section class="guide-section guide-intro-section">
            <div class="section-inner">
                <div class="section-title">
                    <h2 class="ja">スピリッツってなんだろう？</h2>
                    <p class="en">What is Spirits?</p>
                </div>
                <div class="intro-content">
                    <div class="intro-content__image-container">
                        <img src="img/スピリッツとは.png" alt="スピリッツの紹介画像" class="intro-content__image">
                        <div class="intro-content__overlay">
                            <p>スピリッツとは、蒸留によって造られるアルコール度数の高いお酒の総称です。ジン、ウォッカ、ラム、テキーラなどが代表的で、それぞれが独自の原料と製法を持ち、カクテルのベースとして広く使われています。</p>
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
                    <div class="type-card type-card--bg" style="background-image: url('img/ジン.png');">
                        <h4>ジン</h4>
                        <p>ジュニパーベリーの香りが特徴。ジントニックが有名で、ボタニカルの複雑な香りを楽しめます。</p>
                    </div>
                    <div class="type-card type-card--bg" style="background-image: url('img/ウォッカ.png');">
                        <h4>ウォッカ</h4>
                        <p>クリアでクセのない味わいが特徴。様々なジュースやリキュールと相性が良く、カクテルの幅を広げます。</p>
                    </div>
                    <div class="type-card type-card--bg" style="background-image: url('img/ラム.png');">
                        <h4>ラム</h4>
                        <p>サトウキビを原料とし、甘い香りとコクが特徴。コーラで割るキューバリブレや、ミントと合わせるモヒートが人気です。</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- おいしい飲み方を見つけよう -->
        <section class="how-to-drink-section section-inner">
            <div class="section-title">
                <h2 class="ja">おすすめの飲み方</h2>
                <p class="en">HOW TO DRINK</p>
            </div>

            <div class="drink-ways">
                <!-- カシスオレンジ -->
                <div class="drink-way-item">
                    <div class="drink-way-item__title">
                        <h3>カシスオレンジ</h3>
                    </div>
                    <div class="drink-way-item__step">
                        <div class="drink-way-item__description">
                            <p>カシスリキュールをオレンジジュースで割り、フルーティーでジュースのように甘く飲みやすいカクテルです。</p>
                        </div>
                        <div class="drink-way-item__image">
                            <img src="img/cassis_orange.png" alt="カシスオレンジ">
                        </div>
                    </div>
                </div>

                <hr>

                <!-- ジントニック -->
                <div class="drink-way-item">
                    <div class="drink-way-item__title">
                        <h3>ジントニック</h3>
                    </div>
                    <div class="drink-way-item__step">
                        <div class="drink-way-item__description">
                            <p>ジンをトニックウォーターで割り、爽やかな香りとすっきりとしたほろ苦さが楽しめる定番カクテルです。</p>
                        </div>
                        <div class="drink-way-item__image">
                            <img src="img/gin_tonic.png" alt="ジントニック">
                        </div>
                    </div>
                </div>

                <hr>

                <!-- スクリュードライバー -->
                <div class="drink-way-item">
                    <div class="drink-way-item__title">
                        <h3>スクリュードライバー</h3>
                    </div>
                    <div class="drink-way-item__step">
                        <div class="drink-way-item__description">
                            <p>ウォッカをオレンジジュースで割り、オレンジの甘みでアルコール感をほとんど感じさせない飲みやすいカクテルです。</p>
                        </div>
                        <div class="drink-way-item__image">
                            <img src="img/screwdriver.png" alt="スクリュードライバー">
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- おすすめスピリッツカルーセル -->
        <section class="guide-section recommended-sake">
            <div class="section-inner">
                <div class="section-title">
                    <h2 class="ja">おすすめのスピリッツ</h2>
                    <p class="en">Recommended Spirits</p>
                </div>
                <div class="swiper recommended-sake-swiper">
                    <div class="swiper-wrapper">
                        <!-- 商品1 -->
                        <div class="swiper-slide product-item">
                            <a href="product.php?id=601">
                                <div class="product-item__img-wrap">
                                    <img src="img/ボンベイ・サファイア.png" alt="ボンベイ・サファイア">
                                </div>
                                <h3 class="product-item__name">ボンベイ・サファイア</h3>
                                <p class="product-item__price">¥ 3,000<span>(税込)</span></p>
                                <p class="product-item__tag">#ジン #ボタニカル</p>
                            </a>
                        </div>
                        <!-- 商品2 -->
                        <div class="swiper-slide product-item">
                            <a href="product.php?id=602">
                                <div class="product-item__img-wrap">
                                    <img src="img/smirnoff.png" alt="スミノフ">
                                </div>
                                <h3 class="product-item__name">スミノフ ウォッカ</h3>
                                <p class="product-item__price">¥ 1,500<span>(税込)</span></p>
                                <p class="product-item__tag">#ウォッカ #クリア</p>
                            </a>
                        </div>
                        <!-- 商品3 -->
                        <div class="swiper-slide product-item">
                            <a href="product.php?id=603">
                                <div class="product-item__img-wrap">
                                    <img src="img/bacardi.png" alt="バカルディ">
                                </div>
                                <h3 class="product-item__name">バカルディ スペリオール</h3>
                                <p class="product-item__price">¥ 1,800<span>(税込)</span></p>
                                <p class="product-item__tag">#ラム #ライト</p>
                            </a>
                        </div>
                        <!-- 商品4 -->
                        <div class="swiper-slide product-item">
                            <a href="product.php?id=604">
                                <div class="product-item__img-wrap">
                                    <img src="img/cuervo.png" alt="クエルボ">
                                </div>
                                <h3 class="product-item__name">ホセ・クエルボ・エスペシャル</h3>
                                <p class="product-item__price">¥ 2,200<span>(税込)</span></p>
                                <p class="product-item__tag">#テキーラ #ゴールド</p>
                            </a>
                        </div>
                        <!-- 商品5 -->
                        <div class="swiper-slide product-item">
                            <a href="product.php?id=605">
                                <div class="product-item__img-wrap">
                                    <img src="img/tanqueray.png" alt="タンカレー">
                                </div>
                                <h3 class="product-item__name">タンカレー ロンドン ドライジン</h3>
                                <p class="product-item__price">¥ 2,500<span>(税込)</span></p>
                                <p class="product-item__tag">#ジン #ドライ</p>
                            </a>
                        </div>
                    </div>
                    <!-- If we need pagination -->
                    <div class="swiper-pagination"></div>
                </div>
                 <a href="products_list.php?category=spirits" class="btn-all-products">スピリッツ一覧を見る</a>
            </div>
        </section>

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
                            <img src="img/sake.png" alt="日本酒ガイド" class="category-card__img">
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
                            <img src="img/chuhai.png" alt="缶チューハイガイド" class="category-card__img">
                            <h3 class="category-card__name">缶チューハイ</h3>
                        </a>
                    </li>
                    <li class="category-list__item">
                        <a href="guide_shochu.php" class="category-card">
                            <img src="img/shochu.png" alt="焼酎ガイド" class="category-card__img">
                            <h3 class="category-card__name">焼酎</h3>
                        </a>
                    </li>
                    <li class="category-list__item">
                        <a href="guide_whisky.php" class="category-card">
                            <img src="img/whisky.png" alt="ウィスキーガイド" class="category-card__img">
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
                            <img src="img/liqueur.png" alt="リキュールガイド" class="category-card__img">
                            <h3 class="category-card__name">リキュール</h3>
                        </a>
                    </li>
                    <li class="category-list__item">
                        <a href="guide_wine.php" class="category-card">
                            <img src="img/wine.png" alt="ワインガイド" class="category-card__img">
                            <h3 class="category-card__name">ワイン</h3>
                        </a>
                    </li>
                    <li class="category-list__item">
                        <a href="guide_beer.php" class="category-card">
                            <img src="img/beer.png" alt="ビールガイド" class="category-card__img">
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
