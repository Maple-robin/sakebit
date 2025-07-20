<?php
/*!
@file contact.php
@brief お問い合わせページ
@copyright Copyright (c) 2024 Your Name.
*/

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// ログインしていない場合は、ログインページにリダイレクト
if (!isset($_SESSION['user_id'])) {
    $_SESSION['redirect_url'] = $_SERVER['REQUEST_URI'];
    header('Location: login.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>お問い合わせ | SAKE BIT</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@300;400;500;700&family=Zen+Old+Mincho:wght@400;500;700&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="css/top.css">
    <link rel="stylesheet" href="css/contact.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>

<body>

    <?php
    require_once 'header.php';
    ?>

    <main>
        <section class="contact-section">
            <div class="contact-form-container">
                <h2 class="section-title">
                    <span class="en">CONTACT US</span>
                    <span class="ja">( お問い合わせ )</span>
                </h2>
                <form id="contactForm">
                    <div class="form-group">
                        <label for="contact-title">件名 <span class="required-badge">必須</span></label>
                        <input type="text" id="contact-title" name="contact_title" placeholder="お問い合わせの件名を入力してください" required>
                    </div>

                    <!-- ▼▼▼ メールアドレスの確認文を削除 ▼▼▼ -->

                    <div class="form-group">
                        <label for="contact-content">お問い合わせ内容 <span class="required-badge">必須</span></label>
                        <textarea id="contact-content" name="contact_content" placeholder="こちらにお問い合わせ内容を入力してください" required></textarea>
                    </div>
                    <div class="form-actions">
                        <button type="button" class="cancel-btn">キャンセル</button>
                        <button type="submit" class="submit-btn">送信する</button>
                    </div>
                    <!-- メッセージ表示用のコンテナ -->
                    <div id="form-message-container"></div>
                </form>
            </div>
        </section>
    </main>

    <?php
    require_once 'footer.php';
    ?>

    <script src="js/script.js"></script>
    <script src="js/contact.js"></script>
</body>

</html>