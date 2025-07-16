<?php
/*!
@file contact.php
@brief お問い合わせページ
@copyright Copyright (c) 2024 Your Name.
*/

// ★注意: DB接続やセッション開始は header.php で行われるため、ここでの処理は不要です。
// session_start();
// require_once __DIR__ . '/common/contents_db.php';

// ここにお問い合わせページ固有のPHPロジックがあれば記述します。
// (例: フォーム送信時の処理など)
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

    <link rel="stylesheet" href="https://unpkg.com/swiper@8/swiper-bundle.min.css" />
    <link rel="stylesheet" href="css/top.css">
    <link rel="stylesheet" href="css/contact.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>

<body>

    <?php 
    // 共通ヘッダーを読み込む
    require_once 'header.php'; 
    ?>

    <main>
        <section class="contact-section">
            <div class="contact-form-container">
                <h2 class="section-title">
                    <span class="en">CONTACT US</span>
                    <span class="ja">( お問い合わせ )</span>
                </h2>
                <form action="#" method="POST" id="contactForm">
                    <div class="form-group">
                        <label for="contact-title">件名</label>
                        <input type="text" id="contact-title" name="contact_title" placeholder="お問い合わせの件名を入力してください"
                            required>
                    </div>
                    <div class="form-group">
                        <label for="contact-content">お問い合わせ内容</label>
                        <textarea id="contact-content" name="contact_content" placeholder="こちらにお問い合わせ内容を入力してください"
                            required></textarea>
                    </div>
                    <div class="form-actions">
                        <button type="button" class="cancel-btn">キャンセル</button>
                        <button type="submit" class="submit-btn">送信する</button>
                    </div>
                </form>
            </div>
        </section>
    </main>

    <?php 
    // 共通フッターを読み込む
    require_once 'footer.php'; 
    ?>

    <script src="https://unpkg.com/swiper@8/swiper-bundle.min.js"></script>
    <script src="js/script.js"></script>
    <script src="js/contact.js"></script>
</body>

</html>
