<?php
// 編集ページのPHP部分（後で実装）
// データベースや編集ロジックはここでは書かない
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>商品情報を編集 | OUR BRAND 管理者画面</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@300;400;500;700&family=Zen+Old+Mincho:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="../clientcss/client.css">
    <link rel="stylesheet" href="../clientcss/client_add_product.css">
</head>
<body class="admin-page-layout">
    <header class="admin-header">
        <div class="admin-header__inner">
            <h1 class="admin-header__logo">
                <a href="client_top.php">OUR BRAND 管理者画面</a>
            </h1>
            <nav class="admin-header__nav">
                <ul class="admin-nav__list">
                    <li><a href="client_top.php">商品一覧</a></li>
                    <li><a href="client_add_product.php">お酒追加</a></li>
                    <li><a href="client_preview.php">プレビュー</a></li>
                    <li><a href="client_analytics.php">情報確認</a></li>
                </ul>
                <div class="admin-header__actions">
                    <a href="logout.php" class="admin-header__logout">
                        <i class="fas fa-sign-out-alt"></i> ログアウト
                    </a>
                </div>
            </nav>
        </div>
    </header>

    <main class="admin-main">
        <div class="admin-container">
            <h2 class="admin-section-title">商品情報を編集</h2>
            <form action="#" method="post" class="add-product-form" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="product_name">商品名 <span class="required">(必須)</span></label>
                    <input type="text" id="product_name" name="product_name" required value="">
                </div>
                <div class="form-group">
                    <label for="main_image">メイン画像 <span class="required">(必須)</span></label>
                    <div class="custom-file-input-wrapper">
                        <input type="file" id="main_image" name="main_image" accept="image/png,image/jpeg,image/jpg" style="display:none;">
                        <button type="button" class="custom-file-btn" onclick="document.getElementById('main_image').click();">
                            <i class="fas fa-image"></i> メイン画像を選択
                        </button>
                        <span id="mainFileName" class="file-names">未選択</span>
                    </div>
                    <div id="mainImagePreview" class="image-preview"></div>
                </div>
                <div class="form-group">
                    <label for="sub_images">サブ画像 (最大3枚)</label>
                    <div class="custom-file-input-wrapper">
                        <input type="file" id="sub_images" name="sub_images[]" accept="image/png,image/jpeg,image/jpg" multiple style="display:none;">
                        <button type="button" class="custom-file-btn" onclick="document.getElementById('sub_images').click();">
                            <i class="fas fa-images"></i> サブ画像を選択
                        </button>
                        <span id="subFileNames" class="file-names">未選択</span>
                    </div>
                    <div id="subImagesPreview" class="image-preview"></div>
                </div>
                <div class="form-group">
                    <label for="description">商品説明 <span class="required">(必須)</span></label>
                    <textarea id="description" name="description" rows="5" required></textarea>
                </div>
                <div class="form-group">
                    <label for="price">価格 (税込) <span class="required">(必須)</span></label>
                    <input type="number" id="price" name="price" required min="0" value="">
                </div>
                <div class="form-group">
                    <label for="category">カテゴリ <span class="required">(必須)</span></label>
                    <select id="category" name="category" required>
                        <option value="">選択してください</option>
                        <!-- カテゴリは後でPHPで出力 -->
                    </select>
                </div>
                <div class="form-group">
                    <label>タグ <span class="required">(必須)</span></label>
                    <div class="tag-categories-container">
                        <!-- タグは後でPHPで出力 -->
                    </div>
                </div>
                <div class="form-group">
                    <label for="features">商品の特徴 <span class="required">(必須)</span></label>
                    <textarea id="features" name="features" rows="3" required></textarea>
                </div>
                <div class="form-group">
                    <label for="recommendation">おすすめの飲み方 <span class="required">(必須)</span></label>
                    <textarea id="recommendation" name="recommendation" rows="3" required></textarea>
                </div>
                <div class="form-group">
                    <label for="volume">内容量 <span class="required">(必須)</span></label>
                    <input type="text" id="volume" name="volume" required value="">
                </div>
                <div class="form-group">
                    <label for="alcohol_percent">度数 (%) <span class="required">(必須)</span></label>
                    <input type="number" id="alcohol_percent" name="alcohol_percent" required min="0" max="100" step="0.1" value="">
                </div>
                <div class="form-group">
                    <label for="stock">在庫数 <span class="required">(必須)</span></label>
                    <input type="number" id="stock" name="stock" required min="0" value="">
                </div>
                <div class="form-actions">
                    <button type="submit" class="admin-button admin-button--primary"><i class="fas fa-save"></i> 保存</button>
                    <button type="button" class="admin-button admin-button--secondary" onclick="history.back()"><i class="fas fa-times-circle"></i> キャンセル</button>
                </div>
            </form>
        </div>
    </main>

    <footer class="admin-footer">
        <p class="admin-footer__copyright">© OUR BRAND Admin All Rights Reserved.</p>
    </footer>

    <script>
    // 画像プレビューとファイル名表示用の共通関数
    function setupImagePreview(inputId, fileNameId, previewContainerId, maxFiles) {
        const input = document.getElementById(inputId);
        const fileNameSpan = document.getElementById(fileNameId);
        const previewContainer = document.getElementById(previewContainerId);

        input.addEventListener('change', function(e) {
            const files = Array.from(e.target.files);
            // ファイル名表示の更新
            if (files.length > 0) {
                if (files.length > maxFiles) {
                    fileNameSpan.textContent = maxFiles + '枚まで選択できます';
                    input.value = ""; // 選択をリセット
                    previewContainer.innerHTML = '';
                    return;
                }
                fileNameSpan.textContent = files.map(f => f.name).join(', ');
            } else {
                fileNameSpan.textContent = '未選択';
            }
            // プレビューの更新
            previewContainer.innerHTML = '';
            files.forEach(file => {
                if (!file.type.startsWith('image/')) return;
                const reader = new FileReader();
                reader.onload = function(evt) {
                    const img = document.createElement('img');
                    img.src = evt.target.result;
                    img.alt = '画像プレビュー';
                    img.classList.add('preview-thumb');
                    previewContainer.appendChild(img);
                };
                reader.readAsDataURL(file);
            });
        });
    }
    setupImagePreview('main_image', 'mainFileName', 'mainImagePreview', 1);
    setupImagePreview('sub_images', 'subFileNames', 'subImagesPreview', 3);
    </script>
</body>
</html>
