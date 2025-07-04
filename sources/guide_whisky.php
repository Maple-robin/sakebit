<?php
// ヘッダーでセッション開始、DB接続、共通関数の読み込みを行っています。
?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ウィスキーガイド - SAKEBIT</title>
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
        <div class="container">
            <!-- ヒーローセクション -->
            <section class="sake-hero" style="background-image: url('img/whisky_barrel.png');">
                <div class="sake-hero__inner">
                    <h2 class="sake-hero__title">ウィスキーの世界へようこそ</h2>
                    <p class="sake-hero__subtitle">熟成が織りなす深い味わい</p>
                </div>
            </section>

            <!-- ウィスキーって何？ -->
            <section class="sake-section sake-intro-section">
                <div class="section-inner">
                    <div class="section-title">
                        <h2 class="ja">ウィスキーってなんだろう？</h2>
                        <p class="en">What is Whisky?</p>
                    </div>
                    <div class="sake-intro-content">
                        <div class="sake-intro-content__image-container">
                            <img src="img/whisky_bottle.png" alt="ウィスキーの紹介画像" class="sake-intro-content__image">
                            <div class="sake-intro-content__overlay">
                                <p>ウィスキーは、大麦、ライ麦、トウモロコシなどの穀物を原料とし、糖化、発酵、蒸留を経て、木製の樽で熟成させたお酒です。産地や製法によって多様な個性が生まれます。</p>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- 初心者におすすめの種類 -->
            <section class="sake-section sake-beginner-types">
                <div class="section-inner">
                    <div class="section-title">
                        <h2 class="ja">初心者におすすめの種類</h2>
                        <p class="en">Easy to Drink</p>
                    </div>
                    <div class="sake-types">
                        <div class="sake-type-card">
                            <h4>スコッチウィスキー</h4>
                            <p>スモーキーな香りが特徴ですが、華やかで飲みやすいタイプも多数。バニラのような甘い香りも楽しめます。</p>
                        </div>
                        <div class="sake-type-card sake-type-card--bg" style="background-image: url('img/whisky_glass.png');">
                            <h4>ジャパニーズウィスキー</h4>
                            <p>繊細でバランスの取れた味わいが特徴。ハイボールにすると、その良さが一層引き立ちます。</p>
                        </div>
                        <div class="sake-type-card">
                            <h4>バーボンウィスキー</h4>
                            <p>トウモロコシを主原料とし、甘く華やかな香りが特徴。コーラで割るのもおすすめです。</p>
                        </div>
                    </div>
                </div>
            </section>

            <!-- おいしい飲み方を見つけよう -->
            <section class="sake-section sake-how-to-drink">
                <div class="section-inner">
                    <div class="section-title">
                        <h2 class="ja">おいしい飲み方を見つけよう</h2>
                        <p class="en">HOW TO DRINK</p>
                    </div>

                    <div class="drink-ways">
                        <!-- ストレート -->
                        <div class="drink-way-item">
                            <div class="drink-way-item__title">
                                <h3>ストレート</h3>
                            </div>
                            <div class="drink-way-item__step">
                                <div class="drink-way-item__description">
                                    <p>ウィスキー本来の香りや味わいを最も楽しめる飲み方。チェイサー（水）を用意しながら、ゆっくりと味わうのがおすすめです。</p>
                                </div>
                                <div class="drink-way-item__image">
                                    <img src="img/straight_whisky.png" alt="ストレートウィスキー">
                                </div>
                            </div>
                        </div>

                        <hr>

                        <!-- ハイボール -->
                        <div class="drink-way-item">
                            <div class="drink-way-item__title">
                                <h3>ハイボール</h3>
                            </div>
                            <div class="drink-way-item__step">
                                <div class="drink-way-item__description">
                                    <p>ウィスキーをソーダで割った爽快な飲み方。食事との相性も抜群です。レモンピールを加えると香りが引き立ちます。</p>
                                </div>
                                <div class="drink-way-item__image">
                                    <img src="img/highball.png" alt="ハイボール">
                                </div>
                            </div>
                        </div>

                        <hr>

                        <!-- トワイスアップ -->
                        <div class="drink-way-item">
                            <div class="drink-way-item__title">
                                <h3>トワイスアップ</h3>
                            </div>
                            <div class="drink-way-item__step">
                                <div class="drink-way-item__description">
                                    <p>ウィスキーと常温の水を1:1で割る飲み方。香りが最も開き、ブレンダーがテイスティングする際にも用いる方法です。</p>
                                </div>
                                <div class="drink-way-item__image">
                                    <img src="img/twice_up.png" alt="トワイスアップ">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- おすすめウィスキーカルーセル -->
            <section class="sake-section recommended-sake">
                <div class="section-inner">
                    <div class="section-title">
                        <h2 class="ja">おすすめのウィスキー</h2>
                        <p class="en">Recommended Whisky</p>
                    </div>
                    <div class="swiper sake-swiper">
                        <div class="swiper-wrapper">
                            <!-- 商品1 -->
                            <div class="swiper-slide product-item">
                                <a href="product.php?id=501">
                                    <div class="product-item__img-wrap">
                                        <img src="img/yamazaki.png" alt="山崎">
                                    </div>
                                    <h3 class="product-item__name">サントリーシングルモルトウイスキー 山崎</h3>
                                    <p class="product-item__price">¥ 9,000<span>(税込)</span></p>
                                    <p class="product-item__tag">#ジャパニーズ #シングルモルト</p>
                                </a>
                            </div>
                            <!-- 商品2 -->
                            <div class="swiper-slide product-item">
                                <a href="product.php?id=502">
                                    <div class="product-item__img-wrap">
                                        <img src="img/hakushu.png" alt="白州">
                                    </div>
                                    <h3 class="product-item__name">サントリーシングルモルトウイスキー 白州</h3>
                                    <p class="product-item__price">¥ 9,000<span>(税込)</span></p>
                                    <p class="product-item__tag">#ジャパニーズ #爽やか</p>
                                </a>
                            </div>
                            <!-- 商品3 -->
                            <div class="swiper-slide product-item">
                                <a href="product.php?id=503">
                                    <div class="product-item__img-wrap">
                                        <img src="img/hibiki.png" alt="響">
                                    </div>
                                    <h3 class="product-item__name">サントリーウイスキー 響</h3>
                                    <p class="product-item__price">¥ 12,000<span>(税込)</span></p>
                                    <p class="product-item__tag">#ジャパニーズ #ブレンデッド</p>
                                </a>
                            </div>
                            <!-- 商品4 -->
                            <div class="swiper-slide product-item">
                                <a href="product.php?id=504">
                                    <div class="product-item__img-wrap">
                                        <img src="img/yoichi.png" alt="余市">
                                    </div>
                                    <h3 class="product-item__name">シングルモルト余市</h3>
                                    <p class="product-item__price">¥ 8,500<span>(税込)</span></p>
                                    <p class="product-item__tag">#ジャパニーズ #力強い</p>
                                </a>
                            </div>
                            <!-- 商品5 -->
                            <div class="swiper-slide product-item">
                                <a href="product.php?id=505">
                                    <div class="product-item__img-wrap">
                                        <img src="img/taketsuru.png" alt="竹鶴">
                                    </div>
                                    <h3 class="product-item__name">竹鶴ピュアモルト</h3>
                                    <p class="product-item__price">¥ 7,000<span>(税込)</span></p>
                                    <p class="product-item__tag">#ジャパニーズ #ピュアモルト</p>
                                </a>
                            </div>
                        </div>
                        <!-- If we need pagination -->
                        <div class="swiper-pagination"></div>
                    </div>
                    <a href="products_list.php?category=whisky" class="btn-view-all">ウィスキー一覧を見る</a>
                </div>
            </section>
        </div>
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
