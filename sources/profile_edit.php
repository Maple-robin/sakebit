<?php
/*!
@file profile_edit.php
@brief プロフィール編集ページと処理
@copyright Copyright (c) 2024 Your Name.
*/

// ★★★ デバッグ用の設定はここから削除してください。本番環境では不要です。 ★★★
// ini_set('display_errors', 1);
// error_reporting(E_ALL);
// ★★★ ここまで削除 ★★★

// セッションを開始
session_start();

// contents_db.php をインクルード
require_once __DIR__ . '/common/contents_db.php';

$debug_mode = false; // デバッグモードのオン/オフ

// ログインしていない場合はログインページへリダイレクト
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$current_user_id = $_SESSION['user_id'];
$user_db = new cuser_info();
$profile_db = new cuser_profiles();

// 現在のユーザー情報とプロフィール情報を取得
$user_data = $user_db->get_tgt($debug_mode, $current_user_id);
$user_profile = $profile_db->get_profile_by_user_id($debug_mode, $current_user_id);

// プロフィール情報が存在しない場合のデフォルト値設定
if (!$user_profile) {
    $user_profile = [
        'profile_icon_url' => 'img/profile_icons/default_user.png', // デフォルトアイコンのパス
        'profile_text' => 'お酒と美味しい料理をこよなく愛する' . htmlspecialchars($user_data['user_name'] ?? 'サンプル太郎') . 'です。' . "\n" .
                          '特に日本酒の奥深さに魅了されており、週末は新しい銘柄を探しに出かけるのが趣味です。' . "\n" .
                          '皆さんとお酒に関する情報交換ができたら嬉しいです！'
    ];
    // 新規登録時に挿入されるはずなので、基本的にはここには来ないが、念のため挿入も試みる
    $profile_db->insert_profile($debug_mode, $current_user_id, $user_profile['profile_icon_url'], $user_profile['profile_text']);
}

$update_success = false;
$error_message = '';

// フォーム送信時の処理
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 送信されたデータを取得
    $new_username = $_POST['username'] ?? '';
    $new_bio = $_POST['bio'] ?? '';
    $new_icon_url = $user_profile['profile_icon_url']; // デフォルトは現在のアイコンURL

    // バリデーション
    if (empty($new_username)) {
        $error_message = 'ユーザー名を入力してください。';
    } elseif (mb_strlen($new_username, 'UTF-8') > 50) { // ユーザー名の最大文字数を設定 (例: 50文字)
        $error_message = 'ユーザー名は50文字以内で入力してください。';
    } elseif (mb_strlen($new_bio, 'UTF-8') > 200) { // 自己紹介の最大文字数を設定 (例: 200文字)
        $error_message = '自己紹介は200文字以内で入力してください。';
    }

    // アイコン画像のアップロード処理
    // ファイルが選択され、かつエラーがない場合のみ処理
    if (empty($error_message) && isset($_FILES['user_icon']) && $_FILES['user_icon']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = __DIR__ . '/img/profile_icons/'; // プロフィールアイコンの保存先ディレクトリ
        // mkdirの権限は適切に設定済みであることを前提としますが、念のため再確認を推奨
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true); // ディレクトリが存在しない場合は作成
        }

        $file_tmp_name = $_FILES['user_icon']['tmp_name'];
        $file_name = $_FILES['user_icon']['name'];
        $file_size = $_FILES['user_icon']['size'];
        $file_type = $_FILES['user_icon']['type'];
        $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

        // 許可するファイル拡張子
        $allowed_ext = ['jpg', 'jpeg', 'png', 'gif'];
        // 許可するMIMEタイプ
        $allowed_mime = ['image/jpeg', 'image/png', 'image/gif'];
        // 最大ファイルサイズ (例: 5MB)
        $max_file_size = 5 * 1024 * 1024;

        if (!in_array($file_ext, $allowed_ext) || !in_array($file_type, $allowed_mime)) {
            $error_message = '許可されていないファイル形式です。JPEG, PNG, GIFのみアップロードできます。';
        } elseif ($file_size > $max_file_size) {
            $error_message = 'ファイルサイズが大きすぎます。5MB以下にしてください。';
        } else {
            // ユニークなファイル名を生成
            $unique_file_name = uniqid('icon_', true) . '.' . $file_ext;
            $destination_path = $upload_dir . $unique_file_name;
            $relative_path = 'img/profile_icons/' . $unique_file_name; // データベースに保存する相対パス

            if (move_uploaded_file($file_tmp_name, $destination_path)) {
                $new_icon_url = $relative_path; // 新しいアイコンのURLを設定
            } else {
                // ファイル移動が失敗した場合の具体的なエラーメッセージ
                error_log("Failed to move uploaded file: " . $file_tmp_name . " to " . $destination_path . " - Last error: " . (error_get_last()['message'] ?? 'Unknown error'));
                $error_message = 'ファイルのアップロードに失敗しました。(サーバーエラー)';
            }
        }
    } else if (isset($_FILES['user_icon']) && $_FILES['user_icon']['error'] !== UPLOAD_ERR_NO_FILE) {
        // UPLOAD_ERR_NO_FILE (ファイルが選択されていない) 以外のPHPエラーがあった場合
        switch ($_FILES['user_icon']['error']) {
            case UPLOAD_ERR_INI_SIZE:
            case UPLOAD_ERR_FORM_SIZE:
                $error_message = 'アップロードされたファイルが大きすぎます。';
                break;
            case UPLOAD_ERR_PARTIAL:
                $error_message = 'ファイルの一部しかアップロードされませんでした。';
                break;
            case UPLOAD_ERR_NO_TMP_DIR:
                $error_message = '一時フォルダがありません。';
                break;
            case UPLOAD_ERR_CANT_WRITE:
                $error_message = 'ディスクへの書き込みに失敗しました。'; // パーミッション関連のエラーの可能性が高い
                break;
            case UPLOAD_ERR_EXTENSION:
                $error_message = 'PHP拡張モジュールによってアップロードが中断されました。';
                break;
            default:
                $error_message = '不明なファイルアップロードエラーが発生しました。';
                break;
        }
    }


    if (empty($error_message)) {
        // ユーザー名 (user_infoテーブル) の更新
        $username_updated = $user_db->update_user_name($debug_mode, $current_user_id, $new_username);

        // プロフィール情報 (user_profilesテーブル) の更新
        $profile_updated = $profile_db->update_profile($debug_mode, $current_user_id, $new_icon_url, $new_bio);

        if ($username_updated && $profile_updated) {
            // セッションのユーザー名も更新
            $_SESSION['user_name'] = $new_username;
            $update_success = true;
        } else {
            $error_message = 'プロフィールの更新に失敗しました。';
        }
    }

    // 更新後のリダイレクト
    if ($update_success) {
        header('Location: MyPage.php?profile_updated=true');
        exit();
    } else {
        // エラーがある場合は、エラーメッセージをGETパラメータとして渡し、現在のページに留まる
        header('Location: profile_edit.php?profile_update_error=' . urlencode($error_message));
        exit();
    }
}

