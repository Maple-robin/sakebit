<?php
/*!
@file otsumami.php
@brief おつまみ詳細ページ
@copyright Copyright (c) 2024 Your Name.
*/

// ★注意: DB接続やセッション開始は header.php で行われるため、ここでの処理は不要です。
// session_start();
// require_once __DIR__ . '/common/contents_db.php';

// ここにおつまみページ固有のPHPロジックがあれば記述します。
// (例: GETパラメータからIDを取得し、おつまみ情報をDBから取得する処理など)
?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>おつまみ名 | OUR BRAND</title>
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
    require_once 'header.php'; 
    ?>

    <main>
        <div class="breadcrumb">
            <div class="breadcrumb__inner common-inner">
                <a href="index.php">HOME</a> &gt; <a href="otumami.php">おつまみ一覧</a> &gt; ビーフジャーキー
            </div>
        </div>

        <section class="product-detail-section">
            <div class="product-detail-section__inner common-inner">
                <div class="product-detail-content">
                    <div class="product-gallery">
                        <div class="product-gallery__main">
                            <img src="img/AdobeStock_86128982_Preview仮.jpeg" alt="サムネイル1">
                        </div>
                        <div class="product-gallery__thumbnails">
                            <img src="img/AdobeStock_86128982_Preview仮.jpeg" alt="サムネイル1" class="is-active">
                            <img src="img/AdobeStock_158085442_Preview仮.jpeg" alt="サムネイル2">
                            <img src="img/AdobeStock_680764475_Preview仮.jpeg" alt="サムネイル3">
                            <img src="img/AdobeStock_758820275_Preview_Editorial_Use_Only仮.jpeg" alt="サムネイル4">
                        </div>
                    </div>

                    <div class="product-info">
                        <h2 class="product-info__name">ビーフジャーキー</h2>
                        <p class="product-info__type">ビール・ウイスキー</p>
                        <p class="product-info__catchcopy">噛むほどに旨味が広がる、王道おつまみ！<br>お酒との相性抜群です。</p>
                        <p class="product-info__price">¥ 780<span>(税込)</span></p>
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
                            <li>#タグ1</li>
                            <li>#タグ2</li>
                            <li>#タグ3</li>
                        </ul>
                    </div>
                </div>

                <div class="product-accordion-item is-closed">
                    <h3 class="product-accordion-item__title product-description__title">おつまみの説明<span
                            class="accordion-icon"></span></h3>
                    <div class="product-accordion-item__content">
                        <p>
                            ここに、おつまみの詳しい説明が入ります。
                            例えば、どのような素材を使っているか、味の特徴、食感などについて記述します。
                        </p>
                        <p>
                            このおつまみが誕生した背景や、おすすめの食べ方、保存方法など、
                            お客様が興味を持つような情報を盛り込むことができます。
                            データベースに説明文用のカラム（例: otumami_description）があれば、そこに記述された内容をここに表示します。
                        </p>
                    </div>
                </div>

                <div class="paired-snacks">
                    <h3 class="paired-snacks__title">このおつまみに合うお酒</h3>
                    <div class="swiper paired-snacks-swiper">
                        <div class="swiper-wrapper">
                            <div class="swiper-slide">
                                <div class="product-item">
                                    <a href="product.php">
                                        <div class="product-item__img-wrap">
                                            <img src="./img/sake.png" alt="おすすめのお酒1">
                                        </div>
                                        <h3 class="product-item__name">純米大吟醸 麗し乃雫</h3>
                                        <p class="product-item__price">¥ 5,800<span>(税込)</span></p>
                                        <p class="product-item__tag">#日本酒 #華やか #ギフト</p>
                                    </a>
                                </div>
                            </div>
                            <div class="swiper-slide">
                                <div class="product-item">
                                    <a href="product.php">
                                        <div class="product-item__img-wrap">
                                            <img src="./img/beer.png" alt="おすすめのお酒2">
                                        </div>
                                        <h3 class="product-item__name">クラフトビール 泡沫</h3>
                                        <p class="product-item__price">¥ 850<span>(税込)</span></p>
                                        <p class="product-item__tag">#ビール #フルーティー #爽快</p>
                                    </a>
                                </div>
                            </div>
                            <div class="swiper-slide">
                                <div class="product-item">
                                    <a href="product.php">
                                        <div class="product-item__img-wrap">
                                            <img src="./img/wine.png" alt="おすすめのお酒3">
                                        </div>
                                        <h3 class="product-item__name">赤ワイン 夕焼けの丘</h3>
                                        <p class="product-item__price">¥ 3,200<span>(税込)</span></p>
                                        <p class="product-item__tag">#ワイン #ミディアムボディ #香り豊か</p>
                                    </a>
                                </div>
                            </div>
                            <div class="swiper-slide">
                                <div class="product-item">
                                    <a href="product.php">
                                        <div class="product-item__img-wrap">
                                            <img src="./img/whiskey.png" alt="おすすめのお酒4">
                                        </div>
                                        <h3 class="product-item__name">シングルモルト 琥珀の夢</h3>
                                        <p class="product-item__price">¥ 7,500<span>(税込)</span></p>
                                        <p class="product-item__tag">#ウイスキー #スモーキー #長期熟成</p>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="swiper-pagination"></div>
                    </div>
                </div>

            </div>
        </section>

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

            // 「カートに入れる」ボタン
            const addToCartBtn = document.getElementById('add-to-cart-btn');
            if (addToCartBtn) {
                addToCartBtn.addEventListener('click', function () {
                    const selectedQuantity = parseInt(quantityInput.value);
                    console.log(`おつまみID: を${selectedQuantity}個カートに追加しました！`);
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

            // お気に入りボタン
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
