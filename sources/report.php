<?php
/*!
@file report.php
@brief 投稿の通報ページ
@copyright Copyright (c) 2024 Your Name.
*/

// ★注意: DB接続やセッション開始は header.php で行われるため、ここでの処理は不要です。
// session_start();
// require_once __DIR__ . '/common/contents_db.php';

// ここに通報ページ固有のPHPロジックがあれば記述します。
// (例: GETパラメータから通報対象の投稿IDを取得する処理など)
?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>投稿の通報 | SAKE BIT</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@300;400;500;700&family=Zen+Old+Mincho:wght@400;500;700&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="css/report.css">
    <link rel="stylesheet" href="css/top.css">
</head>

<body>
    <?php 
    // 共通ヘッダーを読み込む
    require_once 'header.php'; 
    ?>

    <main>
        <div class="report-container">
            <h1 class="page-title">
                <span class="en">REPORT</span>
                <span class="ja">( 投稿の通報 )</span>
            </h1>

            <form id="report-form" class="report-form">
                <input type="hidden" id="post-id" value="12345">

                <p class="form-description">この投稿を通報する理由を選択してください。</p>

                <div class="form-group category-group">
                    <label class="form-label">通報カテゴリ <span class="required-badge">必須</span></label>
                    <div class="radio-options">
                        <label>
                            <input type="radio" name="report_category" value="不適切な内容" checked>
                            不適切な内容
                        </label>
                        <label>
                            <input type="radio" name="report_category" value="スパム">
                            スパム
                        </label>
                        <label>
                            <input type="radio" name="report_category" value="著作権侵害">
                            著作権侵害
                        </label>
                        <label>
                            <input type="radio" name="report_category" value="その他" id="category-other-radio">
                            その他
                        </label>
                    </div>
                    <p class="error-message" id="category-error"></p>
                </div>

                <div class="form-group">
                    <label for="report-content" class="form-label">
                        通報内容
                        <span id="other-required-badge" class="required-badge hidden">必須</span>
                        <span id="optional-badge" class="optional-badge">任意</span>
                    </label>
                    <textarea id="report-content" placeholder="具体的な通報内容を入力してください"></textarea>
                    <p class="error-message" id="content-error"></p>
                </div>

                <div class="form-actions">
                    <button type="submit" class="submit-button">通報する</button>
                    <button type="button" class="cancel-button" onclick="history.back()">キャンセル</button>
                </div>
                <p class="success-message" id="success-message"></p>
            </form>
        </div>
    </main>

    <?php 
    // 共通フッターを読み込む
    require_once 'footer.php'; 
    ?>

    <script src="js/report.js"></script>
    <script src="js/script.js"></script>
</body>

</html>
