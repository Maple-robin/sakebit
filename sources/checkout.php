<?php
/*!
@file checkout.php
@brief チェックアウトページ
@copyright Copyright (c) 2024 Your Name.
*/

// --- データベースからカート情報を読み込む ---
require_once __DIR__ . '/common/contents_db.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// ログインしていない場合はログインページへリダイレクト
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php?redirect_url=checkout.php');
    exit();
}

$current_user_id = $_SESSION['user_id'];
$debug_mode = defined('DEBUG') ? DEBUG : false;

// DBクラスのインスタンスを生成
$carts_db = new ccarts();
$cart_items_db = new ccart_items();

// ユーザーのカートIDを取得
$cart_id = $carts_db->get_or_create_cart_by_user_id($debug_mode, $current_user_id);

$cart_items = [];
if ($cart_id) {
    // カート内の商品情報を取得
    $cart_items = $cart_items_db->get_items_by_cart_id($debug_mode, $cart_id);
}

// カートが空の場合はカートページに戻す
if (empty($cart_items)) {
    header('Location: cart.php');
    exit();
}

// --- JavaScriptへデータを渡す準備 ---
$cart_items_for_js = [];
foreach ($cart_items as $item) {
    $cart_items_for_js[] = [
        'id'       => $item['product_id'],
        'name'     => $item['product_name'],
        'volume'   => $item['product_Contents'], // JSの'volume'にマッピング
        'price'    => (float)$item['cart_price_at_add'],
        'quantity' => (int)$item['cart_quantity'],
        'imageUrl' => $item['image_path'] ?? 'https://placehold.co/60x60?text=NoImage'
    ];
}

// JavaScriptに渡すためにJSON形式にエンコード
$cart_items_json = json_encode($cart_items_for_js, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT);

// 配送希望日・時間をクエリから取得
$delivery_date = $_GET['delivery_date'] ?? '';
$delivery_time = $_GET['delivery_time'] ?? '';

?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>チェックアウト | SAKE BIT</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@300;400;500;700&family=Zen+Old+Mincho:wght@400;500;700&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="css/checkout.css">
    
    <!-- PHPからカートデータをJSON形式で受け取る -->
    <script>
        const serverCartData = <?php echo $cart_items_json; ?>;
    </script>
</head>

