<?php
/*!
@file wishlist.php
@brief お気に入り一覧ページ
@copyright Copyright (c) 2024 Your Name.
*/

// ★注意: DB接続やセッション開始は header.php で行われるため、ここでの処理は不要です。
// session_start();
// require_once __DIR__ . '/common/contents_db.php';

// ここにお気に入りページ固有のPHPロジックがあれば記述します。
// (例: ログインユーザーのお気に入り商品IDを取得し、DBから商品情報を取得する処理など)
?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>お気に入り | OUR BRAND</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@300;400;500;700&family=Zen+Old+Mincho:wght@400;500;700&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <!-- CSSファイルをwishlist.cssに更新 -->
    <link rel="stylesheet" href="css/wishlist.css">
    <link rel="stylesheet" href="css/top.css">
</head>

<body>
    <?php 
    // 共通ヘッダーを読み込む
    require_once 'header.php'; 
    ?>

    <!-- ここから下はお気に入り商品のメインコンテンツ -->
    <main>
        <div class="ranking-container">
            <!-- products_list.phpと同じコンテナ名を使用 -->
            <h1 class="page-title">
                <span class="en">FAVORITES LIST</span>
                <span class="ja">( お気に入り一覧 )</span>
            </h1>

            <section class="ranking-list-section">
                <!-- products_list.phpと同じセクション名を使用 -->
                <!-- product-grid には、JavaScriptで生成されるお気に入り商品カードが挿入されます -->
                <div class="product-grid" id="product-list"></div>
                <p class="no-favorites-message" style="display:none;">お気に入りの商品はありません。</p>
            </section>
        </div>
    </main>

    <?php 
    // 共通フッターを読み込む
    require_once 'footer.php'; 
    ?>

    <!-- JavaScriptファイルをwishlist.jsに更新 -->
    <script src="js/wishlist.js"></script>
    <script src="js/script.js"></script>
</body>

</html>
