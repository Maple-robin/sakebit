<?php
// admin_header.php でセッションは開始されている想定
require_once __DIR__ . '/../common/contents_db.php';

if (!defined('DEBUG_MODE')) {
    define('DEBUG_MODE', false);
}

$posts_db = new cposts();
$all_posts = $posts_db->get_all_posts_for_admin(DEBUG_MODE);
?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>投稿管理 | SAKE BIT 管理者ページ</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@300;400;500;700&family=Zen+Old+Mincho:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../admincss/admin.css">
    <link rel="stylesheet" href="../admincss/admin_posts.css">
</head>

<body>
    <?php require_once 'admin_header.php'; ?>

    <main class="admin-main">
        <div class="admin-main__inner">
            <h1 class="admin-page-title">
                <span class="en">POST MANAGEMENT</span>
                <span class="ja">( 投稿管理 )</span>
            </h1>

            <section class="admin-table-section">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>ユーザー名</th>
                            <th>投稿タイトル</th>
                            <th>投稿内容</th>
                            <th>いいね数</th>
                            <th>ブックマーク数</th>
                            <th>通報数</th>
                            <th>操作</th>
                        </tr>
                    </thead>
                    <tbody id="post-management-table-body">
                        <?php if (!empty($all_posts)): ?>
                            <?php foreach ($all_posts as $post): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($post['user_name']); ?></td>
                                    <td><?php echo htmlspecialchars($post['post_title']); ?></td>
                                    <td class="post-content-cell" title="<?php echo htmlspecialchars($post['post_content']); ?>">
                                        <?php echo htmlspecialchars(mb_strimwidth($post['post_content'], 0, 50, '...')); ?>
                                    </td>
                                    <td><?php echo htmlspecialchars($post['good_count']); ?></td>
                                    <td><?php echo htmlspecialchars($post['heart_count']); ?></td>
                                    <td><?php echo htmlspecialchars($post['report_count']); ?></td>
                                    <td><button class="delete-button" data-id="<?php echo htmlspecialchars($post['post_id']); ?>">削除</button></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="7">投稿はありません。</td>
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

    <!-- 削除機能などのためにJSは残しますが、表示には使いません -->
    <!-- <script src="../adminjs/admin_posts.js"></script> -->
    <script src="../adminjs/admin.js"></script>
</body>

</html>