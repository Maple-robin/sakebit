<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>投稿管理 | OUR BRAND 管理者ページ</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@300;400;500;700&family=Zen+Old+Mincho:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../admincss/admin.css">
    <link rel="stylesheet" href="../admincss/admin_posts.css">
</head>
<body>
    <header class="admin-header">        <div class="admin-header__inner">
            <h1 class="admin-header__logo">
                <a href="admin.php">OUR BRAND 管理者ページ</a>
            </h1>
            <nav class="admin-header__nav">
                <ul class="admin-nav__list">
                    <li><a href="admin_products.php">お酒管理</a></li>
                    <li><a href="admin_otsumami.php">おつまみ管理</a></li>
                    <li><a href="admin_users.php">一般ユーザー管理</a></li>
                    <li><a href="admin_client_users.php">企業ユーザー管理</a></li>
                    <li><a href="admin_posts.php" class="is-current">投稿管理</a></li>
                    <li><a href="admin_inquiries.php">お問い合わせ管理</a></li>
                    <li><a href="admin_faq.php">FAQ管理</a></li>
                    <li><a href="admin_reports.php">通報管理</a></li>
                    <li><a href="login.php">ログイン</a></li>
                </ul>
            </nav>
        </div>
    </header>

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
                            <th>バッド数</th>
                            <th>通報数</th>
                            <th>操作</th>
                        </tr>
                    </thead>
                    <tbody id="post-management-table-body">
                        </tbody>
                </table>
            </section>
        </div>
    </main>

    <footer class="admin-footer">
        <div class="admin-footer__inner">
            <p class="admin-footer__copyright">© OUR BRAND All Rights Reserved.</p>
        </div>
    </footer>

    <script src="../adminjs/posts.js"></script>
</body>
</html>