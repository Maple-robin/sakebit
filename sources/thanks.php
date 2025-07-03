<?php
/*!
@file thanks.php
@brief 購入完了ページ
@copyright Copyright (c) 2024 Your Name.
*/

// ★注意: DB接続やセッション開始は header.php で行われるため、ここでの処理は不要です。
// session_start();
// require_once __DIR__ . '/common/contents_db.php';

// ここに購入完了ページ固有のPHPロジックがあれば記述します。
?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ご購入ありがとうございました | OUR BRAND</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@300;400;500;700&family=Zen+Old+Mincho:wght@400;500;700&display=swap"
        rel="stylesheet">

    <link rel="stylesheet" href="https://unpkg.com/swiper@8/swiper-bundle.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <!-- ページ専用のCSSを読み込みます -->
    <link rel="stylesheet" href="css/thanks.css">
    <link rel="stylesheet" href="css/top.css">
</head>

<body>
    <?php 
    // 共通ヘッダーを読み込む
    require_once 'header.php'; 
    ?>

    <main>
        <section class="thank-you-section">
            <div class="thank-you-section__inner">
                <h2 class="thank-you-title">ご購入ありがとうございました</h2>
                <p class="thank-you-message">ご注文が正常に完了いたしました。</p>
                <p class="thank-you-message">ご注文内容は、ご登録のメールアドレスに送信いたしました。</p>
                <div class="thank-you-links">
                    <a href="products_list.php" class="btn-primary">引き続きお買い物をする</a>
                    <a href="MyPage.php" class="btn-secondary">注文履歴を見る</a>
                </div>
            </div>
        </section>
    </main>

    <?php 
    // 共通フッターを読み込む
    require_once 'footer.php'; 
    ?>

    <script src="https://unpkg.com/swiper@8/swiper-bundle.min.js"></script>
    <!-- script.jsはハンバーガーメニューとSPメニューの制御に使われます -->
    <script src="js/script.js"></script>
</body>

</html>
