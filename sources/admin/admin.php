<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OUR BRAND | 管理者ページ</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@300;400;500;700&family=Zen+Old+Mincho:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../admincss/admin.css"> </head>
<body>

    <header class="admin-header">
        <div class="admin-header__inner">            <h1 class="admin-header__logo">
                <a href="admin.php">OUR BRAND 管理者ページ</a>
            </h1>
            <nav class="admin-header__nav">
                <ul class="admin-nav__list">
                    <li><a href="admin_products.php">お酒管理</a></li>
                    <li><a href="admin_otsumami.php">おつまみ管理</a></li>
                    <li><a href="admin_users.php">一般ユーザー管理</a></li>
                    <li><a href="admin_client_users.php">企業ユーザー管理</a></li>
                    <li><a href="admin_posts.php">投稿管理</a></li>
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
            <h2 class="admin-page-title">ダッシュボード</h2>

            <section class="admin-summary">
                <div class="admin-summary__grid">
                    <div class="admin-summary__card">
                        <h3>新規お問い合わせ</h3>
                        <p class="admin-summary__count">3</p>
                        <a href="admin_inquiries.php" class="admin-summary__link">詳細を見る</a>
                    </div>
                    <div class="admin-summary__card">
                        <h3>未対応の通報</h3>
                        <p class="admin-summary__count">1</p>
                        <a href="admin_reports.php" class="admin-summary__link">詳細を見る</a>
                    </div>
                    <div class="admin-summary__card">
                        <h3>本日の新規投稿</h3>
                        <p class="admin-summary__count">12</p>
                        <a href="admin_posts.php" class="admin-summary__link">詳細を見る</a>
                    </div>
                    <div class="admin-summary__card">
                        <h3>未承認の商品レビュー</h3>
                        <p class="admin-summary__count">5</p>
                        <a href="#" class="admin-summary__link">詳細を見る</a>
                    </div>
                </div>
            </section>

            <section class="admin-quick-links">
                <h2 class="admin-section-title">クイックリンク</h2>
                <ul class="quick-link__list">
                    <li><a href="admin_products_add.php">新しいお酒を登録</a></li>
                    <li><a href="admin_users.php">一般ユーザーリストを見る</a></li>
                    <li><a href="admin_otsumami_add.php">新しいおつまみを登録</a></li>
                    <li><a href="admin_faq_add.php">FAQ項目を追加</a></li>
                </ul>
            </section>
        </div>
    </main>

    <footer class="admin-footer">
        <div class="admin-footer__inner">
            <p class="admin-footer__copyright">© OUR BRAND Admin All Rights Reserved.</p>
        </div>
    </footer>

</body>
</html>