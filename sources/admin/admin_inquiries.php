<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>お問い合わせ管理 | SAKE BIT 管理者ページ</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="../admincss/admin.css">
    <link rel="stylesheet" href="../admincss/admin_inquiries.css">
</head>
<body>
    <?php require_once 'admin_header.php'; ?>

    <main class="admin-main">
        <div class="admin-main__inner">
            <h2 class="admin-page-title">
                <span class="en">INQUIRY MANAGEMENT</span>
                <span class="ja">( お問い合わせ管理 )</span>
            </h2>
            <section class="admin-table-section">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>ユーザー名</th>
                            <th>メールアドレス</th>
                            <th>タイトル</th>
                            <th>内容</th>
                            <th>返信状況</th>
                            <th>操作</th>
                        </tr>
                    </thead>
                    <tbody id="inquiry-management-table-body">
                        <!-- ここにJSで各問い合わせ行が追加される想定 -->
                    </tbody>
                </table>
            </section>
        </div>
    </main>

    <footer class="admin-footer">
        <div class="admin-footer__inner">
            <p class="admin-footer__copyright">© SAKE BIT Admin All Rights Reserved.</p>
        </div>
    </footer>

    <script src="../adminjs/admin_inquiries.js"></script>
    <script src="../adminjs/admin.js"></script>
</body>
</html>
