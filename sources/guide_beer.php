<?php
// ヘッダーでセッション開始、DB接続、共通関数の読み込みを行っています。

// ★★★【変更箇所】★★★
// データベースクラスを直接使用するため、contents_db.php を読み込みます。
require_once 'common/contents_db.php';

// 「初心者向け」タグを持つ商品を優先的に取得し、足りない分は売上順で補完
$beer_category_id = 10;
$priority_tag_name = '初心者向け'; // 優先したいタグ名を指定
$product_db = new cproduct_info();
$recommended_beer_products = $product_db->get_recommended_products_for_guide(false, $beer_category_id, $priority_tag_name, 5);
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
        <section class="guide-hero" style="background-image: url('img/ビール大麦2.png');">
            <div class="guide-hero__inner">
                <h2 class="guide-hero__title">ビールの世界へようこそ</h2>
                <p class="guide-hero__subtitle">クラフトの探求と楽しみ</p>
            </div>
        </section>

        <!-- ビールって何？ -->
        <section class="guide-section guide-intro-section">
            <div class="section-inner">
                <div class="section-title">
                    <h2 class="ja">ビールってなんだろう？</h2>
                    <p class="en">What is Beer?</p>
                </div>
                <div class="intro-content">
                    <div class="intro-content__image-container">
                        <img src="img/ビールたくさん.png" alt="ビールの紹介画像" class="intro-content__image">
                        <div class="intro-content__overlay">
                            <p>ビールは、主に大麦の麦芽を酵母で発酵させて造られるアルコール飲料です。ホップを加えることで、特有の苦味と香りが生まれます。世界中で最も古くから親しまれているお酒の一つです。</p>
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
                    <div class="type-card type-card--bg" style="background-image: url('img/ビールピルスナー.png');">
                        <h4>ピルスナー</h4>
                        <p>世界で最も普及しているスタイルで、アサヒスーパードライやキリン一番搾りなど、日本で売られているビールのほとんどがこれに当たります。すっきりとしたのどごしと爽やかなホップの苦みが特徴です。</p>
                    </div>
                    <div class="type-card type-card--bg" style="background-image: url('img/ビールヴァイツェン.png');">
                        <h4>ヴァイツェン</h4>
                        <p>小麦を主原料とした、フルーティーでバナナのような香りが特徴の白ビールです。苦みが少なくまろやかな口当たりで、酵母の働きによる独特の風味が楽しめます。</p>
                    </div>
                    <div class="type-card type-card--bg" style="background-image: url('img/ビールペールエール.png');">
                        <h4>ペールエール</h4>
                        <p>ホップを豊富に使うことで、柑橘系やフローラルな華やかな香りと程よい苦みが特徴です。クラフトビールの入門としても人気が高く、香りを楽しむことに重点を置いたスタイルです。</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- おいしい飲み方を見つけよう -->
        <section class="how-to-drink-section section-inner">
            <div class="section-title">
                <h2 class="ja">ビールの飲み方</h2>
                <p class="en">HOW TO BEER</p>
            </div>

            <div class="drink-ways">
                <!-- 適温で風味を最大限に -->
                <div class="drink-way-item">
                    <div class="drink-way-item__title">
                        <h3>ビールの温度</h3>
                    </div>
                    <div class="drink-way-item__step">
                        <div class="drink-way-item__description">
                            <p>ビールの温度<br>冷蔵庫で冷やすとすっきり。少し温めると、香りが際立ち、ビールの奥深さが感じられます。</p>
                        </div>
                        <div class="drink-way-item__image">
                            <img src="img/ビール冷え1.png" alt="適温で風味を最大限に">
                        </div>
                    </div>
                </div>

                <hr>

                <!-- グラスに注いで香り立ちを -->
                <div class="drink-way-item">
                    <div class="drink-way-item__title">
                        <h3>グラスに注いで香り立ちを</h3>
                    </div>
                    <div class="drink-way-item__step">
                        <div class="drink-way-item__description">
                            <p>グラスに注ぐと泡立ちが良くなり、香りが広がりやすくなります。見た目も美しく、より美味しく感じられます。</p>
                        </div>
                        <div class="drink-way-item__image">
                            <img src="img/ビール注ぐ2.png" alt="グラスに注いで香り立ちを">
                        </div>
                    </div>
                </div>

                <hr>

                <!-- 料理との相性で深みを -->
                <div class="drink-way-item">
                    <div class="drink-way-item__title">
                        <h3>料理との相性で深みを</h3>
                    </div>
                    <div class="drink-way-item__step">
                        <div class="drink-way-item__description">
                            <p>ビールと料理を合わせることで、互いの美味しさが引き立ちます。すっきり系は揚げ物、フルーティー系はサラダなどがおすすめです。</p>
                        </div>
                        <div class="drink-way-item__image">
                            <img src="img/ビールおつまみ1.png" alt="料理との相性で深みを">
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- ★★★【変更箇所】おすすめのビール (動的カルーセル) ★★★ -->
        <?php if (!empty($recommended_beer_products)) : ?>
            <section class="guide-section recommended-sake">
                <div class="section-inner">
                    <div class="section-title">
                        <h2 class="ja">おすすめのビール</h2>
                        <p class="en">Recommended Beer</p>
                    </div>
                    <div class="swiper recommended-sake-swiper">
                        <div class="swiper-wrapper">
                            <?php foreach ($recommended_beer_products as $product) : ?>
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
                    <a href="products_list.php?category=<?php echo $beer_category_id; ?>" class="btn-all-products">ビール一覧を見る</a>
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

    <?php
    // 共通フッターを読み込む
    require_once 'footer.php';
    ?>

    <script src="https://unpkg.com/swiper@8/swiper-bundle.min.js"></script>
    <script src="js/script.js"></script>
    <script src="js/guide.js"></script>
</body>

</html>