// フォームに表示するデータ
$display_username = htmlspecialchars($user_data['user_name'] ?? '');
$display_profile_icon_url = htmlspecialchars($user_profile['profile_icon_url'] ?? 'img/profile_icons/default_user.png');
$display_profile_text = htmlspecialchars(str_replace('\n', "\n", $user_profile['profile_text'] ?? ''));

?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>プロフィール編集 | OUR BRAND</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@300;400;500;700&family=Zen+Old+Mincho:wght@400;500;700&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="css/profile_edit.css">
    <link rel="stylesheet" href="css/MyPage.css">
    <link rel="stylesheet" href="css/top.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        /* カスタムメッセージボックスのスタイル (MyPage.phpと連携) */
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
        }
        .custom-message-box.error {
            background-color: #dc3545;
        }
        @keyframes fadeInOut {
            0% { opacity: 0; transform: translateX(-50%) translateY(-20px); }
            10% { opacity: 1; transform: translateX(-50%) translateY(0); }
            90% { opacity: 1; transform: translateX(-50%) translateY(0); }
            100% { opacity: 0; transform: translateX(-50%) translateY(-20px); }
        }
        .error-message { /* PHP側でバリデーションエラーがあった場合 */
            color: #dc3545;
            font-size: 0.9em;
            margin-top: 5px;
            text-align: center;
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
            <!-- ↓ここから追加 -->
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
            <!-- ↑ここまで追加 -->
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
        <div class="mypage-container">
            <h1 class="page-title">
                <span class="en">EDIT PROFILE</span>
                <span class="ja">( プロフィール編集 )</span>
            </h1>

            <?php if (!empty($error_message)): ?>
                <p class="error-message"><?= htmlspecialchars($error_message) ?></p>
            <?php endif; ?>

            <form class="profile-edit-form" method="post" action="profile_edit.php" enctype="multipart/form-data">
                <div class="profile-edit-inner">
                    <div class="profile-edit-item profile-edit-icon">
                        <label for="user-icon">
                            <img src="<?= $display_profile_icon_url ?>" alt="ユーザーアイコン"
                                class="profile-icon-preview">
                            <input type="file" id="user-icon" name="user_icon" accept="image/*" style="display: none;">
                            <button type="button" class="btn-change-icon">アイコンを変更</button>
                        </label>
                        <p class="icon-guidance">推奨サイズ: 150x150px / JPEG, PNG, GIF</p>
                    </div>

                    <div class="profile-edit-item">
                        <label for="username" class="edit-label">ユーザー名</label>
                        <!-- ユーザー名はここでは編集可とする -->
                        <input type="text" id="username" name="username" class="edit-input" value="<?= $display_username ?>" required>
                    </div>

                    <!-- 誕生日フィールドは削除 -->
                    <!-- <div class="profile-edit-item">
                        <label for="birthday" class="edit-label">誕生日</label>
                        <input type="date" id="birthday" name="birthday" class="edit-input" value="<?= $display_birthday ?>">
                    </div> -->

                    <div class="profile-edit-item">
                        <label for="bio" class="edit-label">自己紹介</label>
                        <textarea id="bio" name="bio" class="edit-textarea" rows="5"
                            placeholder="自己紹介を入力してください。"><?= $display_profile_text ?></textarea>
                        <p class="char-count"><span id="bio-current-char"></span> / 200文字</p>
                    </div>

                    <div class="profile-edit-actions">
                        <button type="submit" class="btn-save-profile">変更を保存</button>
                        <button type="button" class="btn-cancel-profile" onclick="history.back()">キャンセル</button>
                    </div>
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
    <script src="js/script.js"></script>
    <script src="js/profile_edit.js"></script>
</body>

</html>
