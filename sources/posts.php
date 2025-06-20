<?php
/*!
@file posts.php
@brief 投稿一覧ページ
@copyright Copyright (c) 2024 Your Name.
*/

// セッションを開始 (HTML出力の前に置く)
session_start();

// エラー表示設定 (開発中のみ)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// contents_db.php と config.php をインクルード
require_once __DIR__ . '/common/contents_db.php';
require_once __DIR__ . '/common/config.php'; // config.phpもインクルード

// DEBUG_MODE が config.php で定義されていない場合はここで定義
if (!defined('DEBUG_MODE')) {
    define('DEBUG_MODE', false); // 本番環境ではfalseに設定
}

$posts_data = []; // 投稿データを格納する配列
$current_user_id = $_SESSION['user_id'] ?? null; // 現在ログインしているユーザーのID

try {
    $posts_db = new cposts();
    $post_images_db = new cpost_images(); // 投稿画像クラスのインスタンス
    $user_info_db = new cuser_info(); // ユーザー情報クラスのインスタンス
    $user_profiles_db = new cuser_profiles(); // ユーザープロフィールクラスのインスタンスを追加！
    $good_db = new cgood(); // いいねクラスのインスタンス
    $heart_db = new cheart(); // ハートクラスのインスタンス

    // すべての投稿を取得 (ページネーションが必要な場合はここにロジックを追加)
    $all_posts = $posts_db->get_all(DEBUG_MODE, 0, 9999); // 最初の9999件を取得 (仮)

    foreach ($all_posts as $post) {
        // 投稿に紐づく画像を全て取得
        $images = $post_images_db->get_images_by_post_id(DEBUG_MODE, $post['post_id']);
        $image_paths = [];
        foreach ($images as $img) {
            $image_paths[] = $img['image_path'];
        }

        // 投稿ユーザーの情報を取得
        $user = $user_info_db->get_tgt(DEBUG_MODE, $post['user_id']);
        $user_name = $user ? htmlspecialchars($user['user_name']) : '名無しユーザー'; // ユーザー名が取得できなければ「名無しユーザー」
        
        // ユーザープロフィールからアイコンURLを取得
        $user_profile = $user_profiles_db->get_profile_by_user_id(DEBUG_MODE, $post['user_id']);
        $user_icon_url = 'https://placehold.co/40x40/5CB85C/FFFFFF?text=' . strtoupper(mb_substr($user_name, 0, 1, 'UTF-8')); // デフォルトアイコン (マルチバイト対応)
        if ($user_profile && !empty($user_profile['profile_icon_url'])) {
            $user_icon_url = htmlspecialchars($user_profile['profile_icon_url']);
        }

        // いいね数とハート数を取得
        $likes_count = $good_db->count_good_by_post_id(DEBUG_MODE, $post['post_id']);
        $hearts_count = $heart_db->count_heart_by_post_id(DEBUG_MODE, $post['post_id']);

        // 現在のユーザーが良いね・ハートしているかチェック
        $is_liked_by_current_user = false;
        $is_hearted_by_current_user = false;
        if ($current_user_id) {
            $is_liked_by_current_user = $good_db->is_good_by_user(DEBUG_MODE, $current_user_id, $post['post_id']);
            $is_hearted_by_current_user = $heart_db->is_heart_by_user(DEBUG_MODE, $current_user_id, $post['post_id']);
        }

        $posts_data[] = [
            'id' => $post['post_id'],
            'userIcon' => $user_icon_url, // ユーザープロフィールから取得したアイコンURL
            'userName' => $user_name, // ユーザー名
            'title' => htmlspecialchars($post['post_title']),
            'content' => htmlspecialchars($post['post_content']),
            'images' => $image_paths, // 画像パスの配列
            'likes' => $likes_count, // いいね数
            'hearts' => $hearts_count, // ハート数
            'isLiked' => $is_liked_by_current_user, // 現在のユーザーが良いねしているか
            'isHearted' => $is_hearted_by_current_user, // 現在のユーザーがハートしているか
        ];
    }
} catch (Exception $e) {
    error_log("Failed to fetch posts: " . $e->getMessage());
    $posts_data = []; // エラー発生時は空の配列を渡す
}

// JavaScriptに渡すためにJSONエンコード
$json_posts_data = json_encode($posts_data);
$json_current_user_id = json_encode($current_user_id); // ユーザーIDもJSに渡す
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
        <div class="posts-container">
            <h1 class="page-title">
                <span class="en">POSTS</span>
                <span class="ja">（みんなの投稿）</span>
            </h1>
            <!-- 投稿リストを表示する場所に追加 -->
            <div id="posts-container" class="posts-list"></div>
        </div>
    </main>

    <div class="new-post-button-wrapper">
        <a href="post.php" class="new-post-button">新規投稿</a>
    </div>

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
    <script>
        // PHPから投稿データをJavaScriptに渡す
        const postsData = <?php echo $json_posts_data; ?>;
        const currentUserId = <?php echo $json_current_user_id; ?>; // ログインユーザーIDもJSに渡す
    </script>
    <script src="js/posts.js"></script>
</body>

</html>