<?php
/*!
@file history.php
@brief 購入履歴ページ
@copyright Copyright (c) 2024 Your Name.
*/

// session_start()を、全ての処理の一番最初に呼び出す
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// ログイン状態を確認
if (!isset($_SESSION['user_id'])) {
    // ログインしていなければ、ログインページにリダイレクト
    header('Location: login.php');
    exit;
}

// DB接続ファイルを読み込む
require_once __DIR__ . '/common/contents_db.php';


// ログインユーザーのIDを取得
$user_id = $_SESSION['user_id'];
$debug = false; // デバッグモード（必要に応じてtrueに変更）

// データベース操作クラスのインスタンスを作成
$orders_db = new corders();
$order_items_db = new corder_items();

// ユーザーの注文履歴を取得
$orders = $orders_db->get_orders_by_user_id($debug, $user_id);

// 各注文に紐づく商品情報を取得して、注文データに格納する
foreach ($orders as $key => $order) {
    $orders[$key]['items'] = $order_items_db->get_items_by_order_id($debug, $order['order_id']);
}

?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>購入履歴 | OUR BRAND</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@300;400;500;700&family=Zen+Old+Mincho:wght@400;500;700&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/swiper@8/swiper-bundle.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="css/top.css">
    <link rel="stylesheet" href="css/history.css">
</head>

<body>
    <?php
    // 共通ヘッダーを読み込む
    require_once 'header.php';
    ?>

    <main>
        <section class="history">
            <div class="history__inner">
                <h2 class="section-title">
                    <span class="en">PURCHASE HISTORY</span>
                    <span class="ja">( 購入履歴 )</span>
                </h2>

                <?php if (empty($orders)): ?>
                    <p class="no-history">購入履歴はまだありません。</p>
                <?php else: ?>
                    <div class="history-list">
                        <?php foreach ($orders as $order): ?>
                            <div class="history-order-card">
                                <div class="card-header">
                                    <div class="order-date">
                                        <i class="fas fa-calendar-alt"></i>
                                        <?php echo htmlspecialchars(date('Y年m月d日', strtotime($order['order_date'])), ENT_QUOTES, 'UTF-8'); ?>
                                    </div>
                                    <div class="order-meta">
                                        <span class="order-total">合計: &yen;<?php echo htmlspecialchars(number_format($order['total_amount']), ENT_QUOTES, 'UTF-8'); ?></span>
                                        <?php
                                        // 注文状況に応じてCSSクラスを切り替える
                                        $status_class = '';
                                        switch ($order['order_status']) {
                                            case 'shipped':
                                                $status_class = 'status-shipped';
                                                break;
                                            case 'delivered':
                                                $status_class = 'status-delivered';
                                                break;
                                            case 'pending':
                                                $status_class = 'status-pending';
                                                break;
                                            case 'cancelled':
                                                $status_class = 'status-cancelled';
                                                break;
                                        }
                                        ?>
                                        <span class="status-badge <?php echo $status_class; ?>"><?php echo htmlspecialchars($order['order_status'], ENT_QUOTES, 'UTF-8'); ?></span>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <?php foreach ($order['items'] as $item): ?>
                                        <div class="history-item">
                                            <?php
                                            $image_path = !empty($item['image_path']) ? htmlspecialchars($item['image_path'], ENT_QUOTES, 'UTF-8') : 'img/no-image.png';
                                            
                                            // ★★★ ここを修正 ★★★
                                            // リンク先を otumami.php から otsumami.php に修正
                                            $link_url = ($item['item_type'] === 'product') 
                                                        ? 'product.php?id=' . $item['product_id'] 
                                                        : 'otsumami.php?id=' . $item['otumami_id'];
                                            ?>
                                            <img src="<?php echo $image_path; ?>" alt="<?php echo htmlspecialchars($item['item_name'], ENT_QUOTES, 'UTF-8'); ?>" class="history-item__img">
                                            <div class="history-item__details">
                                                <h3 class="history-item__name">
                                                    <a href="<?php echo $link_url; ?>">
                                                        <?php echo htmlspecialchars($item['item_name'], ENT_QUOTES, 'UTF-8'); ?>
                                                    </a>
                                                </h3>
                                                <p class="history-item__price">
                                                    &yen;<?php echo htmlspecialchars(number_format($item['price_at_purchase']), ENT_QUOTES, 'UTF-8'); ?> (購入時単価)
                                                </p>
                                                <p class="history-item__quantity">
                                                    数量: <?php echo htmlspecialchars($item['quantity'], ENT_QUOTES, 'UTF-8'); ?>
                                                </p>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <button class="return-button" onclick="window.location.href='MyPage.php'">マイページへ戻る</button>
            </div>
        </section>
    </main>

    <?php
    // 共通フッターを読み込む
    require_once 'footer.php';
    ?>

    <script src="https://unpkg.com/swiper@8/swiper-bundle.min.js"></script>
    <script src="js/script.js"></script>
</body>

</html>
