<?php
/*!
@file MyPage.php
@brief マイページ
@copyright Copyright (c) 2024 Your Name.
*/

// セッションを開始 (HTML出力の前に置く)
session_start();

// contents_db.php をインクルード
require_once __DIR__ . '/common/contents_db.php';
require_once __DIR__ . '/common/config.php'; // config.phpもインクルード

// DEBUG_MODE が config.php で定義されていない場合はここで定義
if (!defined('DEBUG_MODE')) {
    define('DEBUG_MODE', false); // 本番環境ではfalseに設定。必要に応じてtrueに変更してください。
}
$debug_mode = DEBUG_MODE; // config.phpで定義されたDEBUG_MODEを使用

// ログインしていない場合はログインページへリダイレクト
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$current_user_id = $_SESSION['user_id'];
$user_db = new cuser_info();
$profile_db = new cuser_profiles(); // プロフィール情報取得のために追加
$posts_db = new cposts(); // 投稿クラスのインスタンス
$post_images_db = new cpost_images(); // 投稿画像クラスのインスタンス
$good_db = new cgood(); // いいねクラスのインスタンス
$heart_db = new cheart(); // ハートクラスのインスタンス

// ユーザープロフィール情報と基本情報を取得
$user_data = $user_db->get_tgt($debug_mode, $current_user_id);
$user_profile = $profile_db->get_profile_by_user_id($debug_mode, $current_user_id);

// プロフィール情報が存在しない場合のデフォルト値設定と挿入
if (!$user_profile) {
    // ユーザー名の最初の1文字を大文字にしてアイコンテキストとする
    $icon_text = strtoupper(mb_substr($user_data['user_name'] ?? 'U', 0, 1, 'UTF-8'));
    $user_profile = [
        'profile_icon_url' => 'https://placehold.co/48x48/5CB85C/FFFFFF?text=' . $icon_text, // デフォルトアイコン
        'profile_text' => 'お酒と美味しい料理をこよなく愛する' . htmlspecialchars($user_data['user_name'] ?? 'サンプル太郎') . 'です。' . "\n" .
                          '特に日本酒の奥深さに魅了されており、週末は新しい銘柄を探しに出かけるのが趣味です。' . "\n" .
                          '皆さんとお酒に関する情報交換ができたら嬉しいです！'
    ];
    // 新規ユーザーのためにデフォルトプロフィールを挿入
    $profile_db->insert_profile($debug_mode, $current_user_id, $user_profile['profile_icon_url'], $user_profile['profile_text']);
}

// ユーザーの誕生日から年齢を計算するヘルパー関数 (表示しないが、必要であれば残す)
function calculateAge($dob) {
    if (empty($dob) || $dob === '0000-00-00') {
        return '不明';
    }
    try {
        $birthDate = new DateTime($dob);
        $today = new DateTime();
        $age = $birthDate->diff($today)->y;
        return $age;
    } catch (Exception $e) {
        return 'エラー';
    }
}

/**
 * 投稿データを整形してJavaScriptに渡すヘルパー関数
 * @param bool $debug_mode デバッグモードのオン/オフ
 * @param array $post_ids 取得したい投稿IDの配列
 * @param int $current_user_id 現在ログインしているユーザーID
 * @param cposts $posts_db cpostsクラスのインスタンス
 * @param cpost_images $post_images_db cpost_imagesクラスのインスタンス
 * @param cuser_info $user_info_db cuser_infoクラスのインスタンス
 * @param cuser_profiles $user_profiles_db cuser_profilesクラスのインスタンス
 * @param cgood $good_db cgoodクラスのインスタンス
 * @param cheart $heart_db cheartクラスのインスタンス
 * @return array 整形された投稿データの配列
 */
