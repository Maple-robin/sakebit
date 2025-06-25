<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>新規登録 | OUR BRAND</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@300;400;500;700&family=Zen+Old+Mincho:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../clientcss/signup.css">
</head>
<body>
    <div class="signup-container">
        <h1 class="signup-title">
            OUR BRAND<br>新規登録
        </h1>
        <form action="client_top.php" method="post" class="signup-form">
            <div class="form-group">
                <label for="company-name">会社名</label>
                <input type="text" id="company-name" name="company-name" required>
            </div>
            <div class="form-group">
                <label for="representative-name">代表者名</label>
                <input type="text" id="representative-name" name="representative-name" required>
            </div>
            <div class="form-group">
                <label for="email">メールアドレス</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="phone">電話番号</label>
                <input type="tel" id="phone" name="phone" required>
            </div>            <div class="form-group">
                <label for="address">住所</label>
                <input type="text" id="address" name="address" required>
            </div>
            <div class="form-group">
                <label for="password">パスワード</label>
                <input type="password" id="password" name="password" required>
            </div>
            <div class="form-group">
                <label for="password-confirm">パスワード（確認）</label>
                <input type="password" id="password-confirm" name="password-confirm" required>
            </div>
            <div class="terms-group">
                <input type="checkbox" id="terms" name="terms" required>
                <label for="terms">利用規約に同意します</label>
            </div>
            <button type="submit" class="signup-button">アカウントを作成</button>
        </form>
        <p class="signup-footer-text">
            既にアカウントをお持ちですか？<br>
            <a href="login.php">ログインはこちら</a>
        </p>
    </div>
</body>
</html>
