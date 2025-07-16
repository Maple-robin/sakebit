<?php
/*!
@file history.php
@brief 購入履歴ページ
@copyright Copyright (c) 2024 Your Name.
*/

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

require_once __DIR__ . '/common/contents_db.php';

/**
 * 【新規追加】中略機能付きのページネーションHTMLを生成する関数
 * @param int $current_page 現在のページ番号
 * @param int $total_pages 全ページ数
 * @param int $range 現在ページの前後いくつのリンクを表示するか
 * @return string 生成されたHTML
 */
function generate_pagination_links($current_page, $total_pages, $range = 1) {
    $html = '<nav class="pagination">';

    // 「前へ」ボタン
    if ($current_page > 1) {
        $html .= '<a href="?page=' . ($current_page - 1) . '" class="page-link prev">&laquo; 前へ</a>';
    }

    $show_ellipsis_start = false;
    $show_ellipsis_end = false;

    for ($i = 1; $i <= $total_pages; $i++) {
        // 表示するページの条件:
        // 1. 最初のページまたは最後のページ
        // 2. 現在のページの周辺（$rangeで指定した範囲）
        if ($i == 1 || $i == $total_pages || ($i >= $current_page - $range && $i <= $current_page + $range)) {
            if ($i == $current_page) {
                $html .= '<a href="?page=' . $i . '" class="page-link is-active">' . $i . '</a>';
            } else {
                $html .= '<a href="?page=' . $i . '" class="page-link">' . $i . '</a>';
            }
        } else {
            // 省略記号「...」の表示ロジック
            if ($i < $current_page && !$show_ellipsis_start) {
                $html .= '<span class="pagination-ellipsis">...</span>';
                $show_ellipsis_start = true;
            }
            if ($i > $current_page && !$show_ellipsis_end) {
                $html .= '<span class="pagination-ellipsis">...</span>';
                $show_ellipsis_end = true;
            }
        }
    }

    // 「次へ」ボタン
    if ($current_page < $total_pages) {
        $html .= '<a href="?page=' . ($current_page + 1) . '" class="page-link next">次へ &raquo;</a>';
    }

    $html .= '</nav>';
    return $html;
}

$user_id = $_SESSION['user_id'];
$debug = false;

// --- ページネーションの準備 ---
$items_per_page = 5;
$current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($current_page < 1) {
    $current_page = 1;
}
$offset = ($current_page - 1) * $items_per_page;

$orders_db = new corders();
$order_items_db = new corder_items();

$total_orders = $orders_db->get_orders_count_by_user_id($debug, $user_id);
$total_pages = ceil($total_orders / $items_per_page);

$orders = $orders_db->get_orders_by_user_id($debug, $user_id, $items_per_page, $offset);

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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="css/top.css">
    <link rel="stylesheet" href="css/history.css">
</head>
<body>
    <?php require_once 'header.php'; ?>

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
                            <!-- (注文カードの表示部分は変更なし) -->
                            <div class="history-order-card">
                                <div class="card-header">
                                    <div class="order-date">
                                        <i class="fas fa-calendar-alt"></i>
                                        <?php echo htmlspecialchars(date('Y年m月d日', strtotime($order['order_date'])), ENT_QUOTES, 'UTF-8'); ?>
                                    </div>
                                    <div class="order-meta">
                                        <span class="order-total">&yen;<?php echo htmlspecialchars(number_format($order['total_amount']), ENT_QUOTES, 'UTF-8'); ?></span>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <?php foreach ($order['items'] as $item): ?>
                                        <div class="history-item">
                                            <?php
                                            $image_path = !empty($item['image_path']) ? htmlspecialchars($item['image_path'], ENT_QUOTES, 'UTF-8') : 'img/no-image.png';
                                            $link_url = ($item['item_type'] === 'product') 
                                                        ? 'product.php?id=' . $item['product_id'] 
                                                        : 'otsumami.php?id=' . $item['otumami_id'];
                                            
                                            $item_status_class = '';
                                            $item_status_text = '';
                                            switch ($item['item_status']) {
                                                case 'shipped':
                                                    $item_status_class = 'status-shipped';
                                                    $item_status_text = '発送済み';
                                                    break;
                                                case 'delivered':
                                                    $item_status_class = 'status-delivered';
                                                    $item_status_text = '配達済み';
                                                    break;
                                                case 'cancelled':
                                                    $item_status_class = 'status-cancelled';
                                                    $item_status_text = 'キャンセル';
                                                    break;
                                                default:
                                                    $item_status_class = 'status-pending';
                                                    $item_status_text = '未対応';
                                                    break;
                                            }
                                            ?>
                                            <img src="<?php echo $image_path; ?>" alt="<?php echo htmlspecialchars($item['item_name'], ENT_QUOTES, 'UTF-8'); ?>" class="history-item__img">
                                            <div class="history-item__details">
                                                <h3 class="history-item__name">
                                                    <a href="<?php echo $link_url; ?>">
                                                        <?php echo htmlspecialchars($item['item_name'], ENT_QUOTES, 'UTF-8'); ?>
                                                    </a>
                                                </h3>
                                                <div class="history-item__meta">
                                                    <p class="history-item__price">
                                                        &yen;<?php echo htmlspecialchars(number_format($item['price_at_purchase']), ENT_QUOTES, 'UTF-8'); ?>
                                                    </p>
                                                    <p class="history-item__quantity">
                                                        数量: <?php echo htmlspecialchars($item['quantity'], ENT_QUOTES, 'UTF-8'); ?>
                                                    </p>
                                                    <div class="item-status <?php echo $item_status_class; ?>">
                                                        <span class="item-status-dot"></span>
                                                        <span class="item-status-text"><?php echo $item_status_text; ?></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <!-- ★★★ 新しい関数を呼び出してページネーションを表示 ★★★ -->
                    <?php if ($total_pages > 1): ?>
                        <?php echo generate_pagination_links($current_page, $total_pages); ?>
                    <?php endif; ?>

                <?php endif; ?>

                <button class="return-button" onclick="window.location.href='MyPage.php'">マイページへ戻る</button>
            </div>
        </section>
    </main>

    <?php require_once 'footer.php'; ?>
    <script src="js/script.js"></script>
</body>
</html>
