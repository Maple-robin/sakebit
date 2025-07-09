<?php
/*!
@file client_login.php
@brief クライアントログインページ
@copyright Copyright (c) 2024 Your Name.
*/

// ★★★ ここからPHPロジックを記述 ★★★

// 1. セッションを開始
// HTMLよりも前に呼び出す必要があります
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 2. セッションからメッセージや古い入力値を取得し、変数に保存
$login_error = $_SESSION['login_error'] ?? '';
$success_message = $_SESSION['registration_success_message'] ?? '';
$old_username = $_SESSION['login_old_username'] ?? '';

// 3. 表示後、不要になったセッション変数を削除
unset($_SESSION['login_error'], $_SESSION['registration_success_message'], $_SESSION['login_old_username']);

// ★★★ PHPロジックはここまで ★★★
?>
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

        <?php
        // PHPの変数を使ってメッセージを表示
        if (!empty($login_error)) {
            echo '<div class="error-messages">';
            echo '<p>' . htmlspecialchars($login_error) . '</p>';
            echo '</div>';
        }
        if (!empty($success_message)) {
            echo '<div class="success-message">';
            echo '<p>' . htmlspecialchars($success_message) . '</p>';
            echo '</div>';
        }
        ?>

        <form action="process_login.php" method="post" class="login-form">
            <div class="form-group">
                <label for="username">メールアドレス</label>
                <input type="text" id="username" name="username" required value="<?php echo htmlspecialchars($old_username); ?>">
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
        <p class="login-footer-text">
            <a href="signup.php">新規登録はこちら</a>
        </p>
    </div>
</body>

</html>