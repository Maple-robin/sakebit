<?php
/*!
@file otsumami.php
@brief おつまみ詳細ページ (DB連携・全機能版)
@copyright Copyright (c) 2024 Your Name.
*/
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>おつまみ詳細 | SAKE BIT</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@300;400;500;700&family=Zen+Old+Mincho:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/swiper@8/swiper-bundle.min.css" />
    <link rel="stylesheet" href="css/top.css">
    <link rel="stylesheet" href="css/product.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>

    <?php 
    require_once 'header.php'; 
    
    if (!defined('DEBUG')) define('DEBUG', true);

    $otumami_id = $_GET['id'] ?? null;
    $otumami = null;
    $images = [];
    $tags = [];
    $is_favorited = false;
    $recommended_products = [];
    $recommend_title = '';
    
    $is_logged_in = isset($_SESSION['user_id']);
    $current_user_id = $_SESSION['user_id'] ?? null;

    if ($otumami_id && is_numeric($otumami_id)) {
        $otumami_db = new cotumami();
        $otumami_images_db = new cotumami_images();
        $otumami_tags_db = new cotumami_otumami_tags();
        $favorites_db = new cotumami_favorites();
        $order_items_db = new corder_items();
        $product_info_db = new cproduct_info();

        $otumami = $otumami_db->get_tgt(DEBUG, $otumami_id);
        if ($otumami) {
            $images = $otumami_images_db->get_images_by_otumami_id(DEBUG, $otumami_id);
            // TODO: おつまみタグ取得機能
            
            if ($is_logged_in) {
                $is_favorited = $favorites_db->is_favorited(DEBUG, $current_user_id, $otumami_id);
            }

            // 1. まず「よく一緒に買われているお酒」を取得
            $recommended_products = $order_items_db->get_frequently_bought_with_products(DEBUG, $otumami_id, 5);
            $recommend_title = 'よく一緒に買われているお酒';

            // 2. もし結果が空なら、フォールバック処理
            if (empty($recommended_products) && isset($otumami['combi_category_id'])) {
                // 「相性のいいカテゴリ」の売上上位を取得
                $recommended_products = $product_info_db->get_top_selling_products_by_category(DEBUG, $otumami['combi_category_id'], 5);
                $recommend_title = 'このおつまみに合うお酒のおすすめ';
            }
        }
    }
    
    if ($otumami) {
        echo "<script>document.title = '" . htmlspecialchars($otumami['otumami_name'], ENT_QUOTES, 'UTF-8') . " | SAKE BIT';</script>";
    }
    ?>

    <main>
        <?php if ($otumami): ?>
        <div class="breadcrumb">
            <div class="breadcrumb__inner common-inner">
                <a href="index.php">HOME</a> &gt; <a href="otumami_list.php">おつまみ一覧</a> &gt; <?= htmlspecialchars($otumami['otumami_name']) ?>
            </div>
        </div>

        <section class="product-detail-section">
            <div class="product-detail-section__inner common-inner">
                <div class="product-detail-content">
                    <div class="product-gallery">
                        <div class="product-gallery__main">
                            <img src="<?= !empty($images) ? htmlspecialchars($images[0]['image_path']) : 'https://placehold.co/600x400?text=No+Image' ?>" alt="<?= htmlspecialchars($otumami['otumami_name']) ?>">
                        </div>
                        <div class="product-gallery__thumbnails">
                            <?php foreach ($images as $index => $image): ?>
                                <img src="<?= htmlspecialchars($image['image_path']) ?>" alt="サムネイル<?= $index + 1 ?>" class="<?= $index === 0 ? 'is-active' : '' ?>">
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <div class="product-info">
                        <h2 class="product-info__name"><?= htmlspecialchars($otumami['otumami_name']) ?></h2>
                        <p class="product-info__catchcopy"><?= nl2br(htmlspecialchars($otumami['otumami_description'] ?? '')) ?></p>
                        <p class="product-info__price">¥ <?= number_format($otumami['otumami_price']) ?><span>(税込)</span></p>
                        <p class="product-info__tax-note">※送料別途</p>

                        <div class="product-info__buttons">
                            <div class="product-quantity-add-to-cart">
                                <div class="product-quantity-controls">
                                    <button class="quantity-minus">-</button>
                                    <input type="number" class="quantity-input" value="1" min="1">
                                    <button class="quantity-plus">+</button>
                                </div>
                                <button id="add-to-cart-btn" class="btn-to-ec-site">カートに入れる</button>
                            </div>
                            <div class="product-info__favorite">
                                <button class="btn-favorite <?= $is_favorited ? 'is-favorited' : '' ?>" data-otumami-id="<?= $otumami_id ?>">
                                    <i class="<?= $is_favorited ? 'fas' : 'far' ?> fa-heart"></i>
                                    <span class="favorite-text"><?= $is_favorited ? 'お気に入り済み' : 'お気に入りに追加' ?></span>
                                </button>
                            </div>
                        </div>
                        <!-- タグ表示機能は後で実装 -->
                    </div>
                </div>

                <div class="product-accordion-item is-closed">
                    <h3 class="product-accordion-item__title product-description__title">おつまみの説明<span class="accordion-icon"></span></h3>
                    <div class="product-accordion-item__content">
                        <p><?= nl2br(htmlspecialchars($otumami['otumami_discription'] ?? '')) ?></p>
                    </div>
                </div>
                
                <?php if (!empty($recommended_products)): ?>
                <div class="paired-snacks">
                    <h3 class="paired-snacks__title"><?= htmlspecialchars($recommend_title) ?></h3>
                    <div class="swiper paired-snacks-swiper">
                        <div class="swiper-wrapper">
                            <?php foreach ($recommended_products as $product): ?>
                            <div class="swiper-slide">
                                <div class="product-item">
                                    <a href="product.php?id=<?= htmlspecialchars($product['product_id']) ?>">
                                        <div class="product-item__img-wrap">
                                            <img src="<?= htmlspecialchars($product['main_image_path'] ?? 'img/no-image.png') ?>" alt="<?= htmlspecialchars($product['product_name']) ?>">
                                        </div>
                                        <h3 class="product-item__name"><?= htmlspecialchars($product['product_name']) ?></h3>
                                        <p class="product-item__price">¥ <?= number_format($product['product_price']) ?><span>(税込)</span></p>
                                    </a>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                        <div class="swiper-pagination"></div>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </section>
        <?php else: ?>
            <p style="text-align:center; padding: 50px;">指定されたおつまみは見つかりませんでした。</p>
        <?php endif; ?>
    </main>

    <?php require_once 'footer.php'; ?>

    <script src="https://unpkg.com/swiper@8/swiper-bundle.min.js"></script>
    <script src="js/script.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Swiper初期化
            const pairedSwiper = new Swiper('.paired-snacks-swiper', { slidesPerView: 1.5, spaceBetween: 20, centeredSlides: true, loop: document.querySelectorAll('.paired-snacks-swiper .swiper-slide').length > 1, pagination: { el: '.swiper-pagination', clickable: true }, breakpoints: { 768: { slidesPerView: 3, spaceBetween: 30 } } });

            // アコーディオン機能
            document.querySelectorAll('.product-accordion-item').forEach(item => {
                const title = item.querySelector('.product-accordion-item__title');
                const content = item.querySelector('.product-accordion-item__content');
                content.style.height = '0';
                content.style.overflow = 'hidden';
                content.style.transition = 'height 0.3s ease-out';
                title.addEventListener('click', () => {
                    item.classList.toggle('is-closed');
                    content.style.height = item.classList.contains('is-closed') ? '0' : `${content.scrollHeight}px`;
                });
            });

            // 数量コントロール
            const quantityInput = document.querySelector('.product-quantity-controls .quantity-input');
            if(quantityInput) {
                document.querySelector('.quantity-minus').addEventListener('click', () => { let v = parseInt(quantityInput.value); if (v > 1) quantityInput.value = v - 1; });
                document.querySelector('.quantity-plus').addEventListener('click', () => { quantityInput.value = parseInt(quantityInput.value) + 1; });
            }

            // 画像切り替え
            const mainImg = document.querySelector('.product-gallery__main img');
            if(mainImg) {
                document.querySelectorAll('.product-gallery__thumbnails img').forEach(thumb => {
                    thumb.addEventListener('click', function () {
                        mainImg.src = this.src;
                        document.querySelectorAll('.product-gallery__thumbnails img').forEach(t => t.classList.remove('is-active'));
                        this.classList.add('is-active');
                    });
                });
            }

            // カート追加ボタン
            const addToCartBtn = document.getElementById('add-to-cart-btn');
            if (addToCartBtn) {
                addToCartBtn.addEventListener('click', function () {
                    if (!<?= json_encode($is_logged_in) ?>) {
                        alert('カート機能を利用するにはログインが必要です。');
                        window.location.href = 'login.php?redirect_url=' + encodeURIComponent(window.location.href);
                        return;
                    }
                    fetch('api/api_cart_manager.php', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify({ action: 'add', otumami_id: <?= json_encode($otumami_id) ?>, quantity: parseInt(quantityInput.value) })
                    }).then(r => r.json()).then(d => { if(typeof displayMessage === 'function') displayMessage(d.message, d.success ? 'success' : 'error'); else alert(d.message); });
                });
            }

            // お気に入りボタン
            const favoriteBtn = document.querySelector('.btn-favorite');
            if (favoriteBtn) {
                favoriteBtn.addEventListener('click', function () {
                    if (!<?= json_encode($is_logged_in) ?>) {
                        alert('お気に入り機能を利用するにはログインが必要です。');
                        window.location.href = 'login.php?redirect_url=' + encodeURIComponent(window.location.href);
                        return;
                    }
                    const otumamiId = this.dataset.otumamiId;
                    const isFavorited = this.classList.contains('is-favorited');
                    fetch('api/api_toggle_otumami_favorite.php', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify({ otumami_id: otumamiId, is_favorited: isFavorited })
                    }).then(r => r.json()).then(data => {
                        if (data.success) {
                            this.classList.toggle('is-favorited');
                            const icon = this.querySelector('i');
                            const text = this.querySelector('.favorite-text');
                            if (this.classList.contains('is-favorited')) {
                                icon.classList.replace('far', 'fas');
                                text.textContent = 'お気に入り済み';
                            } else {
                                icon.classList.replace('fas', 'far');
                                text.textContent = 'お気に入りに追加';
                            }
                        } else { alert(data.message || 'エラーが発生しました。'); }
                    });
                });
            }
        });
    </script>
</body>
</html>
