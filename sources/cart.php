<?php
/*!
@file cart.php
@brief 買い物かごページ
@copyright Copyright (c) 2024 Your Name.
*/

// ★注意: DB接続やセッション開始は header.php で行われるため、ここでの処理は不要です。
// session_start();
// require_once __DIR__ . '/common/contents_db.php';

// ここに買い物かごページ固有のPHPロジックがあれば記述します。
// (例: カート内の商品情報をDBから取得する処理など)

?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>買い物かご | OUR BRAND</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@300;400;500;700&family=Zen+Old+Mincho:wght@400;500;700&display=swap"
        rel="stylesheet">

    <link rel="stylesheet" href="https://unpkg.com/swiper@8/swiper-bundle.min.css" />
    <link rel="stylesheet" href="css/top.css">
    <link rel="stylesheet" href="css/cart.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>

<body>

    <?php 
    // 共通ヘッダーを読み込む
    require_once 'header.php'; 
    ?>

    <main>
        <section class="cart-section">
            <div class="cart-section__inner common-inner">
                <h2 class="section-title">
                    <span class="en">SHOPPING CART</span>
                    <span class="ja">(買い物かご)</span>
                </h2>

                <div class="cart-links-top"> <a href="products_list.php" class="btn-continue-shopping-top">
                        <i class="fas fa-chevron-left"></i> お買い物を続ける
                    </a>
                </div>

                <div class="cart-main-container">
                    <div class="cart-items" id="cart-items-container">
                        <!-- カート内の商品はJavaScriptまたはサーバーサイドのPHPで動的に生成される想定 -->
                        <div class="cart-item">
                            <div class="cart-item__image">
                                <img src="img/gingerale.png" alt="イセ ポメロモ ヒート">
                            </div>
                            <div class="cart-item__details">
                                <h3 class="cart-item__name">【販売再開】イセ ポメロモ ヒート</h3>
                                <p class="cart-item__size">200ml</p>
                                <p class="cart-item__price">¥ 1,750</p>
                                <div class="cart-item__quantity-controls">
                                    <button class="quantity-minus" data-id="product-1">-</button>
                                    <input type="number" class="quantity-input" value="1" min="1" data-id="product-1">
                                    <button class="quantity-plus" data-id="product-1">+</button>
                                </div>
                            </div>
                            <button class="cart-item__remove" data-id="product-1">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </div>
                        <div class="cart-item">
                            <div class="cart-item__image">
                                <img src="img/berry.png" alt="セレンディピティ・レモン">
                            </div>
                            <div class="cart-item__details">
                                <h3 class="cart-item__name">セレンディピティ・レモン</h3>
                                <p class="cart-item__size">300ml</p>
                                <p class="cart-item__price">¥ 2,500</p>
                                <div class="cart-item__quantity-controls">
                                    <button class="quantity-minus" data-id="product-2">-</button>
                                    <input type="number" class="quantity-input" value="1" min="1" data-id="product-2">
                                    <button class="quantity-plus" data-id="product-2">+</button>
                                </div>
                            </div>
                            <button class="cart-item__remove" data-id="product-2">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </div>
                    </div>

                    <div class="cart-summary">
                        <p class="cart-summary__shipping-info">税込 5,200 円 以上 購入 で 送料 無料 に なり ます。</p>
                        <div class="cart-summary__subtotal">
                            <p>小計</p>
                            <p class="subtotal-price" id="total-price">¥ 4,250<span> JPY</span></p>
                        </div>
                        <p class="cart-summary__tax-info">送料と税金はチェックアウト時に計算されます</p>

                        <div class="cart-delivery-options">
                            <div class="delivery-option">
                                <label for="delivery-date">配送希望日</label>
                                <input type="date" id="delivery-date" class="delivery-select">
                            </div>
                            <div class="delivery-option">
                                <label for="delivery-time">配送希望時間</label>
                                <select id="delivery-time" class="delivery-select">
                                    <option value="none">希望しない</option>
                                    <option value="am">午前中</option>
                                    <option value="12-14">12時〜14時</option>
                                    <option value="14-16">14時〜16時</option>
                                    <option value="16-18">16時〜18時</option>
                                    <option value="18-20">18時〜20時</option>
                                    <option value="18-21">18時〜21時</option>
                                    <option value="19-21">19時〜21時</option>
                                </select>
                            </div>
                        </div>

                        <button class="btn-checkout">チェックアウト</button>
                    </div>
                </div>

            </div>
        </section>

    </main>

    <?php 
    // 共通フッターを読み込む
    require_once 'footer.php'; 
    ?>

    <script src="js/script.js"></script>
    <script src="js/cart.js"></script>
</body>

</html>
