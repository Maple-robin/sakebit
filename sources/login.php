<?php
/*!
@file login.php
@brief ログインページと処理
@copyright Copyright (c) 2024 Your Name.
*/

// セッションを開始
session_start();

// contents_db.php をインクルード
require_once __DIR__ . '/common/contents_db.php';

$debug_mode = false; // デバッグモードのオン/オフ

$login_error_message = '';
$login_success = false;
$submitted_email = ''; // フォームに再表示するためのメールアドレス

// 既にログインしている場合は、トップページにリダイレクト
if (isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit();
}

// 新規登録からのリダイレクトの場合、メッセージを表示
if (isset($_GET['registered']) && $_GET['registered'] === 'true') {
    // ログインページで新規登録完了メッセージを表示する
    // ただし、このページに留まる場合はdisplayMessageを呼ぶ必要があるため、
    // JavaScriptで制御します。PHPはリダイレクトのみ。
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
                // 必要に応じて他の情報もセッションに保存

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
        /* カスタムメッセージボックスのスタイル（signup.php と同様）*/
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

    <nav class="sp-menu">
        <div class="sp-menu__header">
            <?php if (isset($_SESSION['user_id'])): // ログイン状態をチェック ?>
                <a href="logout.php" class="sp-menu__login" style="cursor:pointer;">
                    <i class="fas fa-user-circle"></i> ログアウト
                </a>
            <?php else: ?>
                <a href="login.php" class="sp-menu__login js-login-btn" style="cursor:pointer;">
                    <i class="fas fa-user-circle"></i> ログイン
                </a>
            <?php endif; ?>
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

                setTimeout(() => {
                    messageBox.remove();
                }, 3000); // 3秒後に消える
            }

            // 新規登録からのリダイレクトメッセージがあれば表示
            const urlParams = new URLSearchParams(window.location.search);
            if (urlParams.get('registered') === 'true') {
                displayMessage('新規登録が完了しました！', 'success');
                // メッセージ表示後、URLからパラメータを削除（任意）
                history.replaceState(null, '', window.location.pathname);
            }
             // ログイン成功からのリダイレクトメッセージがあれば表示 (login.php から index.php にリダイレクト後)
            if (urlParams.get('loggedin') === 'true') {
                displayMessage('ログインしました！', 'success');
                history.replaceState(null, '', window.location.pathname);
            }
            // ログアウト成功からのメッセージ表示 (logout.php から index.php にリダイレクト後)
            if (urlParams.get('loggedout') === 'true') {
                displayMessage('ログアウトしました！', 'success');
                history.replaceState(null, '', window.location.pathname);
            }
        });
    </script>
    <script src="js/script.js"></script>
</body>

</html>
