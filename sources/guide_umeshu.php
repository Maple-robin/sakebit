<?php
/*!
@file guide_umeshu.php
@brief 梅酒ガイドページ
@copyright Copyright (c) 2024 Your Name.
*/

// ★注意: DB接続やセッション開始は header.php で行われるため、このファイルでの処理は不要です。

?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>梅酒ガイド - SAKEBIT</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@300;400;500;700&family=Zen+Old+Mincho:wght@400;500;700&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/swiper@8/swiper-bundle.min.css">
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
        <section class="guide-hero" style="background-image: url('img/梅酒梅たくさん.png');">
            <div class="guide-hero__inner">
                <h2 class="guide-hero__title">梅酒の世界へようこそ</h2>
                <p class="guide-hero__subtitle">梅が織りなす、癒やしの甘み</p>
            </div>
        </section>

        <!-- 梅酒って何？ -->
        <section class="guide-section intro-section">
            <div class="section-inner">
                <div class="section-title">
                    <h2 class="ja">梅酒ってなんだろう？</h2>
                    <p class="en">What is Umeshu?</p>
                </div>
                <div class="intro-content">
                    <div class="intro-content__image-container">
                        <img src="img/梅酒コップ.png" alt="梅酒の紹介画像" class="intro-content__image">
                        <div class="intro-content__overlay">
                            <p>梅酒は、青梅を氷砂糖と共に焼酎などの蒸留酒（または醸造酒）に漬け込んで造られる日本の伝統的なリキュールです。梅の爽やかな酸味と、氷砂糖の優しい甘みが調和した、親しみやすい味わいが特徴です。</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- 初心者におすすめの種類 -->
        <section class="guide-section beginner-types">
            <div class="section-inner">
                <div class="section-title">
                    <h2 class="ja">ベースのお酒で選ぶ</h2>
                    <p class="en">Choose by Base Liquor</p>
                </div>
                <div class="alcohol-types">
                    <div class="type-card type-card--bg" style="background-image: url('img/梅酒ホワイトリカー.png');">
                        <h4>ホワイトリカーベース</h4>
                        <p>最も一般的で、すっきりとクリアな味わい。梅本来の風味をストレートに楽しめます。</p>
                    </div>
                    <div class="type-card type-card--bg" style="background-image: url('img/梅酒ブランデー.png');">
                        <h4>ブランデーベース</h4>
                        <p>ブランデーの芳醇な香りとコクが加わり、重厚で贅沢な味わいの梅酒になります。</p>
                    </div>
                    <div class="type-card type-card--bg" style="background-image: url('img/梅酒日本酒.png');">
                        <h4>日本酒ベース</h4>
                        <p>日本酒の米の旨味と梅の酸味が調和し、まろやかで深みのある味わいが楽しめます。</p>
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
                <!-- ソーダ割り -->
                <div class="drink-way-item">
                    <div class="drink-way-item__title">
                        <h3>ソーダ割り</h3>
                    </div>
                    <div class="drink-way-item__step">
                        <div class="drink-way-item__description">
                            <p>梅酒の甘みと酸味がソーダの炭酸で引き立ち、爽快なのどごしに。食前酒にもぴったりです。</p>
                        </div>
                        <div class="drink-way-item__image">
                            <img src="img/umeshu_soda.png" alt="梅酒ソーダ割り">
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
                            <p>氷がゆっくり溶けることで、味わいの変化を楽しめます。梅酒本来の濃厚な味わいをじっくりと楽しみたい方へ。</p>
                        </div>
                        <div class="drink-way-item__image">
                            <img src="img/umeshu_rock.png" alt="梅酒ロック">
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
                            <p>梅の香りがふわりと立ち上り、心も体も温まる飲み方。寝る前のリラックスタイムにもおすすめです。</p>
                        </div>
                        <div class="drink-way-item__image">
                            <img src="img/umeshu_oyuwari.png" alt="梅酒お湯割り">
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- おすすめ梅酒カルーセル -->
        <section class="guide-section recommended-sake">
            <div class="section-inner">
                <div class="section-title">
                    <h2 class="ja">おすすめの梅酒</h2>
                    <p class="en">Recommended Umeshu</p>
                </div>
                <div class="swiper recommended-sake-swiper">
                    <div class="swiper-wrapper">
                        <!-- 商品1 -->
                        <div class="swiper-slide product-item">
                            <a href="product.php?id=701">
                                <div class="product-item__img-wrap">
                                    <img src="img/梅酒原酒_image1.png" alt="特選梅酒うぐいすとまり 鶯とろ">
                                </div>
                                <h3 class="product-item__name">特選梅酒うぐいすとまり 鶯とろ</h3>
                                <p class="product-item__price">¥ 2,900<span>(税込)</span></p>
                                <p class="product-item__tag">#濃厚 #トロリ</p>
                            </a>
                        </div>
                        <!-- 商品2 -->
                        <div class="swiper-slide product-item">
                            <a href="product.php?id=702">
                                <div class="product-item__img-wrap">
                                    <img src="img/苺梅酒2.png" alt="あまおう梅酒">
                                </div>
                                <h3 class="product-item__name">あまおう梅酒 あまおう、はじめました。</h3>
                                <p class="product-item__price">¥ 3,200<span>(税込)</span></p>
                                <p class="product-item__tag">#苺 #デザート</p>
                            </a>
                        </div>
                        <!-- 商品3 -->
                        <div class="swiper-slide product-item">
                            <a href="product.php?id=703">
                                <div class="product-item__img-wrap">
                                    <img src="img/choya.png" alt="チョーヤ梅酒">
                                </div>
                                <h3 class="product-item__name">チョーヤ The CHOYA SINGLE YEAR</h3>
                                <p class="product-item__price">¥ 1,500<span>(税込)</span></p>
                                <p class="product-item__tag">#定番 #紀州南高梅</p>
                            </a>
                        </div>
                        <!-- 商品4 -->
                        <div class="swiper-slide product-item">
                            <a href="product.php?id=704">
                                <div class="product-item__img-wrap">
                                    <img src="img/kokuto_umeshu.png" alt="黒糖梅酒">
                                </div>
                                <h3 class="product-item__name">ヘリオス酒造 黒糖梅酒</h3>
                                <p class="product-item__price">¥ 1,800<span>(税込)</span></p>
                                <p class="product-item__tag">#黒糖 #コク</p>
                            </a>
                        </div>
                        <!-- 商品5 -->
                        <div class="swiper-slide product-item">
                            <a href="product.php?id=705">
                                <div class="product-item__img-wrap">
                                    <img src="img/sake_umeshu.png" alt="日本酒梅酒">
                                </div>
                                <h3 class="product-item__name">八海山の原酒で仕込んだうめ酒</h3>
                                <p class="product-item__price">¥ 2,600<span>(税込)</span></p>
                                <p class="product-item__tag">#日本酒ベース #すっきり</p>
                            </a>
                        </div>
                    </div>
                    <!-- If we need pagination -->
                    <div class="swiper-pagination"></div>
                </div>
                 <a href="products_list.php?category=umeshu" class="btn-all-products">梅酒一覧を見る</a>
            </div>
        </section>
    </main>

    <?php require_once 'footer.php'; ?>

    <script src="https://unpkg.com/swiper@8/swiper-bundle.min.js"></script>
    <script src="js/script.js"></script>
    <script src="js/guide.js"></script>
</body>

</html>
