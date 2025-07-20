<?php
// admin_header.php でセッションは開始されている想定
require_once __DIR__ . '/../common/contents_db.php';

if (!defined('DEBUG_MODE')) {
    define('DEBUG_MODE', false);
}

$user_db = new cuser_info();
// get_all() はページネーション対応ですが、ここでは一旦全件取得します
$all_users = $user_db->get_all(DEBUG_MODE, 0, 1000);

// 生年月日から年齢を計算する関数
function calculateAge($birthday)
{
    if (!$birthday || $birthday === '0000-00-00') {
        return '未設定';
    }
    try {
        $birthDate = new DateTime($birthday);
        $today = new DateTime('today');
        if ($birthDate > $today) {
            return '未来日';
        }
        $age = $today->diff($birthDate)->y;
        return $age;
    } catch (Exception $e) {
        return '不明';
    }
}
?>
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

<body>
    <?php require_once 'admin_header.php'; ?>

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
                            <?php if (!empty($all_users)): ?>
                                <?php foreach ($all_users as $user): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($user['user_name']); ?></td>
                                        <td><?php echo htmlspecialchars($user['user_email']); ?></td>
                                        <td><?php echo htmlspecialchars($user['user_age']); ?></td>
                                        <td><?php echo calculateAge($user['user_age']); ?>歳</td>
                                        <td>
                                            <div class="action-buttons">
                                                <button class="btn btn-sm btn-delete" data-id="<?php echo htmlspecialchars($user['user_id']); ?>">削除</button>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="5">ユーザーは登録されていません。</td>
                                </tr>
                            <?php endif; ?>
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

    <!-- <script src="../adminjs/admin_users.js"></script> -->
    <script src="../adminjs/admin.js"></script>
</body>

</html>