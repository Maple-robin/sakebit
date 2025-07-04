<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OUR BRAND | FAQ管理（一覧）</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@300;400;500;700&family=Zen+Old+Mincho:400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../admincss/admin.css">
    <link rel="stylesheet" href="../admincss/admin_faq.css">
</head>
<body>

    <header class="admin-header">
        <div class="admin-header__inner">            <h1 class="admin-header__logo">
                <a href="admin.php">OUR BRAND 管理者ページ</a>
            </h1>
            <nav class="admin-header__nav">
                <ul class="admin-nav__list">
                    <li><a href="admin_products.php">お酒管理</a></li>
                    <li><a href="admin_otsumami.php">おつまみ管理</a></li>
                    <li><a href="admin_users.php">一般ユーザー管理</a></li>
                    <li><a href="admin_client_users.php">企業ユーザー管理</a></li>
                    <li><a href="admin_posts.php">投稿管理</a></li>
                    <li><a href="admin_inquiries.php">お問い合わせ管理</a></li>
                    <li><a href="admin_faq.php" class="is-current">FAQ管理</a></li>
                    <li><a href="admin_reports.php">通報管理</a></li>
                    <li><a href="login.php">ログイン</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <main class="admin-main">
        <div class="admin-main__inner">
            <h2 class="admin-page-title">
                <span class="en">FAQ MANAGEMENT</span>
                <span class="ja">( FAQ管理 )</span>
            </h2>

            <section class="admin-section admin-faq-list">
                <div class="table-container">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>タイトル</th>
                                <th>質問のカテゴリ</th>
                                <th>内容</th>
                                <th>操作</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>注文の変更・キャンセルはできますか？</td>
                                <td>このサイトについて</td>
                                <td>ご注文完了後の内容変更やキャンセルは、原則として承っておりません。お間違えのないようご注意ください。</td>
                                <td>
                                    <div class="action-buttons">
                                        <a href="admin_faq_edit.php" class="btn btn-sm btn-edit">編集</a>
                                        <button class="btn btn-sm btn-delete">削除</button>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>パスワードを忘れました</td>
                                <td>ログイン・会員登録について</td>
                                <td>ログイン画面の「パスワードをお忘れの方はこちら」より、再設定手続きを行ってください。</td>
                                <td>
                                    <div class="action-buttons">
                                        <a href="admin_faq_edit.php" class="btn btn-sm btn-edit">編集</a>
                                        <button class="btn btn-sm btn-delete">削除</button>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>お酒の選び方がわかりません</td>
                                <td>お酒の情報について</td>
                                <td>当サイトでは、お酒の好みや気分に合わせた選び方のガイドを公開しています。ぜひご参照ください。</td>
                                <td>
                                    <div class="action-buttons">
                                        <a href="admin_faq_edit.php" class="btn btn-sm btn-edit">編集</a>
                                        <button class="btn btn-sm btn-delete">削除</button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </section>

            <div class="add-faq-button-area">
                <a href="admin_faq_add.php" class="btn btn-primary btn-add-new">
                    <span class="btn-icon">＋</span> 新しいFAQを登録する
                </a>
            </div>

        </div>
    </main>

    <footer class="admin-footer">
        <div class="admin-footer__inner">
            <p class="admin-footer__copyright">© OUR BRAND Admin All Rights Reserved.</p>
        </div>
    </footer>

</body>
</html>