function format_posts_for_js($debug_mode, $post_ids, $current_user_id, $posts_db, $post_images_db, $user_info_db, $user_profiles_db, $good_db, $heart_db) {
    $formatted_posts = [];
    if (empty($post_ids)) {
        return $formatted_posts;
    }

    // 重複を削除し、IDを整形 (get_posts_by_idsは重複を考慮しないため)
    $unique_post_ids = array_unique(array_map('intval', $post_ids));

    // 取得したい投稿IDの順序を保持するためにマップを作成
    $post_order_map = array_flip($unique_post_ids);

    $posts_raw = $posts_db->get_posts_by_ids($debug_mode, $unique_post_ids);

    // 投稿IDをキーとしてアクセスできるように連想配列に変換
    $posts_indexed = [];
    foreach ($posts_raw as $post) {
        $posts_indexed[$post['post_id']] = $post;
    }

    foreach ($unique_post_ids as $post_id) {
        if (!isset($posts_indexed[$post_id])) {
            continue; // 投稿が見つからない場合はスキップ
        }
        $post = $posts_indexed[$post_id];

        // 投稿に紐づく画像を全て取得
        $images = $post_images_db->get_images_by_post_id($debug_mode, $post['post_id']);
        $image_paths = [];
        foreach ($images as $img) {
            $image_paths[] = htmlspecialchars($img['image_path']);
        }

        // 投稿ユーザーの情報を取得
        $post_user_data = $user_info_db->get_tgt($debug_mode, $post['user_id']);
        $post_user_name = $post_user_data ? htmlspecialchars($post_user_data['user_name']) : '名無しユーザー';
        
        // 投稿ユーザーのプロフィールからアイコンURLを取得
        $post_user_profile = $user_profiles_db->get_profile_by_user_id($debug_mode, $post['user_id']);
        $user_icon_text = strtoupper(mb_substr($post_user_name, 0, 1, 'UTF-8'));
        $post_user_icon_url = 'https://placehold.co/40x40/5CB85C/FFFFFF?text=' . $user_icon_text;
        if ($post_user_profile && !empty($post_user_profile['profile_icon_url'])) {
            $post_user_icon_url = htmlspecialchars($post_user_profile['profile_icon_url']);
        }

        // いいね数とハート数を取得
        $likes_count = $good_db->count_good_by_post_id($debug_mode, $post['post_id']);
        $hearts_count = $heart_db->count_heart_by_post_id($debug_mode, $post['post_id']); // ここを修正

        // 現在のユーザーが良いね・ハートしているかチェック
        $is_liked_by_current_user = false;
        $is_hearted_by_current_user = false;
        if ($current_user_id) {
            $is_liked_by_current_user = $good_db->is_good_by_user($debug_mode, $current_user_id, $post['post_id']);
            $is_hearted_by_current_user = $heart_db->is_heart_by_user($debug_mode, $current_user_id, $post['post_id']);
        }

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
            'isMine' => ($post['user_id'] == $current_user_id) // 自分の投稿かどうか
        ];
    }
    return $formatted_posts;
}


// --- 自分の投稿データ取得 ---
$my_posts_raw_ids = []; 
$my_posts_raw = $posts_db->get_posts_by_user_id($debug_mode, $current_user_id);
foreach($my_posts_raw as $p) {
    $my_posts_raw_ids[] = $p['post_id'];
}
$my_posts_for_js = format_posts_for_js($debug_mode, $my_posts_raw_ids, $current_user_id, $posts_db, $post_images_db, $user_db, $profile_db, $good_db, $heart_db);


// --- いいねした投稿データ取得 ---
$liked_post_ids = $good_db->get_liked_post_ids_by_user_id($debug_mode, $current_user_id);
$liked_posts_for_js = format_posts_for_js($debug_mode, $liked_post_ids, $current_user_id, $posts_db, $post_images_db, $user_db, $profile_db, $good_db, $heart_db);

// --- ブックマークした投稿データ取得 ---
$hearted_post_ids = $heart_db->get_hearted_post_ids_by_user_id($debug_mode, $current_user_id);
$bookmarked_posts_for_js = format_posts_for_js($debug_mode, $hearted_post_ids, $current_user_id, $posts_db, $post_images_db, $user_db, $profile_db, $good_db, $heart_db);


