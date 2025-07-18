<?php
/*!
@file MyPage.php
@brief マイページ
@copyright Copyright (c) 2024 Your Name.
*/

// ★注意: このページのPHPロジックはヘッダー出力前に実行する必要があるため、
// DB接続とセッション開始はこのファイルで先に行います。
// header.php内のrequire_onceとsession_start()は、重複実行が防止されるので問題ありません。

// セッションを開始 (HTML出力の前に置く)
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// 必要なファイルをインクルード
require_once __DIR__ . '/common/contents_db.php';
require_once __DIR__ . '/common/config.php'; 

// DEBUG_MODE が config.php で定義されていない場合はここで定義
if (!defined('DEBUG_MODE')) {
    define('DEBUG_MODE', false);
}
$debug_mode = DEBUG_MODE;

// ログインしていない場合はログインページへリダイレクト
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$current_user_id = $_SESSION['user_id'];
$user_db = new cuser_info();
$profile_db = new cuser_profiles();
$posts_db = new cposts();
$post_images_db = new cpost_images();
$good_db = new cgood();
$heart_db = new cheart();

// ユーザープロフィール情報と基本情報を取得
$user_data = $user_db->get_tgt($debug_mode, $current_user_id);
$user_profile = $profile_db->get_profile_by_user_id($debug_mode, $current_user_id);

// プロフィール情報が存在しない場合のデフォルト値設定と挿入
if (!$user_profile) {
    $icon_text = strtoupper(mb_substr($user_data['user_name'] ?? 'U', 0, 1, 'UTF-8'));
    $user_profile = [
        'profile_icon_url' => 'https://placehold.co/48x48/5CB85C/FFFFFF?text=' . $icon_text,
        'profile_text' => 'お酒と美味しい料理をこよなく愛する' . htmlspecialchars($user_data['user_name'] ?? 'サンプル太郎') . 'です。' . "\n" .
                          '特に日本酒の奥深さに魅了されており、週末は新しい銘柄を探しに出かけるのが趣味です。' . "\n" .
                          '皆さんとお酒に関する情報交換ができたら嬉しいです！'
    ];
    $profile_db->insert_profile($debug_mode, $current_user_id, $user_profile['profile_icon_url'], $user_profile['profile_text']);
}

// 投稿データを整形してJavaScriptに渡すヘルパー関数
function format_posts_for_js($debug_mode, $post_ids, $current_user_id, $posts_db, $post_images_db, $user_info_db, $user_profiles_db, $good_db, $heart_db) {
    $formatted_posts = [];
    if (empty($post_ids)) {
        return $formatted_posts;
    }
    $unique_post_ids = array_unique(array_map('intval', $post_ids));
    $posts_raw = $posts_db->get_posts_by_ids($debug_mode, $unique_post_ids);
    $posts_indexed = [];
    foreach ($posts_raw as $post) {
        $posts_indexed[$post['post_id']] = $post;
    }

    foreach ($unique_post_ids as $post_id) {
        if (!isset($posts_indexed[$post_id])) continue;
        $post = $posts_indexed[$post_id];
        $images = $post_images_db->get_images_by_post_id($debug_mode, $post['post_id']);
        $image_paths = array_map(fn($img) => htmlspecialchars($img['image_path']), $images);
        $post_user_data = $user_info_db->get_tgt($debug_mode, $post['user_id']);
        $post_user_name = $post_user_data ? htmlspecialchars($post_user_data['user_name']) : '名無しユーザー';
        $post_user_profile = $user_profiles_db->get_profile_by_user_id($debug_mode, $post['user_id']);
        $user_icon_text = strtoupper(mb_substr($post_user_name, 0, 1, 'UTF-8'));
        $post_user_icon_url = $post_user_profile && !empty($post_user_profile['profile_icon_url']) ? htmlspecialchars($post_user_profile['profile_icon_url']) : 'https://placehold.co/40x40/5CB85C/FFFFFF?text=' . $user_icon_text;
        $likes_count = $good_db->count_good_by_post_id($debug_mode, $post['post_id']);
        $hearts_count = $heart_db->count_heart_by_post_id($debug_mode, $post['post_id']);
        $is_liked_by_current_user = $current_user_id ? $good_db->is_good_by_user($debug_mode, $current_user_id, $post['post_id']) : false;
        $is_hearted_by_current_user = $current_user_id ? $heart_db->is_heart_by_user($debug_mode, $current_user_id, $post['post_id']) : false;

        $formatted_posts[] = [
            'id' => $post['post_id'],
            'userIcon' => $post_user_icon_url,
            'userName' => $post_user_name,
            'title' => htmlspecialchars($post['post_title']),
            'content' => htmlspecialchars($post['post_content']),
            'images' => $image_paths,
            'likes' => $likes_count,
            'hearts' => $hearts_count,
            'isLiked' => $is_liked_by_current_user,
            'isHearted' => $is_hearted_by_current_user,
            'isMine' => ($post['user_id'] == $current_user_id)
        ];
    }
    return $formatted_posts;
}

