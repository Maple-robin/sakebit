<?php
/*!
@file posts.php
@brief 投稿一覧ページ
@copyright Copyright (c) 2024 Your Name.
*/

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/common/contents_db.php';
require_once __DIR__ . '/common/config.php';

if (!defined('DEBUG_MODE')) {
    define('DEBUG_MODE', false);
}

// 1. GETパラメータからソート順を取得（なければ 'newest' をデフォルトに）
$sort_by = $_GET['sort'] ?? 'newest';

// 2. 有効なソートキーかチェック（'newest' または 'popular' のみ許可）
$allowed_sort_keys = ['newest', 'popular'];
if (!in_array($sort_by, $allowed_sort_keys)) {
    $sort_by = 'newest'; // 無効な値の場合はデフォルトに戻す
}

$posts_data = [];
$current_user_id = $_SESSION['user_id'] ?? null;

try {
    $posts_db = new cposts();
    $post_images_db = new cpost_images();
    $user_info_db = new cuser_info();
    $user_profiles_db = new cuser_profiles();
    $good_db = new cgood();
    $heart_db = new cheart();

    // 3. ソート順を引数に渡して投稿を取得
    $all_posts = $posts_db->get_all(DEBUG_MODE, 0, 9999, $sort_by);

    foreach ($all_posts as $post) {
        $images = $post_images_db->get_images_by_post_id(DEBUG_MODE, $post['post_id']);
        $image_paths = array_map(fn($img) => htmlspecialchars($img['image_path']), $images);

        $user = $user_info_db->get_tgt(DEBUG_MODE, $post['user_id']);
        $user_name = $user ? htmlspecialchars($user['user_name']) : '名無しユーザー';
        
        $user_profile = $user_profiles_db->get_profile_by_user_id(DEBUG_MODE, $post['user_id']);
        $user_icon_text = strtoupper(mb_substr($user_name, 0, 1, 'UTF-8'));
        $user_icon_url = 'https://placehold.co/40x40/5CB85C/FFFFFF?text=' . $user_icon_text;
        if ($user_profile && !empty($user_profile['profile_icon_url'])) {
            $user_icon_url = htmlspecialchars($user_profile['profile_icon_url']);
        }

        $likes_count = $good_db->count_good_by_post_id(DEBUG_MODE, $post['post_id']);
        $hearts_count = $heart_db->count_heart_by_post_id(DEBUG_MODE, $post['post_id']);

        $is_liked_by_current_user = $current_user_id ? $good_db->is_good_by_user(DEBUG_MODE, $current_user_id, $post['post_id']) : false;
        $is_hearted_by_current_user = $current_user_id ? $heart_db->is_heart_by_user(DEBUG_MODE, $current_user_id, $post['post_id']) : false;

        $posts_data[] = [
            'id' => $post['post_id'],
            'userIcon' => $user_icon_url,
            'userName' => $user_name,
            'title' => htmlspecialchars($post['post_title']),
            'content' => htmlspecialchars($post['post_content']),
            'images' => $image_paths,
            'likes' => $likes_count,
            'hearts' => $hearts_count,
            'isLiked' => $is_liked_by_current_user,
            'isHearted' => $is_hearted_by_current_user,
        ];
    }
} catch (Exception $e) {
    error_log("Failed to fetch posts: " . $e->getMessage());
    $posts_data = [];
}

$json_posts_data = json_encode($posts_data);
$json_current_user_id = json_encode($current_user_id);
?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>投稿一覧 | SAKE BIT</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@300;400;500;700&family=Zen+Old+Mincho:wght@400;500;700&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="css/posts.css">
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

        /* 並び替えボタンのスタイル */
        .sort-container {
            display: flex;
            justify-content: center;
            gap: 30px;
            margin-bottom: 30px;
            border-bottom: 1px solid #e5e5e5;
        }
        .sort-button {
            padding: 12px 16px;
            border: none;
            border-bottom: 3px solid transparent;
            background-color: transparent;
            color: #888;
            font-size: 1.6rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            transform: translateY(1px);
        }
        .sort-button:hover {
            color: #333;
        }
        .sort-button.is-active {
            color: #A0522D;
            border-bottom-color: #A0522D;
            font-weight: 700;
        }

        /* ▼▼▼ ここから追加 ▼▼▼ */
        /* 画像拡大モーダルのスタイル */
        .image-viewer-overlay {
            display: none; /* 初期状態では非表示 */
            position: fixed;
            z-index: 10002; /* 他の要素より手前に表示 */
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.85); /* 半透明の黒い背景 */
            justify-content: center;
            align-items: center;
            animation: fadeIn 0.3s ease;
        }
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        .image-viewer-content {
            margin: auto;
            display: block;
            max-width: 90%;
            max-height: 85vh;
            border-radius: 5px;
        }
        .close-viewer-btn {
            position: absolute;
            top: 15px;
            right: 35px;
            color: #f1f1f1;
            font-size: 40px;
            font-weight: bold;
            transition: 0.3s;
            cursor: pointer;
        }
        .close-viewer-btn:hover,
        .close-viewer-btn:focus {
            color: #bbb;
            text-decoration: none;
        }
        /* 投稿画像のカーソルをポインターに変更 */
        .post-images img {
            cursor: pointer;
        }
        /* ▲▲▲ ここまで追加 ▲▲▲ */
    </style>
</head>

<body>
    <?php 
    // 共通ヘッダーを読み込む
    require_once 'header.php'; 
    ?>

    <main>
        <div class="posts-container">
            <h1 class="page-title">
                <span class="en">POSTS</span>
                <span class="ja">（みんなの投稿）</span>
            </h1>

            <div class="sort-container">
                <a href="posts.php?sort=newest" class="sort-button <?php if ($sort_by === 'newest') echo 'is-active'; ?>">新着順</a>
                <a href="posts.php?sort=popular" class="sort-button <?php if ($sort_by === 'popular') echo 'is-active'; ?>">人気順</a>
            </div>

            <div id="posts-container" class="posts-list">
                <!-- JavaScriptによって投稿がここに描画されます -->
            </div>
        </div>
    </main>

    <div class="new-post-button-wrapper">
        <a href="post.php" class="new-post-button">新規投稿</a>
    </div>

    <?php 
    // 共通フッターを読み込む
    require_once 'footer.php'; 
    ?>
    
    <!-- ▼▼▼ ここから追加 ▼▼▼ -->
    <!-- 画像拡大表示用のモーダル -->
    <div id="image-viewer-modal" class="image-viewer-overlay">
        <span class="close-viewer-btn">&times;</span>
        <img class="image-viewer-content" id="modal-image">
    </div>
    <!-- ▲▲▲ ここまで追加 ▲▲▲ -->

    <script src="js/script.js"></script>
    <script>
        const postsData = <?php echo $json_posts_data; ?>;
        const currentUserId = <?php echo $json_current_user_id; ?>;
    </script>
    <script src="js/posts.js"></script>
</body>

</html>
