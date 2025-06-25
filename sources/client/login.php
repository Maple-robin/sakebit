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
        session_start(); // セッションを開始
        // エラーメッセージの表示
        if (isset($_SESSION['login_error']) && !empty($_SESSION['login_error'])) {
            echo '<div class="error-messages">';
            echo '<p>' . htmlspecialchars($_SESSION['login_error']) . '</p>';
            echo '</div>';
            unset($_SESSION['login_error']); // 表示後、セッションから削除
        }
        // 新規登録成功メッセージの表示 (signupからのリダイレクト時)
        if (isset($_SESSION['registration_success_message']) && !empty($_SESSION['registration_success_message'])) {
            echo '<div class="success-message">';
            echo '<p>' . htmlspecialchars($_SESSION['registration_success_message']) . '</p>';
            echo '</div>';
            unset($_SESSION['registration_success_message']); // 表示後、セッションから削除
        }

        // 入力値の再表示用に旧データを取得
        $old_username = $_SESSION['login_old_username'] ?? '';
        unset($_SESSION['login_old_username']); // 表示後、セッションから削除
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
