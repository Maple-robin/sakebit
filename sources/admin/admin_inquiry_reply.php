<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>お問い合わせ返信 | SAKE BIT 管理者ページ</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@300;400;500;700&family=Zen+Old+Mincho:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="../admincss/admin.css">
    <link rel="stylesheet" href="../admincss/admin_inquiry_reply.css">
</head>
<body>
    <header class="admin-header">
        <div class="admin-header__inner">            <h1 class="admin-header__logo">
                <a href="admin_products.php">SAKE BIT 管理者ページ</a>
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
                    <li><a href="admin_login.php">ログイン</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <main class="admin-main">
        <div class="admin-main__inner">
            <h2 class="admin-page-title">
                <span class="en">REPLY TO INQUIRY</span>
                <span class="ja">( お問い合わせ返信 )</span>
            </h2>

            <section class="inquiry-details-section">
                <div class="inquiry-item">
                    <span class="inquiry-label">お問い合わせタイトル:</span>
                    <p id="inquiry-title" class="inquiry-value"></p>
                </div>
                <div class="inquiry-item">
                    <span class="inquiry-label">お問い合わせ内容:</span>
                    <p id="inquiry-content" class="inquiry-value"></p>
                </div>
            </section>

            <section class="reply-form-section">
                <div class="form-group">
                    <label for="reply-content">返信内容:</label>
                    <textarea id="reply-content" placeholder="返信内容を入力してください"></textarea>
                </div>

                <div class="form-group">
                    <label>返信状況:</label>
                    <div class="radio-group">
                        <label>
                            <input type="radio" name="status" value="replied" id="status-replied">
                            返信済み
                        </label>
                        <label>
                            <input type="radio" name="status" value="pending" id="status-pending">
                            保留中
                        </label>
                    </div>
                </div>

                <button id="submit-reply-button" class="submit-button">返信を送信</button>
            </section>
        </div>
    </main>

    <footer class="admin-footer">
        <div class="admin-footer__inner">
            <p class="admin-footer__copyright">© SAKE BIT Admin All Rights Reserved.</p>
        </div>
    </footer>

    <script src="../adminjs/admin_inquiry_reply.js"></script>
    <script src="../adminjs/admin.js"></script>
</body>
</html>
