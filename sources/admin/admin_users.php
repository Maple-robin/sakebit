<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SAKE BIT | 一般ユーザー管理</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@300;400;500;700&family=Zen+Old+Mincho:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="../admincss/admin.css">
    <link rel="stylesheet" href="../admincss/admin_users.css">
</head>
<body>    <?php require_once 'admin_header.php'; ?>

    <main class="admin-main">
        <div class="admin-main__inner">
            <h2 class="admin-page-title">
                <span class="en">USER MANAGEMENT</span>
                <span class="ja">( 一般ユーザー管理 )</span>
            </h2>

            <section class="admin-section admin-table-section">
                <div class="table-container">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>ユーザー名</th>
                                <th>メールアドレス</th>
                                <th>誕生日</th>
                                <th>年齢</th>
                                <th>操作</th>
                            </tr>
                        </thead>
                        <tbody id="user-management-table-body">
                            <tr>
                                <td>テストユーザー１</td>
                                <td>test1@example.com</td>
                                <td>1990/01/15</td>
                                <td>35</td>
                                <td>
                                    <div class="action-buttons">
                                        <a href="#" class="btn btn-sm btn-edit">編集</a>
                                        <button class="btn btn-sm btn-delete">削除</button>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>テストユーザー２</td>
                                <td>test2@example.com</td>
                                <td>1985/05/20</td>
                                <td>40</td>
                                <td>
                                    <div class="action-buttons">
                                        <a href="#" class="btn btn-sm btn-edit">編集</a>
                                        <button class="btn btn-sm btn-delete">削除</button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </section>
        </div>
    </main>

    <footer class="admin-footer">
        <div class="admin-footer__inner">
            <p class="admin-footer__copyright">© SAKE BIT Admin All Rights Reserved.</p>
        </div>
    </footer>

    <script src="../adminjs/admin_users.js"></script>
    <script src="../adminjs/admin.js"></script>
</body>
</html>