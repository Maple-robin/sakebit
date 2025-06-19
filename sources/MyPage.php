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

$debug_mode = false; // デバッグモードのオン/オフ

// ログインしていない場合はログインページへリダイレクト
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$current_user_id = $_SESSION['user_id'];
$user_db = new cuser_info();
$profile_db = new cuser_profiles(); // プロフィール情報取得のために追加
// $posts_db = new cposts(); // 投稿機能未実装のためコメントアウト
// $good_db = new cgood(); // 投稿機能未実装のためコメントアウト

// ユーザープロフィール情報と基本情報を取得
$user_data = $user_db->get_tgt($debug_mode, $current_user_id);
$user_profile = $profile_db->get_profile_by_user_id($debug_mode, $current_user_id);

// プロフィール情報が存在しない場合のデフォルト値設定
if (!$user_profile) {
    // user_profiles テーブルにデータがない場合、デフォルト値を設定
    $user_profile = [
        'profile_icon_url' => 'img/profile_icons/default_user.png', // デフォルトアイコンのパス
        'profile_text' => 'お酒と美味しい料理をこよなく愛する' . htmlspecialchars($user_data['user_name'] ?? 'サンプル太郎') . 'です。' . "\n" .
                          '特に日本酒の奥深さに魅了されており、週末は新しい銘柄を探しに出かけるのが趣味です。' . "\n" .
                          '皆さんとお酒に関する情報交換ができたら嬉しいです！'
    ];
    // 新規ユーザーのためにデフォルトプロフィールを挿入（profile_edit.phpでも行われるが念のため）
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

// 投稿機能が未実装のため、投稿関連のデータは空とする
$my_posts_for_js = [];
$liked_posts_for_js = [];

/*
// ユーザー自身の投稿を取得 (投稿機能未実装のためコメントアウト)
$my_posts_raw = $posts_db->get_posts_by_author_id($debug_mode, $current_user_id);
// いいねした投稿を取得 (投稿機能未実装のためコメントアウト)
$liked_posts_raw = $posts_db->get_liked_posts_by_user_id($debug_mode, $current_user_id);

// JavaScriptに渡すための投稿データを整形 (投稿機能未実装のためコメントアウト)
$my_posts_for_js = [];
foreach ($my_posts_raw as $post) {
    $reaction_counts = $good_db->get_post_reaction_counts($debug_mode, $post['post_id']);
    $user_reaction = $good_db->get_user_reaction_to_post($debug_mode, $current_user_id, $post['post_id']);

    $my_posts_for_js[] = [
        'id' => $post['post_id'],
        'title' => $post['post_title'],
        'content' => $post['post_content'],
        'goods' => $reaction_counts['goods_count'],
        'bads' => $reaction_counts['bads_count'],
        'is_mine' => true,
        'liked' => ($user_reaction === 'good'),
        'baddened' => ($user_reaction === 'bad')
    ];
}

$liked_posts_for_js = [];
foreach ($liked_posts_raw as $post) {
    $reaction_counts = [
        'goods_count' => $post['goods_count'] ?? 0,
        'bads_count' => $post['bads_count'] ?? 0
    ];
    $user_reaction = $good_db->get_user_reaction_to_post($debug_mode, $current_user_id, $post['post_id']);

    $liked_posts_for_js[] = [
        'id' => $post['post_id'],
        'title' => $post['post_title'],
        'content' => $post['post_content'],
        'goods' => $reaction_counts['goods_count'],
        'bads' => $reaction_counts['bads_count'],
        'is_mine' => ($post['user_id'] == $current_user_id),
        'liked' => ($user_reaction === 'good'),
        'baddened' => ($user_reaction === 'bad')
    ];
}
*/

// PHPからのメッセージング (投稿関連のエラーメッセージは一時的に無効化)
$message = '';
$message_type = '';
/*
if (isset($_GET['post_deleted']) && $_GET['post_deleted'] === 'true') {
    $message = '投稿を削除しました。';
    $message_type = 'success';
} elseif (isset($_GET['post_delete_error']) && $_GET['post_delete_error'] === 'true') {
    $message = '投稿の削除に失敗しました。';
    $message_type = 'error';
}
*/
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
            <!-- ↓ここから追加 -->
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
                    <button class="tab-button active" data-tab="my-posts">自分の投稿</button>
                    <button class="tab-button" data-tab="liked-posts">いいねした投稿</button>
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

    <script>
        // PHPから渡されたユーザーIDと投稿データ
        const currentUserId = <?= json_encode($current_user_id) ?>;
        // 投稿機能未実装のため、空の配列を渡す
        const myPostsData = [];
        const likedPostsData = [];

        // PHPからのメッセージング (ページロード時に表示)
        const phpMessage = <?= json_encode($message) ?>;
        const phpMessageType = <?= json_encode($message_type) ?>;
    </script>
    <script src="js/script.js"></script>
    <script src="js/MyPage.js"></script>
</body>

</html>
