<?php
// admin_header.php でセッションは開始されている想定
require_once __DIR__ . '/../common/contents_db.php';

if (!defined('DEBUG_MODE')) {
    define('DEBUG_MODE', false);
}

$contacts_db = new ccontacts();
$all_inquiries = $contacts_db->get_all_contacts_for_admin(DEBUG_MODE);
?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>お問い合わせ管理 | SAKE BIT 管理者ページ</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@300;400;500;700&family=Zen+Old+Mincho:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="../admincss/admin.css">
    <link rel="stylesheet" href="../admincss/admin_inquiries.css">
    <style>
        /* 返信状況に応じて色分けするスタイル */
        .status-pending {
            color: #dc3545;
            /* 赤色 */
            font-weight: bold;
        }

        .status-replied {
            color: #28a745;
            /* 緑色 */
        }
    </style>
</head>

<body>
    <?php require_once 'admin_header.php'; ?>

    <main class="admin-main">
        <div class="admin-main__inner">
            <h2 class="admin-page-title">
                <span class="en">INQUIRY MANAGEMENT</span>
                <span class="ja">( お問い合わせ管理 )</span>
            </h2>
            <section class="admin-table-section">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>ユーザー名</th>
                            <th>メールアドレス</th>
                            <th>タイトル</th>
                            <th>内容</th>
                            <th>返信状況</th>
                            <th>操作</th>
                        </tr>
                    </thead>
                    <tbody id="inquiry-management-table-body">
                        <?php if (!empty($all_inquiries)): ?>
                            <?php foreach ($all_inquiries as $inquiry): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($inquiry['user_name']); ?></td>
                                    <td><?php echo htmlspecialchars($inquiry['user_email']); ?></td>
                                    <td><?php echo htmlspecialchars($inquiry['contact_title']); ?></td>
                                    <td class="inquiry-content-cell" title="<?php echo htmlspecialchars($inquiry['contact_content']); ?>">
                                        <?php echo htmlspecialchars(mb_strimwidth($inquiry['contact_content'], 0, 50, '...')); ?>
                                    </td>
                                    <td>
                                        <span class="status-<?php echo htmlspecialchars(strtolower($inquiry['status'] === '保留中' ? 'pending' : 'replied')); ?>">
                                            <?php echo htmlspecialchars($inquiry['status']); ?>
                                        </span>
                                    </td>
                                    <td>
                                        <!-- ▼▼▼ ここを修正 ▼▼▼ -->
                                        <button onclick="location.href='admin_inquiry_reply.php?id=<?php echo htmlspecialchars($inquiry['contact_id']); ?>'" class="btn btn-sm btn-primary">返信する</button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6">お問い合わせはありません。</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </section>
        </div>
    </main>

    <footer class="admin-footer">
        <div class="admin-footer__inner">
            <p class="admin-footer__copyright">© SAKE BIT Admin All Rights Reserved.</p>
        </div>
    </footer>

    <script src="../adminjs/admin.js"></script>
</body>

</html>