<?php
// ログインチェックとセッション開始
require_once 'auth_check.php';
// データベース操作クラス
require_once '../common/contents_db.php';

// --- 絞り込み条件の取得 ---
$filter_status = $_GET['status'] ?? '';
$filter_time = $_GET['time'] ?? '';
$is_initial_load = empty($_GET);
if ($is_initial_load) {
    $today = date('Y-m-d');
    $filter_date_from = $today;
    $filter_date_to = $today;
} else {
    $filter_date_from = $_GET['date_from'] ?? '';
    $filter_date_to = $_GET['date_to'] ?? '';
}

// --- 注文データを取得 ---
$orders_db = new corders();
$orders = $orders_db->get_orders_for_client($debug, $client_id, $filter_status, $filter_date_from, $filter_date_to, $filter_time);

?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>注文管理 | OUR BRAND 管理者画面</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="../clientcss/client.css">
    <link rel="stylesheet" href="../clientcss/client_orders.css">
</head>
<body class="admin-page-layout">
    <header class="admin-header">
        <div class="admin-header__inner">
            <h1 class="admin-header__logo"><a href="client_top.php">OUR BRAND 管理者画面</a></h1>
            <nav class="admin-header__nav">
                <ul class="admin-nav__list">
                    <li><a href="client_top.php">商品一覧</a></li>
                    <li><a href="client_add_product.php">お酒追加</a></li>
                    <li><a href="client_orders.php" class="is-active">注文管理</a></li>
                    <li><a href="client_preview.php">プレビュー</a></li>
                    <li><a href="client_analytics.php">情報確認</a></li>
                </ul>
                <div class="admin-header__actions">
                    <a href="client_login.php" class="admin-header__logout"><i class="fas fa-sign-out-alt"></i> ログアウト</a>
                </div>
            </nav>
        </div>
    </header>

    <main class="admin-main">
        <div class="admin-container">
            <h2 class="admin-section-title">注文管理</h2>

            <!-- 絞り込みフォーム (変更なし) -->
            <div class="filter-wrapper <?php if ($is_initial_load || !empty($_GET)) echo 'is-open'; ?>">
                <button type="button" class="filter-toggle-btn">絞り込み条件 <i class="fas fa-chevron-down"></i></button>
                <div class="filter-content">
                    <form method="GET" action="client_orders.php" class="filter-box">
                        <div class="filter-group">
                            <label for="status">商品ステータス</label>
                            <select id="status" name="status">
                                <option value="">すべて</option>
                                <option value="pending" <?php if ($filter_status == 'pending') echo 'selected'; ?>>未対応</option>
                                <option value="shipped" <?php if ($filter_status == 'shipped') echo 'selected'; ?>>発送済み</option>
                                <option value="delivered" <?php if ($filter_status == 'delivered') echo 'selected'; ?>>配達済み</option>
                                <option value="cancelled" <?php if ($filter_status == 'cancelled') echo 'selected'; ?>>キャンセル</option>
                            </select>
                        </div>
                        <div class="filter-group">
                            <label for="date_from">配送希望日 (From)</label>
                            <input type="date" id="date_from" name="date_from" value="<?php echo htmlspecialchars($filter_date_from); ?>">
                        </div>
                        <div class="filter-group">
                            <label for="date_to">配送希望日 (To)</label>
                            <input type="date" id="date_to" name="date_to" value="<?php echo htmlspecialchars($filter_date_to); ?>">
                        </div>
                        <div class="filter-group">
                            <label for="time">配送希望時間</label>
                            <select id="time" name="time">
                                <option value="">すべて</option>
                                <option value="午前中" <?php if ($filter_time == '午前中') echo 'selected'; ?>>午前中</option>
                                <option value="12~14時" <?php if ($filter_time == '12~14時') echo 'selected'; ?>>12~14時</option>
                                <option value="14~16時" <?php if ($filter_time == '14~16時') echo 'selected'; ?>>14~16時</option>
                                <option value="16~18時" <?php if ($filter_time == '16~18時') echo 'selected'; ?>>16~18時</option>
                                <option value="18~20時" <?php if ($filter_time == '18~20時') echo 'selected'; ?>>18~20時</option>
                                <option value="18~21時" <?php if ($filter_time == '18~21時') echo 'selected'; ?>>18~21時</option>
                                <option value="19~21時" <?php if ($filter_time == '19~21時') echo 'selected'; ?>>19~21時</option>
                            </select>
                        </div>
                        <button type="submit" class="filter-btn">絞り込む</button>
                    </form>
                </div>
            </div>

            <div id="message-box" class="message-box"></div>

            <div class="batch-actions">
                <select id="batch-status-select">
                    <option value="">一括操作を選択</option>
                    <option value="shipped">選択した商品を「発送済み」にする</option>
                    <option value="delivered">選択した商品を「配達済み」にする</option>
                    <option value="cancelled">選択した商品を「キャンセル」にする</option>
                </select>
                <button id="batch-update-btn" class="batch-update-btn">適用</button>
            </div>

            <div class="admin-table-wrapper">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th><input type="checkbox" id="select-all-checkbox"></th>
                            <th>注文ID</th>
                            <th>注文日時 / 顧客名</th>
                            <th>対象商品</th>
                            <th>数量</th>
                            <th>配送希望</th>
                            <th>商品ステータス</th>
                            <th>操作</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($orders)): ?>
                            <tr><td colspan="8">該当する注文はありません。</td></tr>
                        <?php else: ?>
                            <?php foreach ($orders as $order): ?>
                                <?php
                                // ★★★ この注文に含まれる商品の中から、自社のものだけをフィルタリング ★★★
                                $client_items = array_filter($order['items'], function($item) use ($client_id) {
                                    return $item['item_type'] === 'product' && isset($item['client_id']) && $item['client_id'] == $client_id;
                                });

                                // 自社の商品がなければ、この注文に関する行は一切表示しない
                                if (empty($client_items)) {
                                    continue;
                                }
                                ?>
                                <?php foreach ($client_items as $item): // ★★★ フィルタリング済みの配列をループ ★★★ ?>
                                    <?php
                                    $is_final_status = in_array($item['item_status'], ['delivered', 'cancelled']);
                                    $is_operable = !$is_final_status;
                                    ?>
                                    <tr class="own-item <?php if($is_final_status) echo 'final-status-row'; ?>">
                                        <td>
                                            <?php if ($is_operable): ?>
                                                <input type="checkbox" class="item-checkbox" value="<?php echo $item['order_item_id']; ?>">
                                            <?php endif; ?>
                                        </td>
                                        <td><?php echo htmlspecialchars($order['order_id']); ?></td>
                                        <td>
                                            <?php echo htmlspecialchars(date('Y/m/d H:i', strtotime($order['order_date']))); ?><br>
                                            <?php echo htmlspecialchars($order['user_name'] ?? 'ゲスト'); ?>様
                                        </td>
                                        <td><?php echo htmlspecialchars($item['item_name']); ?></td>
                                        <td>x <?php echo htmlspecialchars($item['quantity']); ?></td>
                                        <td class="delivery-info">
                                            <?php echo htmlspecialchars($order['delivery_date'] ? date('Y/m/d', strtotime($order['delivery_date'])) : '指定なし'); ?><br>
                                            <?php echo htmlspecialchars($order['delivery_time'] ?? ''); ?>
                                        </td>
                                        <td>
                                            <select class="status-select" data-order-item-id="<?php echo $item['order_item_id']; ?>" data-order-status="<?php echo htmlspecialchars($item['item_status']); ?>" <?php if ($is_final_status) echo 'disabled'; ?>>
                                                <option value="pending" <?php if ($item['item_status'] == 'pending') echo 'selected'; ?>>未対応</option>
                                                <option value="shipped" <?php if ($item['item_status'] == 'shipped') echo 'selected'; ?>>発送済み</option>
                                                <option value="delivered" <?php if ($item['item_status'] == 'delivered') echo 'selected'; ?>>配達済み</option>
                                                <option value="cancelled" <?php if ($item['item_status'] == 'cancelled') echo 'selected'; ?>>キャンセル</option>
                                            </select>
                                        </td>
                                        <td>
                                            <?php if ($is_operable): ?>
                                                <button class="update-btn" data-order-item-id="<?php echo $item['order_item_id']; ?>">更新</button>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>
    <footer class="admin-footer">
        <p class="admin-footer__copyright">© OUR BRAND Admin All Rights Reserved.</p>
    </footer>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // (JavaScript部分は変更なし)
        const messageBox = document.getElementById('message-box');
        const selectAllCheckbox = document.getElementById('select-all-checkbox');
        const itemCheckboxes = document.querySelectorAll('.item-checkbox');

        function showMessage(message, isSuccess) {
            messageBox.textContent = message;
            messageBox.className = 'message-box ' + (isSuccess ? 'success' : 'error');
            messageBox.style.display = 'block';
            setTimeout(() => { messageBox.style.display = 'none'; }, 4000);
        }

        function updateItemStatus(itemIds, status) {
            fetch('../api/api_update_order_item_status.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    order_item_ids: itemIds,
                    status: status
                })
            })
            .then(response => response.json())
            .then(data => {
                showMessage(data.message, data.success);
                if (data.success) {
                    setTimeout(() => { window.location.reload(); }, 1500);
                }
            })
            .catch(error => {
                console.error('Fetch Error:', error);
                showMessage('通信エラーが発生しました。コンソールを確認してください。', false);
            });
        }

        document.querySelectorAll('.update-btn').forEach(button => {
            button.addEventListener('click', function() {
                const itemId = this.dataset.orderItemId;
                const selectElement = document.querySelector(`.status-select[data-order-item-id="${itemId}"]`);
                updateItemStatus([itemId], selectElement.value);
            });
        });

        if (selectAllCheckbox) {
            selectAllCheckbox.addEventListener('change', function() {
                document.querySelectorAll('.item-checkbox:not(:disabled)').forEach(checkbox => {
                    checkbox.checked = this.checked;
                });
            });
        }

        const batchUpdateBtn = document.getElementById('batch-update-btn');
        if (batchUpdateBtn) {
            batchUpdateBtn.addEventListener('click', function() {
                const selectedIds = Array.from(itemCheckboxes)
                    .filter(checkbox => checkbox.checked)
                    .map(checkbox => checkbox.value);
                if (selectedIds.length === 0) {
                    showMessage('一括更新する注文を選択してください。', false);
                    return;
                }
                const batchStatus = document.getElementById('batch-status-select').value;
                if (!batchStatus) {
                    showMessage('一括操作を選択してください。', false);
                    return;
                }
                updateItemStatus(selectedIds, batchStatus);
            });
        }
        
        const filterToggleBtn = document.querySelector('.filter-toggle-btn');
        if (filterToggleBtn) {
            const filterWrapper = filterToggleBtn.closest('.filter-wrapper');
            filterToggleBtn.addEventListener('click', function() {
                filterWrapper.classList.toggle('is-open');
            });
        }
    });
    </script>
</body>
</html>
