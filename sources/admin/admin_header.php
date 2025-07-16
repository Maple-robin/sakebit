<?php
$current_page = basename($_SERVER['PHP_SELF']);
?>
<header class="admin-header">
    <div class="admin-header__inner">
        <h1 class="admin-header__logo">
            <a href="admin_products.php">SAKE BIT 管理者ページ</a>
        </h1>
        <nav class="admin-header__nav">
            <ul class="admin-nav__list">
                <li><a href="admin_products.php" class="<?php if($current_page == 'admin_products.php') echo 'is-current'; ?>">お酒管理</a></li>
                <li><a href="admin_otsumami.php" class="<?php if($current_page == 'admin_otsumami.php') echo 'is-current'; ?>">おつまみ管理</a></li>
                <li><a href="admin_otumami_orders.php" class="<?php if($current_page == 'admin_otumami_orders.php') echo 'is-current'; ?>">注文管理</a></li>
                <li><a href="admin_users.php" class="<?php if($current_page == 'admin_users.php') echo 'is-current'; ?>">一般ユーザー管理</a></li>
                <li><a href="admin_client_users.php" class="<?php if($current_page == 'admin_client_users.php') echo 'is-current'; ?>">企業ユーザー管理</a></li>
                <li><a href="admin_posts.php" class="<?php if($current_page == 'admin_posts.php') echo 'is-current'; ?>">投稿管理</a></li>
                <li><a href="admin_inquiries.php" class="<?php if($current_page == 'admin_inquiries.php') echo 'is-current'; ?>">お問い合わせ管理</a></li>
                <li><a href="admin_faq.php" class="<?php if($current_page == 'admin_faq.php') echo 'is-current'; ?>">FAQ登録</a></li>
                <li><a href="admin_reports.php" class="<?php if($current_page == 'admin_reports.php') echo 'is-current'; ?>">通報管理</a></li>
                <li><a href="admin_login.php" class="<?php if($current_page == 'admin_login.php') echo 'is-current'; ?>">ログイン</a></li>
            </ul>
        </nav>
    </div>
</header>
