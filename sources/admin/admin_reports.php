<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>通報管理 | SAKE BIT 管理者ページ</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@300;400;500;700&family=Zen+Old+Mincho:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="../admincss/admin.css">
    <link rel="stylesheet" href="../admincss/admin_reports.css">
</head>
<body>
    <?php require_once 'admin_header.php'; ?>

    <main class="admin-main">
        <div class="admin-main__inner">
            <h2 class="admin-page-title">
                <span class="en">REPORT MANAGEMENT</span>
                <span class="ja">( 通報管理 )</span>
            </h2>
            <section class="admin-table-section">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>通報者ユーザー名</th>
                            <th>投稿タイトル</th>
                            <th>投稿内容</th>
                            <th>通報カテゴリ</th>
                            <th>通報内容</th>
                            <th>操作</th>
                        </tr>
                    </thead>
                    <tbody id="report-management-table-body">
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

    <script src="../adminjs/admin_reports.js"></script>
    <script src="../adminjs/admin.js"></script>
</body>
</html>
