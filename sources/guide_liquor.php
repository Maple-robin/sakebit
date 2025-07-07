<?php
/*!
@file guide_liquor.php
@brief リキュールガイドページ
@copyright Copyright (c) 2024 Your Name.
*/

// ★注意: DB接続やセッション開始は header.php で行われるため、このファイルでの処理は不要です。

?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>リキュールガイド | OUR BRAND</title>
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
        <section class="guide-hero" style="background-image: url('img/osyareOsake.png');">
            <div class="guide-hero__inner">
                <h2 class="guide-hero__title">リキュールの世界へようこそ</h2>
                <p class="guide-hero__subtitle">創造的なカクテルの探求</p>
            </div>
        </section>

        <!-- リキュールって何？ -->
        <section class="guide-section intro-section">
            <div class="section-inner">
                <div class="section-title">
                    <h2 class="ja">リキュールってなんだろう？</h2>
                    <p class="en">What is Liqueur?</p>
                </div>
                <div class="intro-content">
                    <div class="intro-content__image-container">
                        <img src="img/gin.png" alt="リキュールの紹介画像" class="intro-content__image">
                        <div class="intro-content__overlay">
                            <p>リキュールは、蒸留酒に果物やハーブ、スパイスなどの風味を加え、砂糖やシロップで甘みをつけたお酒です。多彩なフレーバーが特徴で、カクテルのベースとして、また食後酒としても楽しまれます。</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- 初心者におすすめの飲み方 -->
        <section class="guide-section beginner-types">
            <div class="section-inner">
                <div class="section-title">
                    <h2 class="ja">主な種類</h2>
                    <p class="en">Major Types</p>
                </div>
                <div class="alcohol-types">
                    <div class="type-card type-card--bg" style="background-image: url('img/soda.png');">
                        <h4>ソーダ割り</h4>
                        <p>最もシンプルで爽やかな飲み方。リキュールの持つ本来の風味を軽やに楽しめます。</p>
                    </div>
                    <div class="type-card type-card--bg" style="background-image: url('img/cassis.png');">
                        <h4>ジュース割り</h4>
                        <p>オレンジやグレープフルーツジュースで割れば、フルーティーで飲みやすいカクテルが完成します。</p>
                    </div>
                    <div class="type-card type-card--bg" style="background-image: url('img/rock.png');">
                        <h4>ロック</h4>
                        <p>氷を入れたグラスに注ぐだけ。リキュール本来の濃厚な味わいをじっくりと楽しめます。</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- おいしい飲み方を見つけよう -->
        <section class="guide-section how-to-drink">
            <div class="section-inner">
                <div class="section-title">
                    <h2 class="ja">おいしい割り方を見つけよう</h2>
                    <p class="en">HOW TO MIX</p>
                </div>

                <div class="drink-ways">
                    <!-- 炭酸で割る -->
                    <div class="drink-way-item">
                        <div class="drink-way-item__title">
                            <h3>炭酸で割る（ソーダ、トニック）</h3>
                        </div>
                        <div class="drink-way-item__step">
                            <div class="drink-way-item__description">
                                <p>リキュール1に対して、炭酸水を3〜4の割合で。清涼感あふれる定番のスタイル。レモンやライムを搾るとさらに爽やかに。</p>
                            </div>
                            <div class="drink-way-item__image">
                                <img src="img/mojito.png" alt="炭酸で割ったリキュール">
                            </div>
                        </div>
                    </div>

                    <hr>

                    <!-- ジュースで割る -->
                    <div class="drink-way-item">
                        <div class="drink-way-item__title">
                            <h3>ジュースで割る</h3>
                        </div>
                        <div class="drink-way-item__step">
                            <div class="drink-way-item__description">
                                <p>オレンジ、グレープフルーツ、パインなどお好みのジュースで。甘みが加わり、アルコール感が和らぐので初心者にもおすすめです。</p>
                            </div>
                            <div class="drink-way-item__image">
                                <img src="img/peach.png" alt="ジュースで割ったリキュール">
                            </div>
                        </div>
                    </div>

                    <hr>

                    <!-- ミルクで割る -->
                    <div class="drink-way-item">
                        <div class="drink-way-item__title">
                            <h3>ミルクで割る</h3>
                        </div>
                        <div class="drink-way-item__step">
                            <div class="drink-way-item__description">
                                <p>カシスやカルーア、マリブなど、フルーツ系やコーヒー系のリキュールと相性抜群。デザート感覚で楽しめるまろやかなカクテルに。</p>
                            </div>
                            <div class="drink-way-item__image">
                                <img src="img/malibu.png" alt="ミルクで割ったリキュール">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- おすすめリキュールカルーセル -->
        <section class="guide-section recommended-sake">
            <div class="section-inner">
                <div class="section-title">
                    <h2 class="ja">おすすめのリキュール</h2>
                    <p class="en">Recommended Liqueur</p>
                </div>
                <div class="swiper recommended-sake-swiper">
                    <div class="swiper-wrapper">
                        <!-- 商品1 -->
                        <div class="swiper-slide product-item">
                            <a href="product.php?id=101">
                                <div class="product-item__img-wrap">
                                    <img src="img/dita.png" alt="ディタ">
                                </div>
                                <h3 class="product-item__name">ディタ ライチ</h3>
                                <p class="product-item__price">¥ 3,500<span>(税込)</span></p>
                                <p class="product-item__tag">#ライチ #華やか</p>
                            </a>
                        </div>
                        <!-- 商品2 -->
                        <div class="swiper-slide product-item">
                            <a href="product.php?id=102">
                                <div class="product-item__img-wrap">
                                    <img src="img/campari.png" alt="カンパリ">
                                </div>
                                <h3 class="product-item__name">カンパリ</h3>
                                <p class="product-item__price">¥ 2,800<span>(税込)</span></p>
                                <p class="product-item__tag">#ハーブ #苦み</p>
                            </a>
                        </div>
                        <!-- 商品3 -->
                        <div class="swiper-slide product-item">
                            <a href="product.php?id=103">
                                <div class="product-item__img-wrap">
                                    <img src="img/ボンベイ・サファイア.png" alt="ボンベイ・サファイア">
                                </div>
                                <h3 class="product-item__name">ボンベイ・サファイア</h3>
                                <p class="product-item__price">¥ 3,000<span>(税込)</span></p>
                                <p class="product-item__tag">#ジン #ボタニカル</p>
                            </a>
                        </div>
                        <!-- 商品4 -->
                        <div class="swiper-slide product-item">
                            <a href="product.php?id=104">
                                <div class="product-item__img-wrap">
                                    <img src="img/梅酒原酒_image1.png" alt="梅酒">
                                </div>
                                <h3 class="product-item__name">特選梅酒うぐいすとまり 鶯とろ</h3>
                                <p class="product-item__price">¥ 2,900<span>(税込)</span></p>
                                <p class="product-item__tag">#梅酒 #濃厚</p>
                            </a>
                        </div>
                        <!-- 商品5 -->
                        <div class="swiper-slide product-item">
                            <a href="product.php?id=105">
                                <div class="product-item__img-wrap">
                                    <img src="img/苺梅酒2.png" alt="苺梅酒">
                                </div>
                                <h3 class="product-item__name">あまおう梅酒 あまおう、はじめました。</h3>
                                <p class="product-item__price">¥ 3,200<span>(税込)</span></p>
                                <p class="product-item__tag">#苺 #デザート</p>
                            </a>
                        </div>
                    </div>
                    <!-- If we need pagination -->
                    <div class="swiper-pagination"></div>
                </div>
                 <a href="products_list.php?category=リキュール" class="btn-all-products">リキュール一覧を見る</a>
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
