<?php
/*!
@file product.php
@brief 商品詳細ページ (DB連携版)
@copyright Copyright (c) 2024 Your Name.
*/

// ★注意: このファイルでは、先にHTMLの骨格とヘッダーを読み込みます。
// DB関連の処理は、必要なクラスが定義された後に行うため、
// <body> タグの中で header.php を読み込んだ後で実行します。

?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- ★ページタイトルは後ほどPHPで動的に設定しますが、一旦仮のタイトルを入れます -->
    <title>商品詳細 | OUR BRAND</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@300;400;500;700&family=Zen+Old+Mincho:wght@400;500;700&display=swap"
        rel="stylesheet">

    <link rel="stylesheet" href="https://unpkg.com/swiper@8/swiper-bundle.min.css" />
    <link rel="stylesheet" href="css/top.css">
    <link rel="stylesheet" href="css/product.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>

<body>

    <?php 
    // 共通ヘッダーを読み込む
    // このファイルの中で 'common/contents_db.php' が読み込まれます
    require_once 'header.php'; 
    
    // --- ★ここからPHP処理を開始 ---
    
    // デバッグモードの定義（もし未定義の場合）
    if (!defined('DEBUG')) {
        define('DEBUG', true);
    }

    // GETパラメータから商品IDを取得
    $product_id = $_GET['id'] ?? null;
    $product = null;
    $images = [];
    $tags = [];

    // 商品IDが指定されていて、かつ数字であるかチェック
    if ($product_id && is_numeric($product_id)) {
        
        // データベースクラスのインスタンスを生成
        $product_info_obj = new cproduct_info();
        $product_images_obj = new cproduct_images();
        $product_tags_relation_obj = new cproduct_tags_relation();
        
        // 商品情報を取得
        $product_data_list = $product_info_obj->get_product_list_for_admin(DEBUG, null, 0, 9999);
        foreach ($product_data_list as $p) {
            if ($p['product_id'] == $product_id) {
                $product = $p;
                break;
            }
        }

        if ($product) {
            // 商品画像を取得
            $images = $product_images_obj->get_images_by_product_id(DEBUG, $product_id);
            // 商品タグを取得
            $tags = $product_tags_relation_obj->get_tags_by_product_id(DEBUG, $product_id);
        }

    }

    // 商品が見つからなかった場合の処理
    if (!$product) {
        // header("HTTP/1.0 404 Not Found"); // ヘッダー出力後なのでコメントアウト
        // メインコンテンツ部分でエラー表示を行う
    }
    
    // ★動的にページタイトルを設定するためのJavaScript
    if ($product) {
        echo "<script>document.title = '" . htmlspecialchars($product['product_name'], ENT_QUOTES, 'UTF-8') . " | OUR BRAND';</script>";
    }

    ?>

    <main>
        <?php if ($product): // --- 商品が見つかった場合のみ表示 --- ?>
        <div class="breadcrumb">
            <div class="breadcrumb__inner common-inner">
                <a href="index.php">HOME</a> &gt; <a href="products_list.php">商品一覧</a> &gt; <?= htmlspecialchars($product['product_name']) ?>
            </div>
        </div>

        <section class="product-detail-section">
            <div class="product-detail-section__inner common-inner">
                <div class="product-detail-content">
                    <div class="product-gallery">
                        <div class="product-gallery__main">
                            <img src="<?= !empty($images) ? htmlspecialchars($images[0]['image_path']) : 'https://placehold.co/600x400?text=No+Image' ?>" alt="<?= htmlspecialchars($product['product_name']) ?>">
                        </div>
                        <div class="product-gallery__thumbnails">
                            <?php if (!empty($images)): ?>
                                <?php foreach ($images as $index => $image): ?>
                                    <img src="<?= htmlspecialchars($image['image_path']) ?>" alt="サムネイル<?= $index + 1 ?>" class="<?= $index === 0 ? 'is-active' : '' ?>">
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="product-info">
                        <h2 class="product-info__name"><?= htmlspecialchars($product['product_name']) ?></h2>
                        <p class="product-info__type"><?= htmlspecialchars($product['category_name'] ?? 'カテゴリ未分類') ?></p>
                        <p class="product-info__company"><?= htmlspecialchars($product['company_name'] ?? '製造元不明') ?></p>
                        <p class="product-info__catchcopy"><?= nl2br(htmlspecialchars($product['product_description'] ?? '')) ?></p>
                        <p class="product-info__price">¥ <?= number_format($product['product_price']) ?><span>(税込)</span></p>
                        <p class="product-info__tax-note">※送料別途</p>

                        <div class="product-info__buttons">
                            <div class="product-quantity-add-to-cart">
                                <div class="product-quantity-controls">
                                    <button class="quantity-minus" data-id="product-detail-qty">-</button>
                                    <input type="number" class="quantity-input" value="1" min="1"
                                        data-id="product-detail-qty">
                                    <button class="quantity-plus" data-id="product-detail-qty">+</button>
                                </div>
                                <button id="add-to-cart-btn" class="btn-to-ec-site">
                                    カートに入れる
                                </button>
                            </div>
                            <div class="product-info__favorite">
                                <button class="btn-favorite">
                                    <i class="far fa-heart"></i> <span class="favorite-text">お気に入りに追加</span>
                                </button>
                            </div>
                        </div>

                        <ul class="product-info__tags">
                            <?php if (!empty($tags)): ?>
                                <?php foreach ($tags as $tag): ?>
                                    <li>#<?= htmlspecialchars($tag['tag_name']) ?></li>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </ul>
                    </div>
                </div>

                <div class="product-accordion-item is-closed">
                    <h3 class="product-accordion-item__title product-description__title">商品の特徴<span
                            class="accordion-icon"></span></h3>
                    <div class="product-accordion-item__content">
                        <p><?= nl2br(htmlspecialchars($product['product_discription'] ?? '')) ?></p>
                    </div>
                </div>

                <div class="product-accordion-item is-closed">
                    <h3 class="product-accordion-item__title product-description__title">おすすめの飲み方<span
                            class="accordion-icon"></span></h3>
                    <div class="product-accordion-item__content">
                        <p><?= nl2br(htmlspecialchars($product['product_How'] ?? '')) ?></p>
                    </div>
                </div>

                <div class="paired-snacks">
                    <h3 class="paired-snacks__title">相性のいいおつまみ</h3>
                    <div class="swiper paired-snacks-swiper">
                        <div class="swiper-wrapper">
                            <!-- ここは後で動的にする部分（今は静的） -->
                            <div class="swiper-slide">
                                <div class="product-item">
                                    <a href="otsumami.php">
                                        <div class="product-item__img-wrap">
                                            <img src="./img/otsumami1.jpg" alt="伊勢海老せんべい">
                                        </div>
                                        <h3 class="product-item__name">伊勢海老せんべい</h3>
                                        <p class="product-item__price">¥ 1,200<span>(税込)</span></p>
                                        <p class="product-item__tag">#海老 #香ばしい #お土産</p>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="swiper-pagination"></div>
                    </div>
                </div>

            </div>
        </section>
        <?php else: // --- 商品が見つからなかった場合の表示 --- ?>
            <section class="product-not-found" style="text-align: center; padding: 50px 20px;">
                <p style="font-size: 1.8rem; margin-bottom: 30px;">指定された商品は見つかりませんでした。</p>
                <a href="products_list.php" class="btn-primary" style="display:inline-block; padding: 10px 30px; background-color:#A0522D; color:white; border-radius:5px;">商品一覧へ戻る</a>
            </section>
        <?php endif; ?>
    </main>

    <?php 
    // 共通フッターを読み込む
    require_once 'footer.php'; 
    ?>

    <script src="https://unpkg.com/swiper@8/swiper-bundle.min.js"></script>
    <script src="js/script.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Swiper初期化
            const pairedSwiper = new Swiper('.paired-snacks-swiper', {
                slidesPerView: 1.2,
                spaceBetween: 20,
                centeredSlides: true,
                pagination: {
                    el: '.swiper-pagination',
                    clickable: true,
                },
                breakpoints: {
                    768: {
                        slidesPerView: 3,
                        spaceBetween: 30,
                        centeredSlides: true,
                    }
                }
            });

            // アコーディオン
            const accordionItems = document.querySelectorAll('.product-accordion-item');
            accordionItems.forEach(item => {
                const title = item.querySelector('.product-accordion-item__title');
                const content = item.querySelector('.product-accordion-item__content');
                content.style.height = '0';
                content.style.overflow = 'hidden';
                content.style.transition = 'height 0.3s ease-out';
                title.addEventListener('click', () => {
                    item.classList.toggle('is-closed');
                    if (item.classList.contains('is-closed')) {
                        content.style.height = '0';
                    } else {
                        content.style.height = content.scrollHeight + 'px';
                    }
                });
            });

            // 数量コントロール
            const quantityMinusBtn = document.querySelector('.product-quantity-controls .quantity-minus');
            const quantityPlusBtn = document.querySelector('.product-quantity-controls .quantity-plus');
            const quantityInput = document.querySelector('.product-quantity-controls .quantity-input');

            if (quantityMinusBtn && quantityPlusBtn && quantityInput) {
                quantityMinusBtn.addEventListener('click', function () {
                    let quantity = parseInt(quantityInput.value);
                    if (quantity > 1) {
                        quantityInput.value = quantity - 1;
                    }
                });

                quantityPlusBtn.addEventListener('click', function () {
                    let quantity = parseInt(quantityInput.value);
                    quantityInput.value = quantity + 1;
                });

                quantityInput.addEventListener('change', function () {
                    let quantity = parseInt(this.value);
                    if (isNaN(quantity) || quantity < 1) {
                        this.value = 1;
                    }
                });
            }

            // 「カートに入れる」ボタン（※機能は未実装）
            const addToCartBtn = document.getElementById('add-to-cart-btn');
            if (addToCartBtn) {
                addToCartBtn.addEventListener('click', function () {
                    const selectedQuantity = parseInt(quantityInput.value);
                    console.log(`カート追加処理（未実装）: 商品ID <?= $product_id ?>, 数量 ${selectedQuantity}`);
                });
            }
            
            // 画像切り替え機能
            const mainImg = document.querySelector('.product-gallery__main img');
            const thumbs = document.querySelectorAll('.product-gallery__thumbnails img');
            thumbs.forEach(thumb => {
                thumb.addEventListener('click', function () {
                    mainImg.src = this.src;
                    mainImg.alt = this.alt;
                    thumbs.forEach(t => t.classList.remove('is-active'));
                    this.classList.add('is-active');
                });
            });

            // お気に入りボタン（※機能は未実装）
            const favoriteBtn = document.querySelector('.btn-favorite');
            if (favoriteBtn) {
                favoriteBtn.addEventListener('click', function () {
                    this.classList.toggle('is-favorited');
                    const icon = this.querySelector('i');
                    const favoriteTextSpan = this.querySelector('.favorite-text');

                    if (this.classList.contains('is-favorited')) {
                        icon.classList.replace('far', 'fas');
                        if (favoriteTextSpan) favoriteTextSpan.textContent = 'お気に入り済み';
                    } else {
                        icon.classList.replace('fas', 'far');
                        if (favoriteTextSpan) favoriteTextSpan.textContent = 'お気に入りに追加';
                    }
                });
            }
        });
    </script>
</body>

</html>