// --- データ取得 ---
$my_posts_raw = $posts_db->get_posts_by_user_id($debug_mode, $current_user_id);
$my_posts_raw_ids = array_map(fn($p) => $p['post_id'], $my_posts_raw);
$my_posts_for_js = format_posts_for_js($debug_mode, $my_posts_raw_ids, $current_user_id, $posts_db, $post_images_db, $user_db, $profile_db, $good_db, $heart_db);

$liked_post_ids = $good_db->get_liked_post_ids_by_user_id($debug_mode, $current_user_id);
$liked_posts_for_js = format_posts_for_js($debug_mode, $liked_post_ids, $current_user_id, $posts_db, $post_images_db, $user_db, $profile_db, $good_db, $heart_db);

$hearted_post_ids = $heart_db->get_hearted_post_ids_by_user_id($debug_mode, $current_user_id);
$bookmarked_posts_for_js = format_posts_for_js($debug_mode, $hearted_post_ids, $current_user_id, $posts_db, $post_images_db, $user_db, $profile_db, $good_db, $heart_db);

// PHPからのメッセージング
$message = '';
$message_type = '';
if (isset($_GET['profile_updated']) && $_GET['profile_updated'] === 'true') {
    $message = 'プロフィールを更新しました！';
    $message_type = 'success';
} elseif (isset($_GET['profile_update_error'])) {
    $message = '更新エラー: ' . urldecode($_GET['profile_update_error']);
    $message_type = 'error';
}
?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>マイページ | SAKE BIT</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@300;400;500;700&family=Zen+Old+Mincho:wght@400;500;700&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="css/MyPage.css">
    <link rel="stylesheet" href="css/top.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        /* メッセージボックスとモーダルのスタイル */
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
        .custom-modal-overlay {
            display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%;
            background-color: rgba(0, 0, 0, 0.7); z-index: 10001;
            justify-content: center; align-items: center;
        }
        .custom-modal-content {
            background-color: #fff; padding: 30px; border-radius: 8px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3); text-align: center;
            max-width: 400px; width: 90%; position: relative; z-index: 10002;
        }
        .custom-modal-content p { font-size: 1.8rem; margin-bottom: 20px; color: #333; }
        .custom-modal-buttons button {
            margin: 0 10px; padding: 10px 20px; border: none; border-radius: 5px;
            cursor: pointer; font-size: 1.6rem; transition: background-color 0.3s ease;
        }
        .custom-modal-buttons .btn-danger { background-color: #dc3545; color: white; }
        .custom-modal-buttons .btn-danger:hover { background-color: #c82333; }
        .custom-modal-buttons .btn-secondary { background-color: #6c757d; color: white; }
        .custom-modal-buttons .btn-secondary:hover { background-color: #5a6268; }

        /* 投稿カード関連のスタイル */
        .mypage-container .posts-section { width: 100%; max-width: 800px; margin: 20px auto; }
        .post-card { background-color: #fff; border-radius: 10px; box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1); padding: 25px; display: flex; flex-direction: column; margin-bottom: 30px; }
        .posts-list { display: grid; gap: 30px; padding-bottom: 50px; }
        .post-header { display: flex; align-items: center; margin-bottom: 15px; position: relative; }
        .post-user-icon { width: 48px; height: 48px; border-radius: 50%; object-fit: cover; margin-right: 15px; background-color: #eee; }
        .post-info { display: flex; flex-direction: column; justify-content: center; min-width: 0; flex: 1; }
        .post-user-name { font-size: 1.3rem; color: #888; margin-bottom: 2px; font-weight: 500; word-break: break-all; line-height: 1.2; display: block; max-width: 180px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
        .post-title { font-family: 'Noto Sans JP', sans-serif; font-size: 1.8rem; font-weight: 700; color: #333; margin: 0; flex-grow: 1; line-height: 1.3; }
        .post-content { font-size: 1.6rem; color: #555; margin-bottom: 20px; white-space: pre-wrap; word-break: break-word; }
        .post-actions { display: flex; justify-content: flex-end; align-items: center; gap: 15px; border-top: 1px solid #eee; padding-top: 15px; margin-top: auto; }
        .menu-button { background: none; border: none; font-size: 2.0rem; color: #999; padding: 5px; position: relative; }
        .menu-button:hover { color: #555; }
        .menu-dropdown { position: absolute; top: 100%; right: 0; background-color: #fff; border: 1px solid #ddd; border-radius: 5px; box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1); min-width: 120px; z-index: 100; display: none; }
        .menu-dropdown.is-active { display: block; }
        .menu-dropdown ul { list-style: none; padding: 0; margin: 0; }
        .menu-dropdown ul li a { display: block; padding: 10px 15px; color: #333; font-size: 1.4rem; }
        .menu-dropdown ul li a:hover { background-color: #f5f5f5; color: #A0522D; }
        .reaction-button { background: none; border: 1px solid #ccc; border-radius: 20px; padding: 8px 15px; font-size: 1.4rem; display: flex; align-items: center; gap: 5px; color: #555; transition: background-color 0.3s, border-color 0.3s; }
        .reaction-button.good { color: #28a745; border-color: #28a745; }
        .reaction-button.good:hover, .reaction-button.good.active { background-color: #28a745; color: #fff; }
        .reaction-button.heart { color: #dc3545; border-color: #dc3545; }
        .reaction-button.heart:hover, .reaction-button.heart.active { background-color: #dc3545; color: #fff; }
        .reaction-button span { font-size: 1.4rem; font-weight: 500; }
        .post-images { margin-top: 10px; display: flex; gap: 4px; border-radius: 12px; overflow: hidden; }
        .post-images.one img { width: 100%; max-height: 320px; object-fit: cover; border-radius: 12px; }
        .post-images.two img { width: 50%; height: 200px; object-fit: cover; }
        .post-images.three { display: flex; gap: 4px; }
        .post-images.three > div:first-child { width: 50%; }
        .post-images.three > div:last-child { width: 50%; display: flex; flex-direction: column; gap: 4px; }
        .post-images.three img { width: 100%; height: 98px; object-fit: cover; }
        .post-images.three > div:first-child img { height: 200px; }
        .post-images.four { display: grid; grid-template-columns: 1fr 1fr; grid-template-rows: 1fr 1fr; gap: 4px; }
        .post-images.four img { width: 100%; height: 98px; object-fit: cover; }
        @media (min-width: 768px) { .mypage-container .posts-section .posts-list { grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); } }
        @media (max-width: 600px) {
            .mypage-container .posts-section { padding: 0 10px; }
            .post-card { padding: 20px; }
            .post-user-icon { width: 40px; height: 40px; margin-right: 10px; }
            .post-title { font-size: 1.6rem; }
            .post-content { font-size: 1.5rem; }
            .reaction-button { padding: 6px 12px; font-size: 1.3rem; }
            .reaction-button span { font-size: 1.3rem; }
        }

        .tabs {
            display: flex;
            border-bottom: 1px solid #e5e5e5;
        }
        .tabs .tab-button {
            flex: 1;
            text-align: center;
            white-space: nowrap;
            padding: 15px 10px;
            border: none;
            border-bottom: 3px solid transparent;
            background: none;
            font-size: 1.6rem;
            color: #555;
            cursor: pointer;
            transition: all 0.2s ease;
        }
        .tabs .tab-button.active {
            color: #A0522D;
            border-bottom-color: #A0522D;
            font-weight: bold;
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
                <span class="en">MY PAGE</span>
                <span class="ja">( マイページ )</span>
            </h1>

            <section class="profile-section">
                <img src="<?= htmlspecialchars($user_profile['profile_icon_url']) ?>" alt="ユーザーアイコン" class="profile-icon">
                <h2 class="profile-username">ユーザー名：<?= htmlspecialchars($user_data['user_name'] ?? 'ゲスト') ?></h2>
                <p class="profile-birthday">誕生日：<?= htmlspecialchars($user_data['user_age'] ?? '不明') ?></p>
                <p class="profile-bio">
                    <?php
                    $raw_profile_text = $user_profile['profile_text'];
                    $text_with_actual_newlines = str_replace('\n', "\n", $raw_profile_text);
                    echo nl2br(htmlspecialchars($text_with_actual_newlines));
                    ?>
                </p>
                <button type="button" class="edit-profile-button">プロフィールを編集</button>
                <button class="history-button" onclick="window.location.href='history.php'">購入履歴を見る</button>
            </section>

            <section class="posts-section">
                <div class="tabs">
                    <button class="tab-button active" data-tab="my-posts-content">自分の投稿</button>
                    <button class="tab-button" data-tab="liked-posts-content">いいね</button>
                    <button class="tab-button" data-tab="bookmarked-posts-content">ブックマーク</button>
                </div>
                <div id="my-posts-content" class="tab-content active">
                    <div class="posts-list">
                        <!-- ここにJavaScriptで動的に自分の投稿が挿入されます -->
                    </div>
                </div>
                <div id="liked-posts-content" class="tab-content">
                    <div class="posts-list">
                        <!-- ここにJavaScriptで動的にいいねした投稿が挿入されます -->
                    </div>
                </div>
                <div id="bookmarked-posts-content" class="tab-content">
                    <div class="posts-list">
                        <!-- ここにJavaScriptで動的にブックマークした投稿が挿入されます -->
                    </div>
                </div>
            </section>
        </div>
    </main>

    <?php 
    // 共通フッターを読み込む
    require_once 'footer.php'; 
    ?>

    <!-- Custom Confirmation Modal HTML -->
    <div id="custom-confirm-modal" class="custom-modal-overlay">
        <div class="custom-modal-content">
            <p id="confirm-message"></p>
            <div class="custom-modal-buttons">
                <button id="confirm-yes" class="btn btn-danger">はい</button>
                <button id="confirm-no" class="btn btn-secondary">いいえ</button>
            </div>
        </div>
    </div>
    <script src="js/script.js"></script>
    <script src="js/MyPage.js"></script>
    <script>
        // ▼▼▼ ここから修正 ▼▼▼
        // PHPから渡されたユーザーIDと投稿データ
        const currentUserId = <?= json_encode($current_user_id) ?>;
        // const を let に変更して、再代入を可能にする
        let myPostsData = <?= json_encode($my_posts_for_js); ?>;
        let likedPostsData = <?= json_encode($liked_posts_for_js); ?>;
        let bookmarkedPostsData = <?= json_encode($bookmarked_posts_for_js); ?>;

        // PHPからのメッセージング (ページロード時に表示)
        const phpMessage = <?= json_encode($message) ?>;
        const phpMessageType = <?= json_encode($message_type) ?>;
        // ▲▲▲ ここまで修正 ▲▲▲
    </script>
</body>
</html>
