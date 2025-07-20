<?php
// admin_header.php でセッションは開始されている想定
require_once __DIR__ . '/../common/contents_db.php';

if (!defined('DEBUG_MODE')) {
    define('DEBUG_MODE', false);
}

$client_user_db = new cclient_user_info();
$all_client_users = $client_user_db->get_all_client_users(DEBUG_MODE);
?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SAKE BIT | 企業ユーザー管理</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@300;400;500;700&family=Zen+Old+Mincho:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="../admincss/admin.css">
    <link rel="stylesheet" href="../admincss/admin_client_users.css">
</head>

<body>
    <?php require_once 'admin_header.php'; ?>

    <main class="admin-main">
        <div class="admin-main__inner">
            <h2 class="admin-page-title">
                <span class="en">CLIENT USER MANAGEMENT</span>
                <span class="ja">( 企業ユーザー管理 )</span>
            </h2>

            <section class="admin-section admin-table-section">
                <div class="table-container">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>企業ID</th>
                                <th>企業名</th>
                                <th>メールアドレス</th>
                                <th>操作</th>
                            </tr>
                        </thead>
                        <tbody id="client-user-management-table-body">
                            <?php if (!empty($all_client_users)): ?>
                                <?php foreach ($all_client_users as $client_user): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($client_user['client_id']); ?></td>
                                        <td><?php echo htmlspecialchars($client_user['company_name']); ?></td>
                                        <td><?php echo htmlspecialchars($client_user['email']); ?></td>
                                        <td>
                                            <div class="action-buttons">
                                                <button class="btn btn-sm btn-delete" data-id="<?php echo htmlspecialchars($client_user['client_id']); ?>">削除</button>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="4">企業ユーザーは登録されていません。</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </section>
        </div>
    </main>

    <footer class="admin-footer">
        <div class="admin-footer__inner">
            <p class="admin-footer__copyright">© SAKE BIT Admin All Rights Reserved.</p>
        </div>
    </footer>

    <!-- <script src="../adminjs/admin_client_users.js"></script> -->
    <script src="../adminjs/admin.js"></script>
</body>

</html>