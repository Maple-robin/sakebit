<?php
/*!
@file cart.php
@brief 買い物かごページ
@copyright Copyright (c) 2024 Your Name.
*/

// ログインチェックとDB処理をHTML出力の前に行う
require_once __DIR__ . '/common/contents_db.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// ログインしていない場合はログインページへリダイレクト
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php?redirect_url=cart.php');
    exit();
}

$current_user_id = $_SESSION['user_id'];
$debug_mode = defined('DEBUG') ? DEBUG : false;

// DBクラスのインスタンスを生成
$carts_db = new ccarts();
$cart_items_db = new ccart_items();

// ユーザーのカートIDを取得（なければ作成）
$cart_id = $carts_db->get_or_create_cart_by_user_id($debug_mode, $current_user_id);
$_SESSION['cart_id'] = $cart_id;

$cart_items = [];
$total_price = 0;

if ($cart_id) {
    // カート内の商品情報を取得
    $cart_items = $cart_items_db->get_items_by_cart_id($debug_mode, $cart_id);
    // 合計金額を計算
    foreach ($cart_items as $item) {
        $total_price += $item['cart_price_at_add'] * $item['cart_quantity'];
    }
}
?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>買い物かご | SAKE BIT</title>
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

                        <?php if (empty($cart_items)): ?>
                            <p class="cart-empty-message">カートに商品がありません。</p>
                        <?php else: ?>
                            <?php foreach ($cart_items as $item): ?>
                                <!-- ★★★ data-item-id を修正 ★★★ -->
                                <div class="cart-item" data-item-id="<?= $item['cart_item_id'] ?>">
                                    <div class="cart-item__image">
                                        <img src="<?= htmlspecialchars($item['image_path'] ?? 'https://placehold.co/100x100?text=NoImage') ?>" alt="<?= htmlspecialchars($item['product_name']) ?>">
                                    </div>
                                    <div class="cart-item__details">
                                        <h3 class="cart-item__name"><?= htmlspecialchars($item['product_name']) ?></h3>
                                        <p class="cart-item__size"><?= htmlspecialchars($item['product_Contents']) ?></p>
                                        <p class="cart-item__price">¥ <?= number_format($item['cart_price_at_add']) ?></p>
                                        <div class="cart-item__quantity-controls">
                                            <!-- ★★★ 各ボタンと入力欄に data-id を設定 ★★★ -->
                                            <button class="quantity-minus" data-id="<?= $item['cart_item_id'] ?>">-</button>
                                            <input type="number" class="quantity-input" value="<?= $item['cart_quantity'] ?>" min="1" data-id="<?= $item['cart_item_id'] ?>">
                                            <button class="quantity-plus" data-id="<?= $item['cart_item_id'] ?>">+</button>
                                        </div>
                                    </div>
                                    <button class="cart-item__remove" data-id="<?= $item['cart_item_id'] ?>">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>

                    </div>

                    <div class="cart-summary">
                        <p class="cart-summary__shipping-info">税込 5,200 円 以上 購入 で 送料 無料 に なり ます。</p>
                        <div class="cart-summary__subtotal">
                            <p>小計</p>
                            <p class="subtotal-price" id="total-price">¥ <?= number_format($total_price) ?><span> JPY</span></p>
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

                        <a href="checkout.php" class="btn-checkout">チェックアウト</a>
                    </div>
                </div>

            </div>
        </section>

    </main>

    <?php
    require_once 'footer.php';
    ?>

    <script src="js/script.js"></script>
    <script src="js/cart.js"></script>
</body>

</html>