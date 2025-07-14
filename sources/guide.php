<?php
/*!
@file guide.php
@brief お酒ガイドページ
@copyright Copyright (c) 2024 Your Name.
*/

// ★注意: DB接続やセッション開始は header.php で行われるため、このファイルでの処理は不要です。

?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>お酒ガイド | OUR BRAND</title>
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
                <h2 class="guide-hero__title">お酒の世界へようこそ</h2>
                <p class="guide-hero__subtitle">初心者向けガイド</p>
            </div>
        </section>

        <!-- お酒って何？ -->
        <section class="guide-section intro-section">
            <div class="section-inner">
                <div class="section-title">
                    <h2 class="ja">お酒ってなんだろう？</h2>
                    <p class="en">What is Sake?</p>
                </div>
                <div class="intro-content">
                    <div class="intro-content__image-container">
                        <img src="img/gingerale.png" alt="お酒の紹介画像" class="intro-content__image">
                        <div class="intro-content__overlay">
                            <p>お酒は、リラックスしたり、食事をより楽しんだり、人とのコミュニケーションを円滑にする飲み物です。世界にはビール、ワイン、日本酒など、さまざまな種類のお酒があり、それぞれに独自の文化や味わいがあります。</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- 初心者におすすめのお酒 -->
        <section class="guide-section beginner-types">
            <div class="section-inner">
                <div class="section-title">
                    <h2 class="ja">初心者におすすめの種類</h2>
                    <p class="en">For Beginners</p>
                </div>
                <div class="alcohol-types">
                    <div class="type-card type-card--bg" style="background-image: url('img/苺梅酒2.png');">
                        <h4>果実酒・リキュール</h4>
                        <p>甘くてフルーティーなものが多く、ジュース感覚で楽しめるので最初の一杯にぴったりです。</p>
                    </div>
                    <div class="type-card type-card--bg" style="background-image: url('img/缶チューハイ.png');">
                        <h4>缶チューハイ</h4>
                        <p>アルコール度数が低めで、様々なフレーバーがあります。コンビニやスーパーで手軽に試せます。</p>
                    </div>
                    <div class="type-card type-card--bg" style="background-image: url('img/ビール注ぐ.png');">
                        <h4>ビール</h4>
                        <p>苦みが特徴ですが、フルーティーで飲みやすいタイプも増えています。まずは少量から試してみましょう。</p>
                    </div>
                </div>
            </div>
        </section>

        <section class="how-to-drink-section section-inner">
            <div class="section-title">
                <h2 class="ja">おいしい飲み方を見つけよう</h2>
                <p class="en">HOW TO DRINK</p>
            </div>

            <div class="drink-ways">
                <!-- 冷やして飲む -->
                <div class="drink-way-item">
                    <div class="drink-way-item__title">
                        <h3>冷やして飲む</h3>
                    </div>
                    <div class="drink-way-item__step">
                        <div class="drink-way-item__description">
                            <p>冷蔵庫や氷で冷やします。</p>
                        </div>
                        <div class="drink-way-item__image">
                            <img src="img/osake.png" alt="グラスに注がれた冷たいお酒">
                        </div>
                    </div>
                </div>

                <hr>

                <!-- 温めて飲む -->
                <div class="drink-way-item">
                    <div class="drink-way-item__title">
                        <h3>温めて飲む</h3>
                    </div>
                    <div class="drink-way-item__step">
                        <div class="drink-way-item__description">
                            <p>徳利や耐熱容器に入れて温めます。</p>
                        </div>
                        <div class="drink-way-item__image">
                            <img src="img/sake-warm.jpg" alt="温かいお酒、熱燗">
                        </div>
                    </div>
                </div>

                <hr>

                <!-- 割って飲む -->
                <div class="drink-way-item">
                    <div class="drink-way-item__title">
                        <h3>割って飲む</h3>
                    </div>
                    <div class="drink-way-item__step">
                        <div class="drink-way-item__description">
                            <p>ソーダやジュースなど、お好みの割り材で割ります。</p>
                        </div>
                        <div class="drink-way-item__image">
                            <img src="img/gingerale.png" alt="ジンジャーエールで割るお酒">
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- お酒を楽しむための注意点 -->
        <section class="guide-section caution-section">
            <div class="section-inner">
                <div class="section-title">
                    <h2 class="ja">お酒を楽しむための注意点</h2>
                    <p class="en">Important Points</p>
                </div>
                <div class="caution-list">
                    <div class="caution-item">
                        <div class="caution-item__header">
                            <div class="caution-item__icon">
                                <i class="fas fa-glass-whiskey"></i>
                            </div>
                            <h4 class="caution-item__title">適量を守る</h4>
                            <div class="caution-item__toggle">
                                <span class="toggle-icon"></span>
                            </div>
                        </div>
                        <div class="caution-item__content">
                            <div class="caution-item__text">
                                <p>自分のペースで、無理のない範囲で楽しみましょう。飲みすぎは健康を害する原因になります。</p>
                            </div>
                        </div>
                    </div>
                    <div class="caution-item">
                        <div class="caution-item__header">
                            <div class="caution-item__icon">
                                <i class="fas fa-utensils"></i>
                            </div>
                            <h4 class="caution-item__title">空腹時を避ける</h4>
                            <div class="caution-item__toggle">
                                <span class="toggle-icon"></span>
                            </div>
                        </div>
                        <div class="caution-item__content">
                            <div class="caution-item__text">
                                <p>食事をしながら、または何か食べてから飲むと、アルコールの吸収が穏やかになります。</p>
                            </div>
                        </div>
                    </div>
                    <div class="caution-item">
                        <div class="caution-item__header">
                            <div class="caution-item__icon">
                                <i class="fas fa-tint"></i>
                            </div>
                            <h4 class="caution-item__title">水分補給を忘れずに</h4>
                            <div class="caution-item__toggle">
                                <span class="toggle-icon"></span>
                            </div>
                        </div>
                        <div class="caution-item__content">
                            <div class="caution-item__text">
                                <p>お酒と同量以上の水を飲むことを心がけましょう。脱水症状や二日酔いの予防になります。</p>
                            </div>
                        </div>
                    </div>
                    <div class="caution-item">
                        <div class="caution-item__header">
                            <div class="caution-item__icon">
                                <i class="fas fa-car-crash"></i>
                            </div>
                            <h4 class="caution-item__title">飲酒後の運転は絶対にしない</h4>
                            <div class="caution-item__toggle">
                                <span class="toggle-icon"></span>
                            </div>
                        </div>
                        <div class="caution-item__content">
                            <div class="caution-item__text">
                                <p>飲酒運転は法律で固く禁じられています。公共交通機関や運転代行を利用しましょう。</p>
                            </div>
                        </div>
                    </div>
                    <div class="caution-item">
                        <div class="caution-item__header">
                            <div class="caution-item__icon">
                                <i class="fas fa-calendar-day"></i>
                            </div>
                            <h4 class="caution-item__title">休肝日を設ける</h4>
                            <div class="caution-item__toggle">
                                <span class="toggle-icon"></span>
                            </div>
                        </div>
                        <div class="caution-item__content">
                            <div class="caution-item__text">
                                <p>週に1〜2日はお酒を飲まない日を作り、肝臓を休ませてあげましょう。</p>
                            </div>
                        </div>
                    </div>
                </div>
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
