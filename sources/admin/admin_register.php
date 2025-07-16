<?php
/*!
@file admin_register.php
@brief 管理者ユーザー登録ページと処理
@copyright Copyright (c) 2024 Your Name.
*/

// 全てのエラーを表示するように設定（デバッグ用 - 本番環境では推奨されません）
error_reporting(E_ALL);
ini_set('display_errors', 1);

// セッションを開始
session_start();

// contents_db.php と config.php をインクルード
$baseDir = dirname(__DIR__); // /home/j2025g/public_html/ を指す
$contentsDbPath = $baseDir . '/common/contents_db.php';
$configPath = $baseDir . '/common/config.php';

if (!file_exists($configPath)) {
    die("Fatal Error: config.php not found at " . htmlspecialchars($configPath));
}
require_once $configPath;

if (!file_exists($contentsDbPath)) {
    die("Fatal Error: contents_db.php not found at " . htmlspecialchars($contentsDbPath));
}
require_once $contentsDbPath;

// DEBUG_MODE が config.php で定義されていない場合はここで定義
if (!defined('DEBUG_MODE')) {
    define('DEBUG_MODE', false); // 本番環境では false に設定
}

$message = '';
$message_type = ''; // 'success' or 'error'
$submitted_username = '';

// 既にログインしている場合は、管理者ページへリダイレクト（必要であれば）
// if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
//     header('Location: admin.php');
//     exit();
// }

// POSTリクエストがある場合のみ処理を実行
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    $submitted_username = htmlspecialchars($username);

    if (empty($username) || empty($password) || empty($confirm_password)) {
        $message = '全ての項目を入力してください。';
        $message_type = 'error';
    } elseif ($password !== $confirm_password) {
        $message = 'パスワードと確認用パスワードが一致しません。';
        $message_type = 'error';
    } else {
        try {
            $admin_db = new cadmin_user_info();

            // ユーザー名が既に存在するかチェック (任意だが推奨)
            $existing_user = $admin_db->get_admin_user_by_name(DEBUG_MODE, $username);
            if ($existing_user) {
                $message = 'このユーザー名は既に使われています。';
                $message_type = 'error';
            } else {
                // パスワードをハッシュ化
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);

                // データベースにインサート
                $result = $admin_db->insert_admin_user(DEBUG_MODE, $username, $hashed_password);

                if ($result) {
                    // 成功メッセージをセッションに保存してログインページにリダイレクト
                    $_SESSION['admin_login_message'] = ['text' => '管理者ユーザーが正常に登録されました。', 'type' => 'success'];
                    header('Location: admin_login.php');
                    exit();
                } else {
                    $message = '管理者ユーザーの登録に失敗しました。';
                    $message_type = 'error';
                }
            }
        } catch (Exception $e) {
            error_log("Admin registration process error: " . $e->getMessage());
            $message = 'システムエラーが発生しました。しばらくしてから再度お試しください。';
            $message_type = 'error';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OUR BRAND | 管理者登録</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@300;400;500;700&family=Zen+Old+Mincho:wght@400;500;700&display=swap" rel="stylesheet">
    
    <link rel="stylesheet" href="../admincss/admin.css"> 
    <link rel="stylesheet" href="../admincss/login.css"> <style>
        /* メッセージ表示用の追加スタイル */
        .message-box {
            text-align: center;
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 5px;
            font-weight: bold;
        }
        .message-box.success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .message-box.error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
    </style>
</head>
<body>
    <header class="admin-header">
        <div class="admin-header__inner">
            <h1 class="admin-header__logo">
                <a href="admin_products.php">OUR BRAND 管理者ページ</a>
            </h1>
            <nav class="admin-header__nav">
                <ul class="admin-nav__list">
                    <li><a href="admin_products.php">お酒管理</a></li>
                    <li><a href="admin_otsumami.php">おつまみ管理</a></li>
                    <li><a href="admin_users.php">ユーザー管理</a></li>
                    <li><a href="admin_posts.php">投稿管理</a></li>
                    <li><a href="admin_inquiries.php">お問い合わせ管理</a></li>
                    <li><a href="admin_faq.php">FAQ管理</a></li>
                    <li><a href="admin_reports.php">通報管理</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <div class="login-container"> <h1 class="login-title">管理者ユーザー登録</h1>

        <?php if ($message): ?>
            <div class="message-box <?= $message_type ?>">
                <?= htmlspecialchars($message) ?>
            </div>
        <?php endif; ?>
        
        <form action="admin_register.php" method="POST" class="login-form">
            <div class="form-group">
                <label for="username">ユーザー名:</label>
                <input type="text" id="username" name="username" required value="<?= $submitted_username ?>">
            </div>
            <div class="form-group">
                <label for="password">パスワード:</label>
                <input type="password" id="password" name="password" required>
            </div>
            <div class="form-group">
                <label for="confirm_password">パスワード確認:</label>
                <input type="password" id="confirm_password" name="confirm_password" required>
            </div>
            <button type="submit" class="login-button">管理者登録</button>
        </form>
        <p class="text-center" style="margin-top: 20px;"><a href="admin_login.php">ログインページに戻る</a></p>
    </div>

    <footer class="admin-footer">
        <div class="admin-footer__inner">
            <p class="admin-footer__copyright">© OUR BRAND Admin All Rights Reserved.</p>
        </div>
    </footer>
    <script src="../adminjs/admin.js"></script>
</body>
</html>