
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OUR BRAND | 管理者ログイン</title>
    <!-- Google Fonts: Noto Sans JP と Zen Old Mincho を読み込み -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@300;400;500;700&family=Zen+Old+Mincho:wght@400;500;700&display=swap" rel="stylesheet">
    
    <link rel="stylesheet" href="../admincss/admin.css"> 

    <link rel="stylesheet" href="../admincss/login.css"> 
</head>

<body>

    <!-- ヘッダー -->
    <header class="admin-header">
        <div class="admin-header__inner">
            <h1 class="admin-header__logo">
                <a href="admin.php">OUR BRAND 管理者ページ</a>
            </h1>
            <nav class="admin-header__nav">
                <ul class="admin-nav__list">
                    <li><a href="admin_products.php">お酒管理</a></li>
                    <li><a href="admin_otsumami.php">おつまみ管理</a></li>
                    <li><a href="admin_users.php">ユーザー管理</a></li>
                    <li><a href="admin_posts.php">投稿管理</a></li>
                    <li><a href="admin_inquiries.php">お問い合わせ管理</a></li>
                    <li><a href="admin_faq.php">FAQ管理</a></li>
                    <li><a href="admin_reports.php">通報管理</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <!-- ログインフォーム全体を囲むコンテナ -->
    <div class="login-container">
        <!-- ログインページのタイトル -->
        <h1 class="login-title">OUR BRAND 管理者ログイン</h1>
        
        <!-- ログインフォーム -->
        <!-- action="login_process.php" は、フォーム送信時に処理を行うPHPファイルのパスです。 -->
        <!-- method="POST" は、データを安全に送信するために使用します。 -->
        <form action="login_process.php" method="POST" class="login-form">
            <!-- ユーザー名入力グループ -->
            <div class="form-group">
                <label for="username">ユーザー名:</label>
                <input type="text" id="username" name="username" required>
            </div>
            <!-- パスワード入力グループ -->
            <div class="form-group">
                <label for="password">パスワード:</label>
                <input type="password" id="password" name="password" required>
            </div>
            <!-- ログインボタン -->
            <button type="submit" class="login-button">ログイン</button>
        </form>
    </div>

    <!-- フッター -->
    <footer class="admin-footer">
        <div class="admin-footer__inner">
            <p class="admin-footer__copyright">© OUR BRAND Admin All Rights Reserved.</p>
        </div>
    </footer>

</body>
</html>
