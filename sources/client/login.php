<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>管理者ログイン | OUR BRAND</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@300;400;500;700&family=Zen+Old+Mincho:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../clientcss/login.css">
</head>
<body>
    <div class="login-container">
        <h1 class="login-title">
            OUR BRAND<br>管理者ログイン
        </h1>
        <form action="client_top.php" method="post" class="login-form">
            <div class="form-group">
                <label for="username">ユーザー名 または メールアドレス</label>
                <input type="text" id="username" name="username" required>
            </div>
            <div class="form-group">
                <label for="password">パスワード</label>
                <input type="password" id="password" name="password" required>
            </div>
            <button type="submit" class="login-button">ログイン</button>
        </form>
        <p class="login-footer-text">
            <a href="#">パスワードをお忘れですか？</a>
        </p>
    </div>
</body>
</html>