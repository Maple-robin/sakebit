<?php
/*!
@file posts.php
@brief 投稿一覧ページ
@copyright Copyright (c) 2024 Your Name.
*/

// ★注意: このページのPHPロジックはヘッダー出力前に実行する必要があるため、
// DB接続とセッション開始はこのファイルで先に行います。
// header.php内のrequire_onceとsession_start()は、重複実行が防止されるので問題ありません。

// セッションを開始 (HTML出力の前に置く)
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// エラー表示設定 (開発中のみ)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// 必要なファイルをインクルード
require_once __DIR__ . '/common/contents_db.php';
require_once __DIR__ . '/common/config.php';

// DEBUG_MODE が config.php で定義されていない場合はここで定義
if (!defined('DEBUG_MODE')) {
    define('DEBUG_MODE', false);
}

$posts_data = []; // 投稿データを格納する配列
$current_user_id = $_SESSION['user_id'] ?? null; // 現在ログインしているユーザーのID

try {
    $posts_db = new cposts();
    $post_images_db = new cpost_images();
    $user_info_db = new cuser_info();
    $user_profiles_db = new cuser_profiles();
    $good_db = new cgood();
    $heart_db = new cheart();

    // すべての投稿を取得
    $all_posts = $posts_db->get_all(DEBUG_MODE, 0, 9999);

    foreach ($all_posts as $post) {
        // 投稿に紐づく画像を全て取得
        $images = $post_images_db->get_images_by_post_id(DEBUG_MODE, $post['post_id']);
        $image_paths = array_map(fn($img) => htmlspecialchars($img['image_path']), $images);

        // 投稿ユーザーの情報を取得
        $user = $user_info_db->get_tgt(DEBUG_MODE, $post['user_id']);
        $user_name = $user ? htmlspecialchars($user['user_name']) : '名無しユーザー';
        
        // ユーザープロフィールからアイコンURLを取得
        $user_profile = $user_profiles_db->get_profile_by_user_id(DEBUG_MODE, $post['user_id']);
        $user_icon_text = strtoupper(mb_substr($user_name, 0, 1, 'UTF-8'));
        $user_icon_url = 'https://placehold.co/40x40/5CB85C/FFFFFF?text=' . $user_icon_text;
        if ($user_profile && !empty($user_profile['profile_icon_url'])) {
            $user_icon_url = htmlspecialchars($user_profile['profile_icon_url']);
        }

        // いいね数とハート数を取得
        $likes_count = $good_db->count_good_by_post_id(DEBUG_MODE, $post['post_id']);
        $hearts_count = $heart_db->count_heart_by_post_id(DEBUG_MODE, $post['post_id']);

        // 現在のユーザーが良いね・ハートしているかチェック
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

// JavaScriptに渡すためにJSONエンコード
$json_posts_data = json_encode($posts_data);
$json_current_user_id = json_encode($current_user_id);
?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>投稿一覧 | OUR BRAND</title>
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
    
    <script src="js/script.js"></script>
    <script>
        // PHPから投稿データをJavaScriptに渡す
        const postsData = <?php echo $json_posts_data; ?>;
        const currentUserId = <?php echo $json_current_user_id; ?>;
    </script>
    <script src="js/posts.js"></script>
</body>

</html>
