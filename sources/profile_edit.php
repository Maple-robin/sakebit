<?php
/*!
@file profile_edit.php
@brief プロフィール編集ページと処理
@copyright Copyright (c) 2024 Your Name.
*/

// ★注意: このページのPHPロジックはヘッダー出力前に実行する必要があるため、
// DB接続とセッション開始はこのファイルで先に行います。
// header.php内のrequire_onceとsession_start()は、重複実行が防止されるので問題ありません。

// セッションを開始
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// 必要なファイルをインクルード
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
    $icon_text = strtoupper(mb_substr($user_data['user_name'] ?? 'U', 0, 1, 'UTF-8'));
    $user_profile = [
        'profile_icon_url' => 'https://placehold.co/150x150/5CB85C/FFFFFF?text=' . $icon_text,
        'profile_text' => 'お酒と美味しい料理をこよなく愛する' . htmlspecialchars($user_data['user_name'] ?? 'サンプル太郎') . 'です。'
    ];
    $profile_db->insert_profile($debug_mode, $current_user_id, $user_profile['profile_icon_url'], $user_profile['profile_text']);
}

$error_message = '';

// フォーム送信時の処理
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $new_username = $_POST['username'] ?? '';
    $new_bio = $_POST['bio'] ?? '';
    $new_icon_url = $user_profile['profile_icon_url'];

    // バリデーション
    if (empty($new_username)) {
        $error_message = 'ユーザー名を入力してください。';
    } elseif (mb_strlen($new_username, 'UTF-8') > 50) {
        $error_message = 'ユーザー名は50文字以内で入力してください。';
    } elseif (mb_strlen($new_bio, 'UTF-8') > 200) {
        $error_message = '自己紹介は200文字以内で入力してください。';
    }

    // アイコン画像のアップロード処理
    if (empty($error_message) && isset($_FILES['user_icon']) && $_FILES['user_icon']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = __DIR__ . '/img/profile_icons/';
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }

        $file_tmp_name = $_FILES['user_icon']['tmp_name'];
        $file_name = $_FILES['user_icon']['name'];
        $file_size = $_FILES['user_icon']['size'];
        $file_type = mime_content_type($file_tmp_name);
        $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

        $allowed_ext = ['jpg', 'jpeg', 'png', 'gif'];
        $allowed_mime = ['image/jpeg', 'image/png', 'image/gif'];
        $max_file_size = 5 * 1024 * 1024; // 5MB

        if (!in_array($file_ext, $allowed_ext) || !in_array($file_type, $allowed_mime)) {
            $error_message = '許可されていないファイル形式です。';
        } elseif ($file_size > $max_file_size) {
            $error_message = 'ファイルサイズが大きすぎます(5MB以下)。';
        } else {
            $unique_file_name = uniqid('icon_', true) . '.' . $file_ext;
            $destination_path = $upload_dir . $unique_file_name;
            $relative_path = 'img/profile_icons/' . $unique_file_name;

            if (move_uploaded_file($file_tmp_name, $destination_path)) {
                $new_icon_url = $relative_path;
            } else {
                $error_message = 'ファイルのアップロードに失敗しました。';
            }
        }
    } elseif (isset($_FILES['user_icon']) && $_FILES['user_icon']['error'] !== UPLOAD_ERR_NO_FILE) {
        $error_message = 'ファイルアップロードエラーが発生しました。';
    }

    if (empty($error_message)) {
        $username_updated = $user_db->update_user_name($debug_mode, $current_user_id, $new_username);
        $profile_updated = $profile_db->update_profile($debug_mode, $current_user_id, $new_icon_url, $new_bio);

        if ($username_updated && $profile_updated) {
            $_SESSION['user_name'] = $new_username;
            header('Location: MyPage.php?profile_updated=true');
            exit();
        } else {
            $error_message = 'プロフィールの更新に失敗しました。';
        }
    }
    // エラーがある場合は、このままページを表示してエラーメッセージを見せる
}

// GETパラメータでエラーメッセージが渡された場合
if (isset($_GET['profile_update_error'])) {
    $error_message = urldecode($_GET['profile_update_error']);
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
    <title>プロフィール編集 | SAKE BIT</title>
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
        .error-message {
            color: #dc3545;
            background-color: #f8d7da;
            border: 1px solid #f5c6cb;
            padding: 10px 15px;
            border-radius: 5px;
            margin-bottom: 20px;
            text-align: center;
        }
    </style>
</head>

<body>
    <?php 
    // 共通ヘッダーを読み込む
    require_once 'header.php'; 
    ?>

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
                        <input type="text" id="username" name="username" class="edit-input" value="<?= $display_username ?>" required>
                    </div>

                    <div class="profile-edit-item">
                        <label for="bio" class="edit-label">自己紹介</label>
                        <textarea id="bio" name="bio" class="edit-textarea" rows="5"
                            placeholder="自己紹介を入力してください。"><?= $display_profile_text ?></textarea>
                        <p class="char-count"><span id="bio-current-char">0</span> / 200文字</p>
                    </div>

                    <div class="profile-edit-actions">
                        <button type="submit" class="btn-save-profile">変更を保存</button>
                        <button type="button" class="btn-cancel-profile" onclick="history.back()">キャンセル</button>
                    </div>
                </div>
            </form>
        </div>
    </main>

    <?php 
    // 共通フッターを読み込む
    require_once 'footer.php'; 
    ?>

    <script src="js/script.js"></script>
    <script src="js/profile_edit.js"></script>
</body>

</html>
