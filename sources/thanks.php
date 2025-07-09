<?php
/*!
@file thanks.php
@brief 購入完了ページ
@copyright Copyright (c) 2024 Your Name.
*/

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// ログインしていない場合は、トップページなどにリダイレクトしても良い
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

require_once __DIR__ . '/common/contents_db.php';

// URLから注文IDを取得
$order_id = $_GET['order_id'] ?? null;
$order = null;
$order_items = [];

if ($order_id && is_numeric($order_id)) {
    $debug = false;
    $orders_db = new corders();
    $order_items_db = new corder_items();
    
    // 注文情報を取得
    $order = $orders_db->get_tgt($debug, (int)$order_id);

    // ログイン中のユーザーの注文かどうかもチェック（セキュリティ向上）
    if ($order && $order['user_id'] == $_SESSION['user_id']) {
        // 注文に紐づく商品リストを取得
        $order_items = $order_items_db->get_items_by_order_id($debug, $order['order_id']);
    } else {
        // 他のユーザーの注文IDが指定された場合は、情報を表示しない
        $order = null;
    }
}

?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ご購入ありがとうございました | OUR BRAND</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@300;400;500;700&family=Zen+Old+Mincho:wght@400;500;700&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="css/top.css">
    <link rel="stylesheet" href="css/thanks.css">
</head>

<body>
    <?php require_once 'header.php'; ?>

    <main>
        <section class="thank-you-section">
            <div class="thank-you-section__inner">
                <div class="thank-you-card">
                    <div class="thank-you-icon">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <h2 class="thank-you-title">ご注文ありがとうございました</h2>
                    
                    <?php if ($order): ?>
                        <p class="thank-you-message">
                            お客様のご注文が完了いたしました。<br>
                            ご注文内容の確認メールを送信しましたので、ご確認ください。
                        </p>
                        <div class="order-confirmation-box">
                            <h3 class="confirmation-title">ご注文内容の確認</h3>
                            <div class="order-summary">
                                <p><strong>注文番号:</strong> <span><?php echo htmlspecialchars($order['order_id']); ?></span></p>
                                <p><strong>注文日時:</strong> <span><?php echo htmlspecialchars(date('Y年m月d日 H:i', strtotime($order['order_date']))); ?></span></p>
                                <p><strong>お届け先:</strong> <span><?php echo htmlspecialchars($order['shipping_address']); ?></span></p>
                            </div>

                            <div class="order-item-list">
                                <?php foreach ($order_items as $item): ?>
                                    <div class="order-item">
                                        <div class="order-item__image">
                                            <img src="<?php echo htmlspecialchars($item['image_path'] ?? 'img/no-image.png'); ?>" alt="<?php echo htmlspecialchars($item['item_name']); ?>">
                                        </div>
                                        <div class="order-item__details">
                                            <p class="order-item__name"><?php echo htmlspecialchars($item['item_name']); ?></p>
                                            <p class="order-item__info">
                                                <span>&yen;<?php echo number_format($item['price_at_purchase']); ?></span>
                                                <span>&times; <?php echo htmlspecialchars($item['quantity']); ?></span>
                                            </p>
                                        </div>
                                        <div class="order-item__price">
                                            &yen;<?php echo number_format($item['price_at_purchase'] * $item['quantity']); ?>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>

                            <div class="order-total">
                                <p><strong>合計金額:</strong> <span>&yen;<?php echo number_format($order['total_amount']); ?></span></p>
                                <p class="tax-note">(消費税込み)</p>
                            </div>
                        </div>
                    <?php else: ?>
                        <p class="thank-you-message">
                            ご注文が正常に完了いたしました。<br>
                            詳細は、購入履歴またはご注文内容の確認メールをご確認ください。
                        </p>
                    <?php endif; ?>

                    <div class="thank-you-links">
                        <a href="products_list.php" class="btn-primary">お買い物を続ける</a>
                        <a href="history.php" class="btn-secondary">購入履歴を見る</a>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <?php require_once 'footer.php'; ?>
    <script src="js/script.js"></script>
</body>

</html>
