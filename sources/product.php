<?php
/*!
@file product.php
@brief 商品詳細ページ (DB連携・おつまみレコメンド機能付き)
@copyright Copyright (c) 2024 Your Name.
*/
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
    require_once 'header.php'; 
    
    if (!defined('DEBUG')) {
        define('DEBUG', true);
    }

    $product_id = $_GET['id'] ?? null;
    $product = null;
    $images = [];
    $tags = [];
    $is_favorited = false;
    $recommended_otumami = [];
    $recommend_title = '';

    $is_logged_in = isset($_SESSION['user_id']);
    $current_user_id = $_SESSION['user_id'] ?? null;

    if ($product_id && is_numeric($product_id)) {
        
        $product_info_obj = new cproduct_info();
        $favorites_obj = new cproduct_favorites();
        $product_views_obj = new cproduct_views();
        $order_items_db = new corder_items();
        $otumami_db = new cotumami();
        
        $product = $product_info_obj->get_full_product_details(DEBUG, $product_id);

        if ($product) {
            $product_views_obj->insert_product_view(DEBUG, $product_id);
            $images = $product['images'];
            $tags = !empty($product['tags']) ? explode(', ', $product['tags']) : [];
            
            if ($is_logged_in) {
                $is_favorited = $favorites_obj->is_favorited(DEBUG, $current_user_id, $product_id);
            }

            // --- 「相性のいいおつまみ」取得ロジック ---
            // 1. よく一緒に買われているおつまみを取得
            $recommended_otumami = $order_items_db->get_frequently_bought_with_otumami(DEBUG, $product_id, 3);
            $recommend_title = 'よく一緒に買われているおつまみ';

            $seen_ids = array_column($recommended_otumami, 'otumami_id');

            // 2. 3件に満たない場合、相性のいいカテゴリのおつまみで補完
            if (count($recommended_otumami) < 3 && isset($product['product_category'])) {
                $needed = 3 - count($recommended_otumami);
                // 既に取得済みのIDを除外して、不足分以上を取得
                $category_recs_raw = $otumami_db->get_top_selling_otumami_by_sake_category(DEBUG, $product['product_category'], $needed + count($seen_ids));
                
                foreach($category_recs_raw as $rec) {
                    if (!in_array($rec['otumami_id'], $seen_ids)) {
                        $recommended_otumami[] = $rec;
                        $seen_ids[] = $rec['otumami_id'];
                        if (count($recommended_otumami) >= 3) break;
                    }
                }
                // タイトルを更新（まだ「よく一緒に～」が一件もなければ）
                if (empty($bought_together)) {
                    $recommend_title = 'このお酒に合うおつまみのおすすめ';
                }
            }
            
            // 3.それでも3件に満たない場合、おつまみ全体の売上ランキングで補完
            if (count($recommended_otumami) < 3) {
                $needed = 3 - count($recommended_otumami);
                $overall_recs_raw = $otumami_db->get_top_selling_otumami(DEBUG, $needed + count($seen_ids));
                
                foreach($overall_recs_raw as $rec) {
                    if (!in_array($rec['otumami_id'], $seen_ids)) {
                        $recommended_otumami[] = $rec;
                        if (count($recommended_otumami) >= 3) break;
                    }
                }
                // タイトルを更新（まだおすすめが一件もなければ）
                 if (empty($bought_together) && empty($category_recs_raw)) {
                    $recommend_title = '人気のおつまみ';
                }
            }
        }
    }
    
    if ($product) {
        echo "<script>document.title = '" . htmlspecialchars($product['product_name'], ENT_QUOTES, 'UTF-8') . " | OUR BRAND';</script>";
    }
    ?>

    <main>
        <?php if ($product): ?>
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
                                    <button class="quantity-minus">-</button>
                                    <input type="number" class="quantity-input" value="1" min="1">
                                    <button class="quantity-plus">+</button>
                                </div>
                                <button id="add-to-cart-btn" class="btn-to-ec-site">カートに入れる</button>
                            </div>
                            <div class="product-info__favorite">
                                <button class="btn-favorite <?= $is_favorited ? 'is-favorited' : '' ?>" data-product-id="<?= $product_id ?>">
                                    <i class="<?= $is_favorited ? 'fas' : 'far' ?> fa-heart"></i>
                                    <span class="favorite-text"><?= $is_favorited ? 'お気に入り済み' : 'お気に入りに追加' ?></span>
                                </button>
                            </div>
                        </div>

                        <ul class="product-info__tags">
                            <?php if (!empty($tags)): ?>
                                <?php foreach ($tags as $tag): ?>
                                    <li>#<?= htmlspecialchars($tag) ?></li>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </ul>
                    </div>
                </div>

                <div class="product-accordion-item is-closed">
                    <h3 class="product-accordion-item__title product-description__title">商品の特徴<span class="accordion-icon"></span></h3>
                    <div class="product-accordion-item__content"><p><?= nl2br(htmlspecialchars($product['product_discription'] ?? '')) ?></p></div>
                </div>
                <div class="product-accordion-item is-closed">
                    <h3 class="product-accordion-item__title product-description__title">おすすめの飲み方<span class="accordion-icon"></span></h3>
                    <div class="product-accordion-item__content"><p><?= nl2br(htmlspecialchars($product['product_How'] ?? '')) ?></p></div>
                </div>

                <?php if (!empty($recommended_otumami)): ?>
                <div class="paired-snacks">
                    <h3 class="paired-snacks__title"><?= htmlspecialchars($recommend_title) ?></h3>
                    <div class="swiper paired-snacks-swiper">
                        <div class="swiper-wrapper">
                            <?php foreach ($recommended_otumami as $otumami): ?>
                            <div class="swiper-slide">
                                <div class="product-item">
                                    <a href="otsumami.php?id=<?= htmlspecialchars($otumami['otumami_id']) ?>">
                                        <div class="product-item__img-wrap">
                                            <img src="<?= htmlspecialchars($otumami['main_image_path'] ?? 'img/no-image.png') ?>" alt="<?= htmlspecialchars($otumami['otumami_name']) ?>">
                                        </div>
                                        <h3 class="product-item__name"><?= htmlspecialchars($otumami['otumami_name']) ?></h3>
                                        <p class="product-item__price">¥ <?= number_format($otumami['otumami_price']) ?><span>(税込)</span></p>
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
            <section class="product-not-found" style="text-align: center; padding: 50px 20px;">
                <p style="font-size: 1.8rem; margin-bottom: 30px;">指定された商品は見つかりませんでした。</p>
                <a href="products_list.php" class="btn-primary" style="display:inline-block; padding: 10px 30px; background-color:#A0522D; color:white; border-radius:5px;">商品一覧へ戻る</a>
            </section>
        <?php endif; ?>
    </main>

    <?php require_once 'footer.php'; ?>

    <script src="https://unpkg.com/swiper@8/swiper-bundle.min.js"></script>
    <script src="js/script.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const pairedSwiper = new Swiper('.paired-snacks-swiper', { slidesPerView: 1.5, spaceBetween: 20, centeredSlides: true, loop: document.querySelectorAll('.paired-snacks-swiper .swiper-slide').length > 1, pagination: { el: '.swiper-pagination', clickable: true }, breakpoints: { 768: { slidesPerView: 3, spaceBetween: 30 } } });
            const accordionItems = document.querySelectorAll('.product-accordion-item');
            accordionItems.forEach(item => {
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
            const quantityInput = document.querySelector('.product-quantity-controls .quantity-input');
            if(quantityInput) {
                document.querySelector('.quantity-minus').addEventListener('click', () => { let v = parseInt(quantityInput.value); if (v > 1) quantityInput.value = v - 1; });
                document.querySelector('.quantity-plus').addEventListener('click', () => { quantityInput.value = parseInt(quantityInput.value) + 1; });
            }
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
            const favoriteBtn = document.querySelector('.btn-favorite');
            if (favoriteBtn) {
                favoriteBtn.addEventListener('click', function () {
                    if (!<?= json_encode($is_logged_in) ?>) {
                        alert('お気に入り機能を利用するにはログインが必要です。');
                        window.location.href = 'login.php?redirect_url=' + encodeURIComponent(window.location.href);
                        return;
                    }
                    const productId = this.dataset.productId;
                    const isFavorited = this.classList.contains('is-favorited');
                    fetch('api/api_toggle_favorite.php', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify({ product_id: productId, is_favorited: isFavorited })
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
            const addToCartBtn = document.getElementById('add-to-cart-btn');
            if (addToCartBtn) {
                addToCartBtn.addEventListener('click', function() {
                    if (!<?= json_encode($is_logged_in) ?>) {
                        alert('カート機能を利用するにはログインが必要です。');
                        window.location.href = 'login.php?redirect_url=' + encodeURIComponent(window.location.href);
                        return;
                    }
                    fetch('api/api_cart_manager.php', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify({ action: 'add', product_id: <?= json_encode($product_id) ?>, quantity: parseInt(quantityInput.value) })
                    }).then(r => r.json()).then(d => { if(typeof displayMessage === 'function') displayMessage(d.message, d.success ? 'success' : 'error'); else alert(d.message); });
                });
            }
        });
    </script>
</body>
</html>
