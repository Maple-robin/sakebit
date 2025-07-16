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
        <section class="guide-hero" style="background-image: url('img/ワインブドウ.png');">
            <div class="guide-hero__inner">
                <h2 class="guide-hero__title">ワインの世界へようこそ</h2>
                <p class="guide-hero__subtitle">ブドウ畑からの物語</p>
            </div>
        </section>

        <!-- ワインって何？ -->
        <section class="guide-section guide-intro-section">
            <div class="section-inner">
                <div class="section-title">
                    <h2 class="ja">ワインってなんだろう？</h2>
                    <p class="en">What is Wine?</p>
                </div>
                <div class="intro-content">
                    <div class="intro-content__image-container">
                        <img src="img/ワインブドウ1.png" alt="ワインの紹介画像" class="intro-content__image">
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
                    <div class="type-card type-card--bg" style="background-image: url('img/ワイン赤ワイン.png');">
                        <h4>赤ワイン</h4>
                        <p>ブドウの皮や種を発酵させて作られる。<br>渋みとコクがある味わいが特徴です。</p>
                    </div>
                    <div class="type-card type-card--bg" style="background-image: url('img/ワイン白ワイン.png');">
                        <h4>白ワイン</h4>
                        <p>ブドウの果汁のみを発酵させて作られる。<br>爽やかな酸味と果実のような香りが特徴です。</p>
                    </div>
                    <div class="type-card type-card--bg" style="background-image: url('img/ワインロゼ.png');">
                        <h4>ロゼ</h4>
                        <p>ブドウの皮を少しだけ使って作るワインです。<br>すっきりとしたブドウの風味と後味が特徴です。</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- おいしい飲み方を見つけよう -->
        <section class="how-to-drink-section section-inner">
            <div class="section-title">
                <h2 class="ja">ワインの飲み方</h2>
                <p class="en">HOW TO WINE</p>
            </div>

            <div class="drink-ways">
                <!-- 料理との相性を楽しむ -->
                <div class="drink-way-item">
                    <div class="drink-way-item__title">
                        <h3>料理との相性を楽しむ</h3>
                    </div>
                    <div class="drink-way-item__step">
                        <div class="drink-way-item__description">
                            <p>まずは料理との相性を楽しみましょう。
                                ワインが料理の風味を広げ、新たな味わいが生まれます。</p>
                        </div>
                        <div class="drink-way-item__image">
                            <img src="img/ワイン料理2.png" alt="料理との相性を楽しむ">
                        </div>
                    </div>
                </div>

                <hr>

                <!-- 香りを意識する -->
                <div class="drink-way-item">
                    <div class="drink-way-item__title">
                        <h3>香りを意識する</h3>
                    </div>
                    <div class="drink-way-item__step">
                        <div class="drink-way-item__description">
                            <p>飲むだけでなく、香りにも意識を向けてみましょう。いつもと違うワインの魅力が見つかります。</p>
                        </div>
                        <div class="drink-way-item__image">
                            <img src="img/ワイン香り.png" alt="香りを意識する">
                        </div>
                    </div>
                </div>

                <hr>

                <!-- グラスに注いで楽しむ -->
                <div class="drink-way-item">
                    <div class="drink-way-item__title">
                        <h3>グラスに注いで楽しむ</h3>
                    </div>
                    <div class="drink-way-item__step">
                        <div class="drink-way-item__description">
                            <p>グラスに注ぐことでワインの香りがより引き立ち、見た目も美しく楽しめます。</p>
                        </div>
                        <div class="drink-way-item__image">
                            <img src="img/ワイン注ぐ2.png" alt="グラスに注いで楽しむ">
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
                            <img src="img/日本酒カテゴリ.png" alt="日本酒ガイド" class="category-card__img">
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
                            <img src="img/缶チューハイカテゴリ.png" alt="缶チューハイガイド" class="category-card__img">
                            <h3 class="category-card__name">缶チューハイ</h3>
                        </a>
                    </li>
                    <li class="category-list__item">
                        <a href="guide_shochu.php" class="category-card">
                            <img src="img/金次郎1.png" alt="焼酎ガイド" class="category-card__img">
                            <h3 class="category-card__name">焼酎</h3>
                        </a>
                    </li>
                    <li class="category-list__item">
                        <a href="guide_whisky.php" class="category-card">
                            <img src="img/ウイスキーカテゴリ.png" alt="ウィスキーガイド" class="category-card__img">
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
                            <img src="img/バラ梅酒1.png" alt="リキュールガイド" class="category-card__img">
                            <h3 class="category-card__name">リキュール</h3>
                        </a>
                    </li>
                    <li class="category-list__item">
                        <a href="guide_wine.php" class="category-card">
                            <img src="img/ワインカテゴリ.png" alt="ワインガイド" class="category-card__img">
                            <h3 class="category-card__name">ワイン</h3>
                        </a>
                    </li>
                    <li class="category-list__item">
                        <a href="guide_beer.php" class="category-card">
                            <img src="img/ビールカテゴリ.png" alt="ビールガイド" class="category-card__img">
                            <h3 class="category-card__name">ビール</h3>
                        </a>
                    </li>
                </ul>
            </div>
        </section>
        <!-- /ガイド一覧セクション -->
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
