<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SAKE BIT | FAQを登録</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@300;400;500;700&family=Zen+Old+Mincho:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../admincss/admin.css">
    <link rel="stylesheet" href="../admincss/admin_faq_add.css">
</head>
<body>

    <?php require_once 'admin_header.php'; ?>

    <main class="admin-main">
        <div class="admin-main__inner">
            <h2 class="admin-page-title">
                <span class="en">ADD FAQ</span>
                <span class="ja">( FAQ追加 )</span>
            </h2>

            <section class="admin-section admin-faq-add-form">
                <form action="#" method="post" class="admin-form">
                    <div class="form-group">
                        <label for="faq_title">タイトル <span class="required">必須</span></label>
                        <input type="text" id="faq_title" name="faq_title" required maxlength="128">
                    </div>

                    <div class="form-group">
                        <label for="faq_category">質問のカテゴリ <span class="required">必須</span></label>
                        <select id="faq_category" name="faq_category" required>
                            <option value="">選択してください</option>
                            <option value="このサイトについて">このサイトについて</option>
                            <option value="ログイン・会員登録について">ログイン・会員登録について</option>
                            <option value="お酒の情報について">お酒の情報について</option>
                            <option value="投稿について">投稿について</option>
                            <option value="その他">その他</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="faq_content">内容 <span class="required">必須</span></label>
                        <textarea id="faq_content" name="faq_content" rows="10" required maxlength="500"></textarea>
                    </div>
                    
                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary btn-lg">このFAQを登録する</button>
                    </div>
                </form>
            </section>

            <div class="back-to-list-button-area">
                <a href="admin_faq.php" class="btn btn-secondary btn-back-to-list">
                    FAQ一覧に戻る
                </a>
            </div>

        </div>
    </main>

    <footer class="admin-footer">
        <div class="admin-footer__inner">
            <p class="admin-footer__copyright">© SAKE BIT Admin All Rights Reserved.</p>
        </div>
    </footer>

    <script src="../adminjs/admin.js"></script>
</body>
</html>