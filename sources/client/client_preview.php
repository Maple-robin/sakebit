<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>商品プレビュー | OUR BRAND 管理者画面</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@300;400;500;700&family=Zen+Old+Mincho:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    
    <link rel="stylesheet" href="../clientcss/client.css"> 
    <link rel="stylesheet" href="../clientcss/client_preview.css"> 
    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css">
</head>
<body class="admin-page-layout">
    <?php include 'client_header.php'; ?>

    <main class="admin-main">
        <div class="admin-container">
            <h2 class="admin-section-title">商品プレビュー</h2>
            <div class="preview-explanation">
                <p>このセクションは、登録されたお酒が**お客様向けサイトでどのように表示されるか**を示すプレビューです。</p>
            </div>

            <div class="product-select-wrapper">
                <label for="product-select" class="select-label">プレビューする商品を選択:</label>
                <select id="product-select" class="admin-select">
                    <option value="">-- 商品を選択してください --</option>
                </select>
            </div>

            <div id="preview-content-area" class="product-detail-section">
                <div class="preview-placeholder-message">
                    <p><i class="fas fa-info-circle"></i> 上のプルダウンから商品を選択してください。</p>
                </div>
            </div>
            <div class="form-actions preview-actions" style="text-align: center;">
                <a href="client_top.php" class="admin-button admin-button--secondary">
                    <i class="fas fa-arrow-alt-circle-left"></i> 商品一覧に戻る
                </a>
            </div>
        </div>
    </main>

    <footer class="admin-footer">
        <p class="admin-footer__copyright">© OUR BRAND Admin All Rights Reserved.</p>
    </footer>

    <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
    <script src="../clientjs/client_preview.js"></script> 
</body>
</html>