// PHPからのメッセージング
$message = '';
$message_type = '';
// プロフィール更新時のメッセージ
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
    <title>マイページ | OUR BRAND</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@300;400;500;700&family=Zen+Old+Mincho:wght@400;500;700&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="css/MyPage.css">
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

        /* カスタム確認モーダル */
        .custom-modal-overlay {
            display: none; /* 初期状態では非表示 */
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.7);
            z-index: 10001; /* custom-message-boxより上 */
            justify-content: center;
            align-items: center;
        }

        .custom-modal-content {
            background-color: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
            text-align: center;
            max-width: 400px;
            width: 90%;
            position: relative;
            z-index: 10002;
        }

        .custom-modal-content p {
            font-size: 1.8rem;
            margin-bottom: 20px;
            color: #333;
        }

        .custom-modal-buttons button {
            margin: 0 10px;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1.6rem;
            transition: background-color 0.3s ease;
        }

        .custom-modal-buttons .btn-danger {
            background-color: #dc3545;
            color: white;
        }

        .custom-modal-buttons .btn-danger:hover {
            background-color: #c82333;
        }

        .custom-modal-buttons .btn-secondary {
            background-color: #6c757d;
            color: white;
        }

        .custom-modal-buttons .btn-secondary:hover {
            background-color: #5a6268;
        }

        /* 以下、posts.css から追加・調整されたスタイル */
        /* 投稿一覧コンテナの調整 */
        .mypage-container .posts-section {
            width: 100%;
            max-width: 800px; /* 投稿カードの最大幅 */
            margin: 20px auto;
        }

        /* 投稿カード */
        .post-card {
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            padding: 25px;
            display: flex;
            flex-direction: column;
            margin-bottom: 30px; /* カード間のスペース */
        }

        .posts-list {
            display: grid; /* グリッドレイアウトでカードを配置 */
            gap: 30px; /* カード間のスペース */
            padding-bottom: 50px; /* フッターとの間隔確保 */
        }

        .post-header {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
            position: relative; /* メニューボタンの基準 */
        }

        .post-user-icon {
            width: 48px;
            height: 48px;
            border-radius: 50%;
            object-fit: cover;
            margin-right: 15px;
            background-color: #eee; /* デフォルトの背景 */
        }

        .post-info {
            display: flex;
            flex-direction: column;
            justify-content: center;
            min-width: 0;
            flex: 1;
        }

        .post-user-name {
            font-size: 1.3rem;
            color: #888;
            margin-bottom: 2px;
            font-weight: 500;
            word-break: break-all;
            line-height: 1.2;
            display: block;
            max-width: 180px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .post-title {
            font-family: 'Noto Sans JP', sans-serif;
            font-size: 1.8rem;
            font-weight: 700;
            color: #333;
            margin: 0;
            flex-grow: 1;
            line-height: 1.3;
        }

        .post-content {
            font-size: 1.6rem;
            color: #555;
            margin-bottom: 20px;
            white-space: pre-wrap; /* 改行を反映 */
            word-break: break-word; /* 長い単語の途中で改行 */
        }

        .post-actions {
            display: flex;
            justify-content: flex-end; /* アクションボタンを右寄せ */
            align-items: center;
            gap: 15px; /* ボタン間のスペース */
            border-top: 1px solid #eee;
            padding-top: 15px;
            margin-top: auto; /* コンテンツが短い場合でも下に配置 */
        }

        /* メニューボタン */
        .menu-button {
            background: none;
            border: none;
            font-size: 2.0rem;
            color: #999;
            padding: 5px;
            position: relative; /* ドロップダウンの基準 */
        }

        .menu-button:hover {
            color: #555;
        }

        /* メニュードロップダウン */
        .menu-dropdown {
            position: absolute;
            top: 100%; /* ボタンのすぐ下 */
            right: 0;
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 5px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            min-width: 120px;
            z-index: 100;
            display: none; /* 初期状態では非表示 */
        }

        .menu-dropdown.is-active {
            display: block; /* アクティブ時に表示 */
        }

        .menu-dropdown ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .menu-dropdown ul li a {
            display: block;
            padding: 10px 15px;
            color: #333;
            font-size: 1.4rem;
        }

        .menu-dropdown ul li a:hover {
            background-color: #f5f5f5;
            color: #A0522D;
        }

        /* グッド・ハートボタン */
        .reaction-button {
            background: none;
            border: 1px solid #ccc;
            border-radius: 20px;
            padding: 8px 15px;
            font-size: 1.4rem;
            display: flex;
            align-items: center;
            gap: 5px;
            color: #555;
            transition: background-color 0.3s, border-color 0.3s;
        }

        .reaction-button.good {
            color: #28a745;
            border-color: #28a745;
        }
        .reaction-button.good:hover {
            background-color: #28a745;
            color: #fff;
        }
        .reaction-button.good.active {
            background-color: #28a745;
            color: #fff;
        }

        .reaction-button.heart {
            color: #dc3545;
            border-color: #dc3545;
        }
        .reaction-button.heart:hover {
            background-color: #dc3545;
            color: #fff;
        }
        .reaction-button.heart.active {
            background-color: #dc3545;
            color: #fff;
        }

        .reaction-button span {
            font-size: 1.4rem;
            font-weight: 500;
        }

        /* 投稿画像レイアウト */
        .post-images {
            margin-top: 10px;
            display: flex;
            gap: 4px;
            border-radius: 12px;
            overflow: hidden;
        }
        .post-images.one img {
            width: 100%;
            max-height: 320px;
            object-fit: cover;
            border-radius: 12px;
        }
        .post-images.two img {
            width: 50%;
            height: 200px;
            object-fit: cover;
        }
        .post-images.three {
            display: flex;
            gap: 4px;
        }
        .post-images.three > div:first-child {
            width: 50%;
        }
        .post-images.three > div:last-child {
            width: 50%;
            display: flex;
            flex-direction: column;
            gap: 4px;
        }
        .post-images.three img {
            width: 100%;
            height: 98px;
            object-fit: cover;
        }
        .post-images.three > div:first-child img {
            height: 200px;
        }
        .post-images.four {
            display: grid;
            grid-template-columns: 1fr 1fr;
            grid-template-rows: 1fr 1fr;
            gap: 4px;
        }
        .post-images.four img {
            width: 100%;
            height: 98px;
            object-fit: cover;
        }

        /* PC (min-width: 768px) */
        @media (min-width: 768px) {
            .mypage-container .posts-section .posts-list {
                grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); /* PCでは複数列に */
            }
        }

        /* スマートフォン向けの追加調整 (既存のメディアクエリに統合) */
        @media (max-width: 600px) {
            .mypage-container .posts-section {
                padding: 0 10px; /* モバイルでのパディング調整 */
            }

            .post-card {
                padding: 20px;
            }

            .post-user-icon {
                width: 40px;
                height: 40px;
                margin-right: 10px;
            }

            .post-title {
                font-size: 1.6rem;
            }

            .post-content {
                font-size: 1.5rem;
            }

            .reaction-button {
                padding: 6px 12px;
                font-size: 1.3rem;
            }

            .reaction-button span {
                font-size: 1.3rem;
            }
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
                    <li><a href="products_list.php?tag=度数高め">度数低め</a></li>
                    <li><a href="products_list.php?tag=度数低め">度数高め</a></li>
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
        <div class="mypage-container">
            <h1 class="page-title">
                <span class="en">MY PAGE</span>
                <span class="ja">( マイページ )</span>
            </h1>

            <section class="profile-section">
                <img src="<?= htmlspecialchars($user_profile['profile_icon_url']) ?>" alt="ユーザーアイコン" class="profile-icon">
                <h2 class="profile-username">ユーザー名：<?= htmlspecialchars($user_data['user_name'] ?? 'ゲスト') ?></h2>
                <!-- メールアドレスと年齢は表示しない -->
                <p class="profile-birthday">誕生日：<?= htmlspecialchars($user_data['user_age'] ?? '不明') ?></p>
                <p class="profile-bio">
                    <?php
                    // データベースから取得したテキスト
                    $raw_profile_text = $user_profile['profile_text'];

                    // もしデータベースに '\n' (バックスラッシュとnの文字) として保存されている場合、
                    // それを実際の改行文字に変換します。
                    $text_with_actual_newlines = str_replace('\n', "\n", $raw_profile_text);

                    // その後、XSS対策のためにHTML特殊文字をエスケープし、
                    // 実際の改行文字をHTMLの<br>タグに変換して出力します。
                    echo nl2br(htmlspecialchars($text_with_actual_newlines));
                    ?>
                </p>
                <button class="edit-profile-button">プロフィールを編集</button>
                <!-- 購入履歴ページへのリンクボタン -->
                <button class="history-button" onclick="window.location.href='history.php'">購入履歴を見る</button>
            </section>

            <section class="posts-section">
                <div class="tabs">
                    <button class="tab-button active" data-tab="my-posts-content">自分の投稿</button>
                    <button class="tab-button" data-tab="liked-posts-content">いいねした投稿</button>
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
        // PHPから渡されたユーザーIDと投稿データ
        const currentUserId = <?= json_encode($current_user_id) ?>;
        const myPostsData = <?= json_encode($my_posts_for_js); ?>;
        const likedPostsData = <?= json_encode($liked_posts_for_js); ?>;
        const bookmarkedPostsData = <?= json_encode($bookmarked_posts_for_js); ?>;

        // PHPからのメッセージング (ページロード時に表示)
        const phpMessage = <?= json_encode($message) ?>;
        const phpMessageType = <?= json_encode($message_type) ?>;
    </script>
</body>
</html>