<body>
    <header class="header">
        <div class="header__inner">
            <h1 class="header__logo">
                <a href="index.php">SAKE BIT</a>
            </h1>
        </div>
    </header>

    <main class="checkout-main">
        <div class="checkout-container">
            <!-- 注文概要セクション (モバイルでは上部に表示) -->
            <section class="order-summary-section mobile-only">
                <button class="order-summary-toggle">
                    <span class="toggle-text">注文サマリー</span>
                    <i class="fas fa-chevron-down toggle-icon"></i>
                    <span class="total-price-mobile" id="summary-total-mobile"></span>
                </button>
                <div class="order-summary-content hidden">
                    <h3 class="section-title-alt">ご注文内容</h3>
                    <div class="product-list" id="order-summary-products">
                        <!-- 商品はJavaScriptで動的に追加されます -->
                    </div>

                    <div class="discount-code">
                        <input type="text" id="discount-input" placeholder="クーポンコード">
                        <button id="apply-discount">適用</button>
                    </div>

                    <div class="price-breakdown">
                        <div class="price-item">
                            <span>小計</span>
                            <span id="summary-subtotal-mobile-content">¥ 0</span>
                        </div>
                        <div class="price-item">
                            <span>送料</span>
                            <span id="summary-shipping-mobile-content">お届け先住所を入力する</span>
                        </div>
                        <div class="price-item total-price-item">
                            <span>合計</span>
                            <span id="summary-total-mobile-content-final">¥ 0 JPY</span>
                        </div>
                        <p class="tax-info-summary" id="summary-tax-info">¥ 0 の税金を含む</p>
                    </div>
                </div>
            </section>

            <!-- 決済フォームセクション -->
            <section class="checkout-form-section">
                <div class="express-checkout">
                    <h3>かんたん決済</h3>
                    <div class="express-buttons">
                        <button class="express-button shop-pay">Shop Pay</button>
                        <button class="express-button paypal">PayPal</button>
                        <button class="express-button gpay">G Pay</button>
                    </div>
                    <div class="divider">
                        <span class="divider-text">または</span>
                    </div>
                </div>

                <div class="contact-info">
                    <div class="section-header-with-link">
                        <h3>連絡先</h3>
                        <a href="#" class="login-link">ログイン</a>
                    </div>
                    <div class="form-group">
                        <label for="email" class="sr-only">メールアドレス</label>
                        <input type="email" id="email" placeholder="メールアドレス" required>
                    </div>
                    <div class="checkbox-group">
                        <input type="checkbox" id="email-updates">
                        <label for="email-updates">お得な情報をメールで受け取る</label>
                    </div>
                </div>

                <div class="delivery-address">
                    <h3>お届け先</h3>
                    <div class="form-group">
                        <label for="country" class="sr-only">国／地域</label>
                        <select id="country" required>
                            <option value="">国／地域</option>
                            <option value="Japan" selected>日本</option>
                        </select>
                    </div>
                    <div class="form-row">
                        <div class="form-group half-width">
                            <label for="last-name" class="sr-only">姓</label>
                            <input type="text" id="last-name" placeholder="姓" required>
                        </div>
                        <div class="form-group half-width">
                            <label for="first-name" class="sr-only">名</label>
                            <input type="text" id="first-name" placeholder="名" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="company" class="sr-only">会社 (任意)</label>
                        <input type="text" id="company" placeholder="会社 (任意)">
                    </div>
                    <div class="form-group">
                        <label for="zip-code" class="sr-only">郵便番号</label>
                        <input type="text" id="zip-code" placeholder="郵便番号" required>
                    </div>
                    <div class="form-group">
                        <label for="prefecture" class="sr-only">都道府県</label>
                        <select id="prefecture" required>
                            <option value="">都道府県</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="city" class="sr-only">市区町村</label>
                        <input type="text" id="city" placeholder="市区町村" required>
                    </div>
                    <div class="form-group">
                        <label for="address" class="sr-only">住所</label>
                        <input type="text" id="address" placeholder="住所" required>
                    </div>
                    <div class="form-group">
                        <label for="apartment" class="sr-only">建物名、部屋番号など (任意)</label>
                        <input type="text" id="apartment" placeholder="建物名、部屋番号など (任意)">
                    </div>
                    <div class="form-group">
                        <label for="phone" class="sr-only">携帯電話</label>
                        <input type="tel" id="phone" placeholder="携帯電話" required>
                        <div class="help-icon">
                            <i class="fas fa-question-circle"></i>
                        </div>
                    </div>
                </div>

                <div class="shipping-method">
                    <h3>配送方法</h3>
                    <div id="shipping-options-container">
                       <p class="shipping-info-placeholder">利用可能な配送方法は配送先住所入力後に表示されます。</p>
                    </div>
                </div>

                <div class="payment-method">
                    <h3>お支払い</h3>
                    <p class="payment-note">すべての取引は安全で、暗号化されています。</p>
                    <div class="payment-card-form">
                        <div class="payment-option-header">
                            <input type="radio" id="credit-card-radio" name="payment-option" value="credit-card" checked>
                            <label for="credit-card-radio">クレジットカード</label>
                            <div class="card-icons">
                                <i class="fab fa-cc-visa"></i>
                                <i class="fab fa-cc-mastercard"></i>
                                <i class="fab fa-cc-amex"></i>
                                <span>+3</span>
                            </div>
                        </div>
                        <div class="card-form-details">
                            <div class="form-group">
                                <label for="card-number" class="sr-only">カード番号</label>
                                <input type="text" id="card-number" placeholder="カード番号" required>
                                <i class="fas fa-lock input-icon-right"></i>
                            </div>
                            <div class="form-row">
                                <div class="form-group half-width">
                                    <label for="expiry-date" class="sr-only">有効期限（月/年）</label>
                                    <input type="text" id="expiry-date" placeholder="有効期限（月/年）" required>
                                </div>
                                <div class="form-group half-width">
                                    <label for="cvc" class="sr-only">セキュリティコード</label>
                                    <input type="text" id="cvc" placeholder="セキュリティコード" required>
                                    <i class="fas fa-question-circle input-icon-right"></i>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="card-name" class="sr-only">カードの名義人</label>
                                <input type="text" id="card-name" placeholder="カードの名義人" required>
                            </div>
                        </div>
                    </div>
                    <div class="payment-option-paypal">
                        <input type="radio" id="paypal-radio" name="payment-option" value="paypal" style="display:none;">
                        <label for="paypal-radio" class="paypal-label-container">
                           <img src="img/paypal.png" alt="PayPal" class="paypal-logo">
                        </label>
                    </div>
                </div>

                <div class="billing-address-section">
                    <h3>請求先住所</h3>
                    <div class="form-group">
                        <div class="radio-group">
                            <input type="radio" id="billing-same" name="billing-address-option" value="same" checked>
                            <label for="billing-same">お届け先住所と同じ</label>
                        </div>
                        <div class="radio-group">
                             <input type="radio" id="billing-different" name="billing-address-option" value="different">
                             <label for="billing-different">違う請求先住所を使う</label>
                        </div>
                    </div>
                    <div class="billing-address-fields hidden">
                        <div class="form-group">
                            <label for="billing-country" class="sr-only">国／地域</label>
                            <select id="billing-country">
                                <option value="Japan" selected>日本</option>
                            </select>
                        </div>
                        <div class="form-row">
                            <div class="form-group half-width">
                                <label for="billing-last-name" class="sr-only">姓</label>
                                <input type="text" id="billing-last-name" placeholder="姓">
                            </div>
                            <div class="form-group half-width">
                                <label for="billing-first-name" class="sr-only">名</label>
                                <input type="text" id="billing-first-name" placeholder="名">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="billing-zip-code" class="sr-only">郵便番号</label>
                            <input type="text" id="billing-zip-code" placeholder="郵便番号">
                        </div>
                        <div class="form-group">
                            <label for="billing-prefecture" class="sr-only">都道府県</label>
                            <select id="billing-prefecture">
                                <option value="">都道府県</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="billing-city" class="sr-only">市区町村</label>
                            <input type="text" id="billing-city" placeholder="市区町村">
                        </div>
                        <div class="form-group">
                            <label for="billing-address" class="sr-only">住所</label>
                            <input type="text" id="billing-address" placeholder="住所">
                        </div>
                        <div class="form-group">
                            <label for="billing-apartment" class="sr-only">建物名、部屋番号など (任意)</label>
                            <input type="text" id="billing-apartment" placeholder="建物名、部屋番号など (任意)">
                        </div>
                    </div>
                </div>

                <div class="checkout-actions">
                    <a href="cart.php" class="link-back-to-cart"><i class="fas fa-chevron-left"></i> 買い物かごに戻る</a>
                    <button type="submit" class="button-primary" id="place-order-button">今すぐ支払う</button>
                </div>

                <form id="checkout-form">
                    <input type="hidden" id="delivery-date-hidden" name="delivery_date" value="<?= htmlspecialchars($delivery_date) ?>">
                    <input type="hidden" id="delivery-time-hidden" name="delivery_time" value="<?= htmlspecialchars($delivery_time) ?>">
                </form>
            </section>

            <!-- 注文概要セクション (PCでは右側に表示) -->
            <section class="order-summary-section pc-only">
                <h3 class="section-title-alt">ご注文内容</h3>
                <div class="product-list" id="order-summary-products-pc">
                    <!-- 商品はJavaScriptで動的に追加されます -->
                </div>

                <div class="discount-code">
                    <input type="text" id="discount-input-pc" placeholder="クーポンコード">
                    <button id="apply-discount-pc">適用</button>
                </div>

                <div class="price-breakdown">
                    <div class="price-item">
                        <span>小計</span>
                        <span id="summary-subtotal-pc"></span>
                    </div>
                    <div class="price-item">
                        <span>送料</span>
                        <span id="summary-shipping-pc"></span>
                    </div>
                    <div class="price-item total-price-item">
                        <span>合計</span>
                        <span id="summary-total-pc"></span>
                    </div>
                    <p class="tax-info-summary" id="summary-tax-info-pc"></p>
                </div>
            </section>
        </div>
    </main>

    <?php require_once 'footer.php'; ?>

    <script src="js/checkout.js"></script>
    <script src="js/script.js"></script>
</body>

</html>
