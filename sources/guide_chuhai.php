<?php
// ヘッダーでセッション開始、DB接続、共通関数の読み込みを行っています。

// ★★★【変更箇所】★★★
// データベースクラスを直接使用するため、contents_db.php を読み込みます。
require_once 'common/contents_db.php';

// 「初心者向け」タグを持つ商品を優先的に取得し、足りない分は売上順で補完
$chuhai_category_id = 4;
$priority_tag_name = '初心者向け'; // 優先したいタグ名を指定
$product_db = new cproduct_info();
$recommended_chuhai_products = $product_db->get_recommended_products_for_guide(false, $chuhai_category_id, $priority_tag_name, 5);
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
        <section class="guide-section guide-intro-section">
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

        <!-- 主な種類 -->
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
                            <img src="img/チューハイレモン.png" alt="グラスに注いだレモンサワー">
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
                            <img src="img/チューハイグレフル.png" alt="フルーツを入れたチューハイ">
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
                            <img src="img/チューハイアレンジ.png" alt="アレンジチューハイ">
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- ★★★【変更箇所】おすすめの缶チューハイ (動的カルーセル) ★★★ -->
        <?php if (!empty($recommended_chuhai_products)) : ?>
            <section class="guide-section recommended-sake">
                <div class="section-inner">
                    <div class="section-title">
                        <h2 class="ja">おすすめの缶チューハイ</h2>
                        <p class="en">Recommended Canned Chuhai</p>
                    </div>
                    <div class="swiper recommended-sake-swiper">
                        <div class="swiper-wrapper">
                            <?php foreach ($recommended_chuhai_products as $product) : ?>
                                <div class="swiper-slide product-item">
                                    <a href="product.php?id=<?php echo htmlspecialchars($product['product_id'], ENT_QUOTES, 'UTF-8'); ?>">
                                        <div class="product-item__img-wrap">
                                            <img src="<?php echo htmlspecialchars($product['main_image_path'] ?? 'img/no-image.png', ENT_QUOTES, 'UTF-8'); ?>" alt="<?php echo htmlspecialchars($product['product_name'], ENT_QUOTES, 'UTF-8'); ?>">
                                        </div>
                                        <h3 class="product-item__name"><?php echo htmlspecialchars($product['product_name'], ENT_QUOTES, 'UTF-8'); ?></h3>
                                        <p class="product-item__price">¥ <?php echo number_format($product['product_price']); ?><span>(税込)</span></p>
                                    </a>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        <!-- If we need pagination -->
                        <div class="swiper-pagination"></div>
                    </div>
                    <a href="products_list.php?category=<?php echo $chuhai_category_id; ?>" class="btn-all-products">缶チューハイ一覧を見る</a>
                </div>
            </section>
        <?php endif; ?>
        <!-- ★★★【変更箇所ここまで】★★★ -->

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

    <?php require_once 'footer.php'; ?>

    <script src="https://unpkg.com/swiper@8/swiper-bundle.min.js"></script>
    <script src="js/script.js"></script>
    <script src="js/guide.js"></script>
</body>

</html>