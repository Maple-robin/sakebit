<?php
/*!
@file login.php
@brief ログインページと処理
@copyright Copyright (c) 2024 Your Name.
*/

// ★注意: このページのPHPロジックはヘッダー出力前に実行する必要があるため、
// DB接続とセッション開始はこのファイルで先に行います。
// header.php内のrequire_onceとsession_start()は、重複実行が防止されるので問題ありません。

// セッションを開始
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// 共通DBファイルをインクルード
require_once __DIR__ . '/common/contents_db.php';

$debug_mode = false; // デバッグモードのオン/オフ

$login_error_message = '';
$submitted_email = ''; // フォームに再表示するためのメールアドレス

// 既にログインしている場合は、トップページにリダイレクト
if (isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit();
}

// POSTリクエストがある場合のみ処理を実行
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // フォームデータの取得とサニタイズ
    $email = $_POST['username'] ?? ''; // HTMLフォームのnameが'username'なので注意
    $password = $_POST['password'] ?? '';

    $submitted_email = htmlspecialchars($email); // 入力されたメールアドレスを保持

    // サーバーサイドでのバリデーション
    if (empty($email) || empty($password)) {
        $login_error_message = 'メールアドレスとパスワードを入力してください。';
    } else {
        $user_db = new cuser_info();
        $user_data = $user_db->get_user_by_email_for_login($debug_mode, $email);

        if ($user_data) {
            // パスワードの検証
            if (password_verify($password, $user_data['user_pass'])) {
                // ログイン成功！セッションにユーザー情報を保存
                $_SESSION['user_id'] = $user_data['user_id'];
                $_SESSION['user_name'] = $user_data['user_name'];
                $_SESSION['user_email'] = $user_data['user_email'];
                
                // ログイン成功後、トップページへリダイレクト
                header('Location: index.php?loggedin=true'); // ログイン成功フラグを追加
                exit();
            } else {
                $login_error_message = 'メールアドレスまたはパスワードが間違っています。';
            }
        } else {
            $login_error_message = 'メールアドレスまたはパスワードが間違っています。';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ログイン | OUR BRAND</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@300;400;500;700&family=Zen+Old+Mincho:wght@400;500;700&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="css/login.css">
    <link rel="stylesheet" href="css/top.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
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
            background-color: #28a745; /* 緑色 */
        }
        .custom-message-box.error {
            background-color: #dc3545; /* 赤色 */
        }
        @keyframes fadeInOut {
            0% { opacity: 0; transform: translateX(-50%) translateY(-20px); }
            10% { opacity: 1; transform: translateX(-50%) translateY(0); }
            90% { opacity: 1; transform: translateX(-50%) translateY(0); }
            100% { opacity: 0; transform: translateX(-50%) translateY(-20px); }
        }
        /* エラーメッセージと成功メッセージのスタイル */
        .error-message, .success-message {
            color: #dc3545; /* 赤色 */
            font-size: 0.9em;
            margin-top: 5px;
            text-align: center;
        }
        .success-message {
            color: #28a745; /* 緑色 */
        }
    </style>
</head>

<body>
    <?php 
    // 共通ヘッダーを読み込む
    require_once 'header.php'; 
    ?>

    <main>
        <div class="login-container">
            <h1 class="login-logo">OUR BRAND</h1>
            <form class="login-form" method="post" action="login.php">
                <?php if (!empty($login_error_message)): ?>
                    <p class="error-message"><?= htmlspecialchars($login_error_message) ?></p>
                <?php endif; ?>
                <?php if (isset($_GET['registered']) && $_GET['registered'] === 'true'): ?>
                    <p class="success-message">新規登録が完了しました！</p>
                <?php endif; ?>
                <div class="form-group">
                    <label for="username">メールアドレス</label>
                    <input type="text" id="username" name="username" required value="<?= $submitted_email ?>">
                </div>
                <div class="form-group">
                    <label for="password">パスワード</label>
                    <input type="password" id="password" name="password" required>
                </div>
                <button type="submit" class="login-button">ログイン</button>
                <div class="login-links">
                    <a href="">パスワードを忘れた場合</a>
                    <a href="signup.php">新規登録はこちら</a>
                </div>
            </form>
        </div>
    </main>

    <?php 
    // 共通フッターを読み込む
    require_once 'footer.php'; 
    ?>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // カスタムメッセージボックスを表示する関数
            function displayMessage(message, type) {
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

                // メッセージはアニメーションで消えるので、JSでの削除は不要
            }

            // 新規登録からのリダイレクトメッセージがあれば表示
            const urlParams = new URLSearchParams(window.location.search);
            if (urlParams.get('registered') === 'true') {
                // PHP側でメッセージを表示しているので、JSでは何もしない
                // もしJSで制御したい場合は、PHPのメッセージを削除して、以下のコメントを外す
                // displayMessage('新規登録が完了しました！', 'success');
                // history.replaceState(null, '', window.location.pathname);
            }
        });
    </script>
    <script src="js/script.js"></script>
</body>

</html>
