<?php
/*!
@file signup.php
@brief 新規登録ページと処理
@copyright Copyright (c) 2024 Your Name.
*/

// 必要に応じて、エラー表示設定やセッション開始などを行う
// ini_set('display_errors', 1);
// error_reporting(E_ALL);
// session_start();

// contents_db.php をインクルード
// crecord と cutil クラスがこのファイル内で定義されているか、
// または別途インクルードされていることを確認してください。
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
    $dob = $_POST['dob'] ?? ''; // YYYY-MM-DD形式

    // サーバーサイドでのバリデーション
    $isValid = true;

    // 1. パスワードの一致確認
    if ($password !== $confirm_password) {
        $signup_error_message = 'パスワードが一致しません。';
        $isValid = false;
    }

    // 2. 生年月日（年齢）の確認
    if ($dob) {
        $birthDate = new DateTime($dob);
        $today = new DateTime();
        $age = $birthDate->diff($today)->y; // 年齢を計算

        if ($age < 20) {
            $signup_error_message = '20歳未満の方は登録できません。';
            $isValid = false;
        }
    } else {
        $signup_error_message = '生年月日を入力してください。';
        $isValid = false;
    }

    // 3. メールアドレスの形式と重複チェック
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $signup_error_message = '無効なメールアドレス形式です。';
        $isValid = false;
    } else {
        $user_db = new cuser_info();
        if ($user_db->get_user_by_email($debug_mode, $email)) {
            $signup_error_message = 'このメールアドレスは既に登録されています。';
            $isValid = false;
        }
    }

    // 全てのバリデーションが成功した場合、データベースに挿入
    if ($isValid) {
        // パスワードのハッシュ化
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        $user_db = new cuser_info();
        $result = $user_db->insert_user($debug_mode, $username, $email, $hashed_password, $dob);

        if ($result) {
            $signup_success = true;
            // 登録成功後、index.php へリダイレクト
            // JavaScriptでのメッセージ表示後、リダイレクトするように変更したため、PHPでの即時リダイレクトはコメントアウト
            // header('Location: index.php?registered=true');
            // exit();
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
            min-width: 300px; /* メッセージボックスの最小幅 */
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
        .error-message {
            color: #dc3545; /* 赤色 */
            font-size: 0.9em;
            margin-top: 5px;
        }
    </style>
</head>

<body>
    <!-- 共通ヘッダー：index.phpからコピー -->
    <header class="header">
        <div class="header__inner">
            <!-- ハンバーガーメニューを左端に配置 -->
            <button class="hamburger-menu">
                <span></span>
                <span></span>
                <span></span>
            </button>
            <!-- ロゴを中央に配置 -->
            <h1 class="header__logo">
                <a href="index.php">OUR BRAND</a>
            </h1>
            <!-- ナビゲーションとアイコンを右端に配置 -->
            <nav class="header__nav">
                <ul class="nav__list pc-only">
                    <li><a href="products_list.php">商品一覧</a></li>
                    <li><a href="contact.php">お問い合わせ</a></li>
                </ul>
                <div class="header__icons">
                    <a href="wishlist.php" class="header__icon-link">
                        <i class="fas fa-heart"></i>
                    </a>
                    <a href="cart.php" class="header__icon-link">
                        <i class="fas fa-shopping-cart"></i>
                    </a>
                </div>
            </nav>
        </div>
    </header>

    <!-- スマホ用メニューも必要なら同様に -->
    <nav class="sp-menu">
        <div class="sp-menu__header">
            <div class="sp-menu__login js-login-btn" style="cursor:pointer;">
                <i class="fas fa-user-circle"></i> ログイン
            </div>
        </div>
        <div class="sp-menu__search">
            <input type="text" placeholder="検索...">
            <button type="submit"><i class="fas fa-search"></i></button>
        </div>
        <ul class="sp-menu__list">
            <li class="sp-menu__category-toggle">
                商品カテゴリ <i class="fas fa-chevron-down category-icon"></i>
                <ul class="sp-menu__sub-list">
                    <li><a href="products_list.php?category=日本酒">日本酒</a></li>
                    <li><a href="products_list.php?category=中国酒">中国酒</a></li>
                    <li><a href="products_list.php?category=梅酒">梅酒</a></li>
                    <li><a href="products_list.php?category=缶チューハイ">缶チューハイ</a></li>
                    <li><a href="products_list.php?category=焼酎">焼酎</a></li>
                    <li><a href="products_list.php?category=ウィスキー">ウィスキー</a></li>
                    <li><a href="products_list.php?category=スピリッツ">スピリッツ</a></li>
                    <li><a href="products_list.php?category=リキュール">リキュール</a></li>
                    <li><a href="products_list.php?category=ワイン">ワイン</a></li>
                    <li><a href="products_list.php?category=ビール">ビール</a></li>
                </ul>
            </li>
            <li class="sp-menu__category-toggle">
                商品タグ <i class="fas fa-chevron-down category-icon"></i>
                <ul class="sp-menu__sub-list">
                    <li><a href="products_list.php?tag=初心者向け">初心者向け</a></li>
                    <li><a href="products_list.php?tag=甘口">甘口</a></li>
                    <li><a href="products_list.php?tag=辛口">辛口</a></li>
                    <li><a href="products_list.php?tag=度数低め">度数低め</a></li>
                    <li><a href="products_list.php?tag=度数高め">度数高め</a></li>
                </ul>
            </li>
            <li class="sp-menu__item"><a href="posts.php">投稿ページ</a></li>
            <li class="sp-menu__item"><a href="MyPage.php">マイページ</a></li>
        </ul>
        <div class="sp-menu__divider"></div>
        <ul class="sp-menu__list sp-menu__list--bottom">
            <li class="sp-menu__item"><a href="faq.php">よくある質問</a></li>
            <li class="sp-menu__item"><a href="contact.php">お問い合わせ</a></li>
        </ul>
    </nav>

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

    <footer class="footer">
        <div class="footer__inner">
            <ul class="footer__nav">
                <li>
                    <span class="footer__nav-title">商品一覧</span>
                    <ul class="footer__subnav">
                        <li><a href="products_list.php?category=日本酒">日本酒</a></li>
                        <li><a href="products_list.php?category=中国酒">中国酒</a></li>
                        <li><a href="products_list.php?category=梅酒">梅酒</a></li>
                        <li><a href="products_list.php?category=缶チューハイ">缶チューハイ</a></li>
                        <li><a href="products_list.php?category=焼酎">焼酎</a></li>
                        <li><a href="products_list.php?category=ウィスキー">ウィスキー</a></li>
                        <li><a href="products_list.php?category=スピリッツ">スピリッツ</a></li>
                        <li><a href="products_list.php?category=リキュール">リキュール</a></li>
                        <li><a href="products_list.php?category=ワイン">ワイン</a></li>
                        <li><a href="products_list.php?category=ビール">ビール</a></li>
                    </ul>
                </li>
                <li><a href="faq.php">よくあるご質問／お問合せ</a></li>
                <li><a href="MyPage.php">会員登録・ログイン</a></li>
                <li><a href="history.php">購入履歴</a></li>
                <li><a href="cart.php">買い物かごを見る</a></li>
                <li><a href="privacy.php">プライバシーポリシー</a></li>
                <li><a href="terms.php">利用規約</a></li>
            </ul>
            <div class="footer__logo" style="margin: 24px 0 12px;">
                <a href="index.php">
                    <img src="img/logo.png" alt="OUR BRAND" style="height:32px;">
                </a>
            </div>
            <p class="footer__copyright">© OUR BRAND All Rights Reserved.</p>
        </div>
    </footer>

    <script>
        // DOMContentLoadedは、HTMLの読み込みが完了したときに実行されます。
        document.addEventListener('DOMContentLoaded', function() {
            // 新規登録フォームの処理
            const signupForm = document.querySelector('.signup-form');
            const ageErrorMessage = document.getElementById('age-error');
            const passwordMatchErrorMessage = document.getElementById('password-match-error');

            // PHPからのメッセージをJavaScriptで表示するための要素
            const phpMessageDiv = document.createElement('div');
            phpMessageDiv.id = 'php-message-box';
            document.body.appendChild(phpMessageDiv);

            // PHPからの登録結果メッセージを表示
            <?php if ($signup_success): ?>
                displayMessage('登録が完了しました！', 'success'); // ★メッセージを「登録が完了しました！」のみに変更
                setTimeout(function() {
                    window.location.href = 'index.php?registered=true'; // index.phpへリダイレクト
                }, 3000); // 3秒後にリダイレクト
            <?php elseif (!empty($signup_error_message)): ?>
                displayMessage('<?= htmlspecialchars($signup_error_message) ?>', 'error');
            <?php endif; ?>


            if (signupForm) {
                signupForm.addEventListener('submit', function(event) {
                    // クライアントサイドでのバリデーション
                    // サーバーサイドでもバリデーションを行うため、ここで阻止するのは
                    // JavaScriptでの入力チェックが主な目的になります。
                    // 最終的な送信はPHPに任せます。

                    // エラーメッセージをリセット
                    ageErrorMessage.textContent = '';
                    passwordMatchErrorMessage.textContent = '';

                    // 入力値の取得
                    const password = document.getElementById('password').value;
                    const confirmPassword = document.getElementById('confirm-password').value;
                    const dob = document.getElementById('dob').value; // YYYY-MM-DD形式

                    let isValidClient = true; // クライアントサイドのバリデーション状態

                    // 1. パスワードの一致確認
                    if (password !== confirmPassword) {
                        passwordMatchErrorMessage.textContent = 'パスワードが一致しません。';
                        isValidClient = false;
                    }

                    // 2. 生年月日（年齢）の確認
                    if (dob) {
                        const birthDate = new Date(dob);
                        const today = new Date();
                        let age = today.getFullYear() - birthDate.getFullYear();
                        const m = today.getMonth() - birthDate.getMonth();
                        if (m < 0 || (m === 0 && today.getDate() < birthDate.getDate())) {
                            age--; // 誕生日がまだ来ていない場合
                        }

                        if (age < 20) {
                            ageErrorMessage.textContent = '20歳未満の方は登録できません。';
                            isValidClient = false;
                        }
                    } else {
                        ageErrorMessage.textContent = '生年月日を入力してください。';
                        isValidClient = false;
                    }

                    // クライアントサイドバリデーションが失敗した場合、フォーム送信を阻止
                    if (!isValidClient) {
                        event.preventDefault(); // フォームのデフォルト送信を防ぐ
                        displayMessage('入力内容にエラーがあります。ご確認ください。', 'error');
                    }
                    // クライアントサイドバリデーションが成功した場合は、フォームはそのまま送信される
                });
            }

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

                // 既存のメッセージボックスがあれば削除
                const existingMessageBox = document.querySelector('.custom-message-box');
                if (existingMessageBox) {
                    existingMessageBox.remove();
                }

                document.body.appendChild(messageBox);

                // メッセージボックスを数秒後に非表示にする
                setTimeout(() => {
                    messageBox.remove();
                }, 3000); // 3秒後に消える
            }
        });
    </script>
    <!-- js/script.js も引き続き必要に応じてインクルードしてください -->
    <script src="js/script.js"></script>
</body>

</html>
