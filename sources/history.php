<?php
/*!
@file history.php
@brief 購入履歴ページ
@copyright Copyright (c) 2024 Your Name.
*/

// ★注意: DB接続やセッション開始は header.php で行われるため、ここでの処理は不要です。
// session_start();
// require_once __DIR__ . '/common/contents_db.php';

// ここに購入履歴ページ固有のPHPロジックがあれば記述します。
// (例: ログインユーザーの購入履歴をDBから取得する処理など)

?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>購入履歴 | OUR BRAND</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@300;400;500;700&family=Zen+Old+Mincho:wght@400;500;700&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/swiper@8/swiper-bundle.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="css/top.css">
    <link rel="stylesheet" href="css/history.css">
</head>

<body>
    <?php 
    // 共通ヘッダーを読み込む
    require_once 'header.php'; 
    ?>

    <!-- メインコンテンツ -->
    <main>
        <section class="history">
            <div class="history__inner">
                <h2 class="section-title">
                    <span class="en">PURCHASE HISTORY</span>
                    <span class="ja">( 購入履歴 )</span>
                </h2>
                <ul class="history-list">
                    <li class="history-item">
                        <img src="img/sake.png" alt="商品画像" class="history-item__img">
                        <div class="history-item__details">
                            <h3 class="history-item__name">純米大吟醸 麗し乃雫</h3>
                            <p class="history-item__date">購入日: 2025年6月1日</p>
                            <p class="history-item__price">¥ 5,800 (税込)</p>
                        </div>
                    </li>
                    <li class="history-item">
                        <img src="img/osake.png" alt="商品画像" class="history-item__img">
                        <div class="history-item__details">
                            <h3 class="history-item__name">果実酒 桃源郷の誘い</h3>
                            <p class="history-item__date">購入日: 2025年5月20日</p>
                            <p class="history-item__price">¥ 3,200 (税込)</p>
                        </div>
                    </li>
                </ul>
                <!-- マイページへ戻るボタン -->
                <button class="return-button" onclick="window.location.href='MyPage.php'">マイページへ戻る</button>
            </div>
        </section>
    </main>

    <?php 
    // 共通フッターを読み込む
    require_once 'footer.php'; 
    ?>

    <script src="https://unpkg.com/swiper@8/swiper-bundle.min.js"></script>
    <script src="js/script.js"></script>
</body>

</html>
