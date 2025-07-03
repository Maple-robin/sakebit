<?php
/*!
@file signup.php
@brief 新規登録ページと処理
@copyright Copyright (c) 2024 Your Name.
*/

// ★注意: このページのPHPロジックはヘッダー出力前に実行する必要があるため、
// DB接続とセッション開始はこのファイルで先に行います。
// header.php内のrequire_onceとsession_start()は、重複実行が防止されるので問題ありません。

// セッションを開始 (HTML出力の前に置く)
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// contents_db.php をインクルード
require_once __DIR__ . '/common/contents_db.php';

$debug_mode = false; // デバッグモードのオン/オフ

$signup_success = false;
$signup_error_message = '';

// POSTリクエストがある場合のみ処理を実行
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // フォームデータの取得とサニタイズ
    $username = $_POST['username'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm-password'] ?? '';
    $dob = $_POST['dob'] ?? '';

    // バリデーション
    $isValid = true;
    if ($password !== $confirm_password) {
        $signup_error_message = 'パスワードが一致しません。';
        $isValid = false;
    }
    if ($dob) {
        $birthDate = new DateTime($dob);
        $today = new DateTime();
        $age = $birthDate->diff($today)->y;
        if ($age < 20) {
            $signup_error_message = '20歳未満の方は登録できません。';
            $isValid = false;
        }
    } else {
        $signup_error_message = '生年月日を入力してください。';
        $isValid = false;
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $signup_error_message = '無効なメールアドレス形式です。';
        $isValid = false;
    } else {
        $user_db_check = new cuser_info();
        if ($user_db_check->get_user_by_email($debug_mode, $email)) {
            $signup_error_message = 'このメールアドレスは既に登録されています。';
            $isValid = false;
        }
    }

    if ($isValid) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $user_db = new cuser_info();
        $new_user_id = $user_db->insert_user($debug_mode, $username, $email, $hashed_password, $dob);

        if ($new_user_id) {
            $profile_db = new cuser_profiles();
            $default_icon_url = 'https://placehold.co/100x100/FFD700/000000?text=' . strtoupper(mb_substr($username, 0, 1));
            $default_profile_text = 'お酒と美味しい料理をこよなく愛する' . htmlspecialchars($username) . 'です。\nよろしくお願いします！';
            $profile_db->insert_profile($debug_mode, $new_user_id, $default_icon_url, $default_profile_text);
            
            // 自動ログイン
            $new_user_data = $user_db->get_tgt($debug_mode, $new_user_id);
            if ($new_user_data) {
                $_SESSION['user_id'] = $new_user_data['user_id'];
                $_SESSION['user_name'] = $new_user_data['user_name'];
                $_SESSION['user_email'] = $new_user_data['user_email'];
            }
            
            header('Location: index.php?registered=true');
            exit();
        } else {
            $signup_error_message = 'ユーザー登録に失敗しました。再度お試しください。';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>新規登録 | OUR BRAND</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@300;400;500;700&family=Zen+Old+Mincho:wght@400;500;700&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="css/signup.css">
    <link rel="stylesheet" href="css/top.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        /* カスタムメッセージボックスのスタイル */
        .custom-message-box {
            position: fixed; top: 20px; left: 50%; transform: translateX(-50%);
            padding: 15px 25px; border-radius: 8px; font-size: 1.6rem; color: #fff;
            z-index: 10000; box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
            opacity: 0; animation: fadeInOut 3s forwards; min-width: 300px; text-align: center;
        }
        .custom-message-box.success { background-color: #28a745; }
        .custom-message-box.error { background-color: #dc3545; }
        @keyframes fadeInOut {
            0%, 100% { opacity: 0; transform: translateX(-50%) translateY(-20px); }
            10%, 90% { opacity: 1; transform: translateX(-50%) translateY(0); }
        }
        .error-message { color: #dc3545; font-size: 0.9em; margin-top: 5px; }
    </style>
</head>

<body>
    <?php 
    // 共通ヘッダーを読み込む
    require_once 'header.php'; 
    ?>

    <main>
        <div class="signup-container">
            <h1 class="signup-logo">新規登録</h1>
            <form class="signup-form" method="post" action="signup.php">
                <div class="form-group">
                    <label for="username">ユーザー名</label>
                    <input type="text" id="username" name="username" required value="<?= htmlspecialchars($_POST['username'] ?? '') ?>">
                </div>
                <div class="form-group">
                    <label for="email">メールアドレス</label>
                    <input type="email" id="email" name="email" required value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
                </div>
                <div class="form-group">
                    <label for="password">パスワード</label>
                    <input type="password" id="password" name="password" required>
                </div>
                <div class="form-group">
                    <label for="confirm-password">パスワード（確認用）</label>
                    <input type="password" id="confirm-password" name="confirm-password" required>
                    <p id="password-match-error" class="error-message"></p>
                </div>
                <div class="form-group">
                    <label for="dob">生年月日</label>
                    <input type="date" id="dob" name="dob" required value="<?= htmlspecialchars($_POST['dob'] ?? '') ?>">
                    <p id="age-error" class="error-message"></p>
                </div>
                <button type="submit" class="signup-button">登録</button>
                <div class="signup-links">
                    <a href="login.php">すでにアカウントをお持ちですか？ログインはこちら</a>
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
            const signupForm = document.querySelector('.signup-form');
            const ageErrorMessage = document.getElementById('age-error');
            const passwordMatchErrorMessage = document.getElementById('password-match-error');

            function displayMessage(message, type) {
                const messageBox = document.createElement('div');
                messageBox.classList.add('custom-message-box', type);
                messageBox.textContent = message;
                const existingMessageBox = document.querySelector('.custom-message-box');
                if (existingMessageBox) {
                    existingMessageBox.remove();
                }
                document.body.appendChild(messageBox);
                setTimeout(() => {
                    messageBox.remove();
                }, 3000);
            }

            <?php if (!empty($signup_error_message)): ?>
                displayMessage('<?= htmlspecialchars($signup_error_message) ?>', 'error');
            <?php endif; ?>

            if (signupForm) {
                signupForm.addEventListener('submit', function(event) {
                    ageErrorMessage.textContent = '';
                    passwordMatchErrorMessage.textContent = '';

                    const password = document.getElementById('password').value;
                    const confirmPassword = document.getElementById('confirm-password').value;
                    const dob = document.getElementById('dob').value;

                    let isValidClient = true;

                    if (password !== confirmPassword) {
                        passwordMatchErrorMessage.textContent = 'パスワードが一致しません。';
                        isValidClient = false;
                    }

                    if (dob) {
                        const birthDate = new Date(dob);
                        const today = new Date();
                        let age = today.getFullYear() - birthDate.getFullYear();
                        const m = today.getMonth() - birthDate.getMonth();
                        if (m < 0 || (m === 0 && today.getDate() < birthDate.getDate())) {
                            age--;
                        }
                        if (age < 20) {
                            ageErrorMessage.textContent = '20歳未満の方は登録できません。';
                            isValidClient = false;
                        }
                    } else {
                        ageErrorMessage.textContent = '生年月日を入力してください。';
                        isValidClient = false;
                    }

                    if (!isValidClient) {
                        event.preventDefault();
                        displayMessage('入力内容にエラーがあります。ご確認ください。', 'error');
                    }
                });
            }
        });
    </script>
    <script src="js/script.js"></script>
</body>

</html>
