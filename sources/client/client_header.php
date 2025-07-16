<?php
// 共通ヘッダー（client_header.php）
?>
<header class="admin-header">
    <div class="admin-header__inner">
        <h1 class="admin-header__logo">
            <a href="client_top.php">OUR BRAND 管理者画面</a>
        </h1>
        <nav class="admin-header__nav">
            <ul class="admin-nav__list">
                <li><a href="client_top.php"<?php if(basename($_SERVER['PHP_SELF'])==='client_top.php')echo' class="is-active"';?>>商品一覧</a></li>
                <li><a href="client_add_product.php"<?php if(basename($_SERVER['PHP_SELF'])==='client_add_product.php')echo' class="is-active"';?>>お酒追加</a></li>
                <li><a href="client_orders.php"<?php if(basename($_SERVER['PHP_SELF'])==='client_orders.php')echo' class="is-active"';?>>注文管理</a></li>
                <li><a href="client_preview.php"<?php if(basename($_SERVER['PHP_SELF'])==='client_preview.php')echo' class="is-active"';?>>プレビュー</a></li>
                <li><a href="client_analytics.php"<?php if(basename($_SERVER['PHP_SELF'])==='client_analytics.php')echo' class="is-active"';?>>情報確認</a></li>
            </ul>
            <div class="admin-header__actions">
                <a href="client_login.php" class="admin-header__logout">
                    <i class="fas fa-sign-out-alt"></i> ログアウト
                </a>
            </div>
        </nav>
    </div>
</header>
