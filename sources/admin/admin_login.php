<?php
/*!
@file admin_login.php
@brief 管理者ログインページと処理
@copyright Copyright (c) 2024 Your Name.
*/

// 全てのエラーを表示するように設定（デバッグ用 - 本番環境では推奨されません）
error_reporting(E_ALL);
ini_set('display_errors', 1);

// セッションを開始
session_start();
// 既存のセッション情報を一度すべて破棄して、セッションIDを再生成する
// これにより、他のユーザーとしてログインしていた場合の情報が完全にクリアされる
session_unset();
session_regenerate_id(true);

// contents_db.php と config.php をインクルード
// パスは /home/j2025g/public_html/common/ にあることを想定して調整済み
$baseDir = dirname(__DIR__); // /home/j2025g/public_html/ を指す
$contentsDbPath = $baseDir . '/common/contents_db.php';
$configPath = $baseDir . '/common/config.php';

// config.php が存在しない場合のエラーハンドリング
if (!file_exists($configPath)) {
    die("Fatal Error: config.php not found at " . htmlspecialchars($configPath));
}
require_once $configPath;

// contents_db.php が存在しない場合のエラーハンドリング
if (!file_exists($contentsDbPath)) {
    die("Fatal Error: contents_db.php not found at " . htmlspecialchars($contentsDbPath));
}
require_once $contentsDbPath;


// DEBUG_MODE が config.php で定義されていない場合はここで定義
if (!defined('DEBUG_MODE')) {
    define('DEBUG_MODE', false); // 本番環境では false に設定
}

// ログイン後のメッセージを一時的に保存するセッション変数
$display_message = '';
$message_type = ''; // 'success' or 'error'

// セッションにメッセージがあれば取得し、一度だけ表示
if (isset($_SESSION['admin_login_message'])) {
    $display_message = $_SESSION['admin_login_message']['text'];
    $message_type = $_SESSION['admin_login_message']['type'];
    unset($_SESSION['admin_login_message']); // 表示後削除
}

$submitted_username = ''; // 入力されたユーザー名を再表示するため

// 既にログインしている場合は、管理者ページへリダイレクト
if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
    header('Location: admin.php');
    exit();
}

