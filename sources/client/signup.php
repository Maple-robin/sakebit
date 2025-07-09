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

        <?php
        session_start(); // セッションを開始
        // エラーメッセージの表示
        if (isset($_SESSION['signup_errors']) && !empty($_SESSION['signup_errors'])) {
            echo '<div class="error-messages">';
            foreach ($_SESSION['signup_errors'] as $error) {
                echo '<p>' . htmlspecialchars($error) . '</p>';
            }
            echo '</div>';
            unset($_SESSION['signup_errors']); // 表示後、セッションから削除
        }

        // 入力値の再表示用に旧データを取得
        $old_data = $_SESSION['signup_old_data'] ?? [];
        unset($_SESSION['signup_old_data']); // 表示後、セッションから削除
        ?>

        <form action="process_signup.php" method="post" class="signup-form">
            <div class="form-group">
                <label for="company-name">会社名</label>
                <input type="text" id="company-name" name="company-name" required value="<?php echo htmlspecialchars($old_data['company_name'] ?? ''); ?>">
            </div>
            <div class="form-group">
                <label for="representative-name">代表者名</label>
                <input type="text" id="representative-name" name="representative-name" required value="<?php echo htmlspecialchars($old_data['representative_name'] ?? ''); ?>">
            </div>
            <div class="form-group">
                <label for="email">メールアドレス</label>
                <input type="email" id="email" name="email" required value="<?php echo htmlspecialchars($old_data['email'] ?? ''); ?>">
            </div>
            <div class="form-group">
                <label for="phone">電話番号</label>
                <input type="tel" id="phone" name="phone" required value="<?php echo htmlspecialchars($old_data['phone'] ?? ''); ?>">
            </div>
            <div class="form-group">
                <label for="address">住所</label>
                <input type="text" id="address" name="address" required value="<?php echo htmlspecialchars($old_data['address'] ?? ''); ?>">
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
                <input type="checkbox" id="terms" name="terms" required <?php echo (isset($old_data['terms']) && $old_data['terms'] == 'on') ? 'checked' : ''; ?>>
                <label for="terms">利用規約に同意します</label>
            </div>
            <button type="submit" class="signup-button">アカウントを作成</button>
        </form>
        <p class="signup-footer-text">
            既にアカウントをお持ちですか？<br>
            <a href="client_login.php">ログインはこちら</a>
        </p>
    </div>
</body>
</html>
