<?php
// DB接続ファイルを読み込む
require_once __DIR__ . '/common/contents_db.php';

// セッションが開始されていなければ開始する
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// --- ▼▼▼【ここから追記】サイト全体のアクセスログを記録する処理 ▼▼▼ ---
try {
    // --- 条件1: 無視するロボット（ユーザーエージェント）のリスト ---
    $bot_list = ['Googlebot', 'bingbot', 'crawler', 'spider', 'YandexBot'];
    $user_agent = $_SERVER['HTTP_USER_AGENT'] ?? '';
    $is_bot = false;
    foreach ($bot_list as $bot) {
        if (stripos($user_agent, $bot) !== false) {
            $is_bot = true;
            break;
        }
    }

    // --- 条件2: 短時間での連続アクセスを無視する ---
    $now = time();
    $last_log_time = $_SESSION['last_log_time'] ?? 0;
    $is_too_soon = ($now - $last_log_time) < 30; // 30秒以内の再アクセスは記録しない

    // ★【条件判定】ロボットでなく、かつ連続アクセスでもない場合のみ記録する
    if (!$is_bot && !$is_too_soon) {
        // ログ記録用のクラスをインスタンス化
        $clogs = new caccess_logs();

        $session_id = session_id();
        $ip_address = $_SERVER['REMOTE_ADDR'] ?? 'UNKNOWN';
        $page_url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://" . ($_SERVER['HTTP_HOST'] ?? 'UNKNOWN') . ($_SERVER['REQUEST_URI'] ?? '');

        // データベースにアクセス記録を追加
        $clogs->insert_log(defined('DEBUG') ? DEBUG : false, $session_id, $ip_address, $page_url);

        // ★【追記】最後にログを記録した時間をセッションに保存
        $_SESSION['last_log_time'] = $now;
    }
} catch (Exception $e) {
    // サイトの表示に影響が出ないよう、エラーはログファイルにのみ記録します
    error_log('Access Log Error: ' . $e->getMessage());
}
// --- ▲▲▲【ここまで追記】サイト全体のアクセスログを記録する処理 ▲▲▲ ---


// ログイン状態に応じた変数を設定
$is_logged_in = isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
$login_link = $is_logged_in ? 'logout.php' : 'login.php';
$login_text = $is_logged_in ? 'ログアウト' : 'ログイン';
$login_icon = $is_logged_in ? 'fas fa-sign-out-alt' : 'fas fa-user-circle';

// SPメニューの商品タグ表示用に、タグ情報を取得
$tags_obj_for_header = new ctags_for_products();
$grouped_tags_for_header = $tags_obj_for_header->get_all_tags_grouped_by_category(defined('DEBUG') ? DEBUG : true);

?>
<header class="header">
    <div class="header__inner">
        <button class="hamburger-menu">
            <span></span>
            <span></span>
            <span></span>
        </button>
        <h1 class="header__logo" style="margin-left: 10px;">
            <a href="index.php">SAKE BIT</a>
        </h1>
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
        <div class="sp-menu__login">
            <i class="<?php echo $login_icon; ?>"></i> <a href="<?php echo $login_link; ?>"><?php echo $login_text; ?></a>
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
                <?php if (!empty($grouped_tags_for_header)): ?>
                    <?php foreach ($grouped_tags_for_header as $category): ?>
                        <li>
                            <span class="sp-menu__tag-category-toggle">
                                <?php echo htmlspecialchars($category['tag_category_name']); ?> <i class="fas fa-chevron-down category-icon"></i>
                            </span>
                            <ul class="sp-menu__sub-sub-list">
                                <?php if (!empty($category['tags'])): ?>
                                    <?php foreach ($category['tags'] as $tag): ?>
                                        <li><a href="products_list.php?tag=<?php echo urlencode($tag['tag_name']); ?>"><?php echo htmlspecialchars($tag['tag_name']); ?></a></li>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <li><span>タグなし</span></li>
                                <?php endif; ?>
                            </ul>
                        </li>
                    <?php endforeach; ?>
                <?php else: ?>
                    <li><span>タグ情報がありません。</span></li>
                <?php endif; ?>
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