// POSTリクエストがある場合のみ処理を実行
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    $submitted_username = htmlspecialchars($username);

    if (empty($username) || empty($password)) {
        $_SESSION['admin_login_message'] = ['text' => 'ユーザー名とパスワードを入力してください。', 'type' => 'error'];
        header('Location: admin_login.php'); // エラーメッセージを表示するため同じページにリダイレクト
        exit();
    } else {
        try {
            // cadmin_user_info クラスが存在するか再度確認 (念のため)
            if (!class_exists('cadmin_user_info')) {
                $_SESSION['admin_login_message'] = ['text' => 'システムエラー: クラス cadmin_user_info が見つかりません。', 'type' => 'error'];
                error_log("Class cadmin_user_info not found in admin_login.php POST block.");
                header('Location: admin_login.php');
                exit();
            }

            $admin_db = new cadmin_user_info();
            $admin_user = $admin_db->get_admin_user_by_name(DEBUG_MODE, $username);

            if ($admin_user && password_verify($password, $admin_user['admin_user_pass'])) {
                $_SESSION['admin_logged_in'] = true;
                $_SESSION['admin_user_id'] = $admin_user['admin_user_id'];
                $_SESSION['admin_user_name'] = $admin_user['admin_user_name'];

                header('Location: admin.php');
                exit();
            } else {
                $_SESSION['admin_login_message'] = ['text' => 'ユーザー名またはパスワードが正しくありません。', 'type' => 'error'];
                header('Location: admin_login.php');
                exit();
            }
        } catch (Exception $e) {
            error_log("Admin login process error: " . $e->getMessage());
            $_SESSION['admin_login_message'] = ['text' => 'システムエラーが発生しました。しばらくしてから再度お試しください。', 'type' => 'error'];
            header('Location: admin_login.php');
            exit();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OUR BRAND | 管理者ログイン</title>
    <!-- Google Fonts: Noto Sans JP と Zen Old Mincho を読み込み -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@300;400;500;700&family=Zen+Old+Mincho:wght@400;500;700&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="../admincss/admin.css">
    <link rel="stylesheet" href="../admincss/login.css">
    <style>
        /* エラーメッセージのスタイル */
        .error-message {
            color: #dc3545;
            /* 赤色 */
            text-align: center;
            margin-bottom: 15px;
            font-weight: bold;
        }

        /* カスタムメッセージボックスのスタイル */
        .custom-message-box {
            position: fixed;
            top: 20px;
            left: 50%;
            transform: translateX(-50%);
            padding: 15px 25px;
            border-radius: 8px;
            font-size: 1.6rem;
            color: #fff;
            z-index: 10000;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
            opacity: 0;
            animation: fadeInOut 3s forwards;
            min-width: 300px;
            text-align: center;
        }

        .custom-message-box.success {
            background-color: #28a745;
            /* 緑色 */
        }

        .custom-message-box.error {
            background-color: #dc3545;
            /* 赤色 */
        }

        @keyframes fadeInOut {
            0% {
                opacity: 0;
                transform: translateX(-50%) translateY(-20px);
            }

            10% {
                opacity: 1;
                transform: translateX(-50%) translateY(0);
            }

            90% {
                opacity: 1;
                transform: translateX(-50%) translateY(0);
            }

            100% {
                opacity: 0;
                transform: translateX(-50%) translateY(-20px);
            }
        }
    </style>
</head>

<body>
    <?php
    // ここではエラーメッセージを直接 echo せずに、JavaScriptで表示するためPHPの変数をJavaScriptに渡す
    // JavaScriptで表示するため、HTML要素に直接PHP変数を出力しない
    ?>

    <!-- ヘッダー -->
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

    <!-- ログインフォーム全体を囲むコンテナ -->
    <div class="login-container">
        <!-- ログインページのタイトル -->
        <h1 class="login-title">OUR BRAND 管理者ログイン</h1>

        <!-- ログインフォーム -->
        <!-- action="admin_login.php" で同じページに送信し、処理を行う -->
        <!-- method="POST" でデータを安全に送信 -->
        <form action="admin_login.php" method="POST" class="login-form">
            <!-- ユーザー名入力グループ -->
            <div class="form-group">
                <label for="username">ユーザー名:</label>
                <input type="text" id="username" name="username" required value="<?= $submitted_username ?>">
            </div>
            <!-- パスワード入力グループ -->
            <div class="form-group">
                <label for="password">パスワード:</label>
                <input type="password" id="password" name="password" required>
            </div>
            <!-- ログインボタン -->
            <button type="submit" class="login-button">ログイン</button>
        </form>
    </div>

    <!-- フッター -->
    <footer class="admin-footer">
        <div class="admin-footer__inner">
            <p class="admin-footer__copyright">© OUR BRAND Admin All Rights Reserved.</p>
        </div>
    </footer>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // カスタムメッセージボックスを表示する関数
            function displayMessage(message, type) {
                if (!message) return; // メッセージがない場合は何もしない

                const messageBox = document.createElement('div');
                messageBox.classList.add('custom-message-box');
                if (type === 'success') {
                    messageBox.classList.add('success');
                } else if (type === 'error') {
                    messageBox.classList.add('error');
                }
                messageBox.textContent = message;

                const existingMessageBox = document.querySelector('.custom-message-box');
                if (existingMessageBox) {
                    existingMessageBox.remove();
                }

                document.body.appendChild(messageBox);

                setTimeout(() => {
                    messageBox.remove();
                }, 3000); // 3秒後に消える
            }

            // PHPからJavaScriptにメッセージを渡す
            const phpMessage = <?php echo json_encode($display_message); ?>;
            const phpMessageType = <?php echo json_encode($message_type); ?>;

            displayMessage(phpMessage, phpMessageType);

            // URLパラメータをチェックし、メッセージを表示 (管理者ユーザー登録時など)
            const urlParams = new URLSearchParams(window.location.search);
            if (urlParams.get('admin_registered') === 'true') {
                displayMessage('管理者ユーザーが登録されました！', 'success');
                history.replaceState(null, '', window.location.pathname);
            }
        });
    </script>
</body>

</html>