<?php
// ログインチェックとセッション開始、デバッグ変数定義を共通ファイルに任せます
require_once 'auth_check.php';

// データベース操作クラスを読み込みます
require_once '../common/contents_db.php';

// DBクラスのインスタンス化
$db_tag_categories = new ctag_categories_for_products();
$db_tags = new ctags_for_products();
$db_categories = new ccategories();

// 必要なデータをDBから取得
$tag_categories = $db_tag_categories->get_all_tag_categories($debug);
$all_tags = $db_tags->get_all_tags_with_category($debug);
$categories = $db_categories->get_all($debug, 0, 9999);

// タグをカテゴリごとにグループ化
$grouped_tags = [];
if ($all_tags) {
    foreach ($all_tags as $tag) {
        $grouped_tags[$tag['tag_category_name']][] = $tag;
    }
}

// セッションからのエラーメッセージや旧データを取得
$errors = $_SESSION['product_add_errors'] ?? [];
$old_data = $_SESSION['product_add_old_data'] ?? [];
$success_message = $_SESSION['product_add_success_message'] ?? null;

// 使用後にセッション変数を破棄
unset($_SESSION['product_add_errors'], $_SESSION['product_add_old_data'], $_SESSION['product_add_success_message']);

?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>新しいお酒を追加 | OUR BRAND 管理者画面</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@300;400;500;700&family=Zen+Old+Mincho:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="../clientcss/client.css">
    <link rel="stylesheet" href="../clientcss/client_add_product.css">
</head>
<body class="admin-page-layout">
    <?php include 'client_header.php'; ?>

    <main class="admin-main">
        <div class="admin-container">
            <h2 class="admin-section-title">新しいお酒を追加</h2>

            <?php if (!empty($errors)): ?>
                <div class="error-messages">
                    <?php foreach ($errors as $error): ?>
                        <p><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></p>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <?php if (isset($success_message)): ?>
                <div class="success-message">
                    <p><?= htmlspecialchars($success_message, ENT_QUOTES, 'UTF-8') ?></p>
                </div>
            <?php endif; ?>

            <form action="process_add_product.php" method="post" class="add-product-form" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="product_name">商品名 <span class="required">(必須)</span></label>
                    <input type="text" id="product_name" name="product_name" required value="<?= htmlspecialchars($old_data['product_name'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
                </div>

                <!-- ★★★★★★★★★★★★★★★★★★★★★★ -->
                <!-- ★★★ ここからが修正箇所 ★★★ -->
                <!-- ★★★★★★★★★★★★★★★★★★★★★★ -->
                <div class="form-group">
                    <label for="main_image">メイン画像 <span class="required">(必須)</span></label>
                    <div class="custom-file-input-wrapper">
                        <!-- 実際のファイル入力は非表示にする -->
                        <input type="file" id="main_image" name="main_image" accept="image/png,image/jpeg,image/jpg" required style="display:none;">
                        <!-- 見た目用のカスタムボタン -->
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
                         <!-- 実際のファイル入力は非表示にする -->
                        <input type="file" id="sub_images" name="sub_images[]" accept="image/png,image/jpeg,image/jpg" multiple style="display:none;">
                        <!-- 見た目用のカスタムボタン -->
                        <button type="button" class="custom-file-btn" onclick="document.getElementById('sub_images').click();">
                            <i class="fas fa-images"></i> サブ画像を選択
                        </button>
                        <span id="subFileNames" class="file-names">未選択</span>
                    </div>
                    <div id="subImagesPreview" class="image-preview"></div>
                </div>
                <!-- ★★★★★★★★★★★★★★★★★★★★★★ -->
                <!-- ★★★        修正箇所ここまで        ★★★ -->
                <!-- ★★★★★★★★★★★★★★★★★★★★★★ -->

                <div class="form-group">
                    <label for="description">商品説明 <span class="required">(必須)</span></label>
                    <textarea id="description" name="description" rows="5" required><?= htmlspecialchars($old_data['description'] ?? '', ENT_QUOTES, 'UTF-8') ?></textarea>
                </div>
                <div class="form-group">
                    <label for="price">価格 (税込) <span class="required">(必須)</span></label>
                    <input type="number" id="price" name="price" required min="0" value="<?= htmlspecialchars($old_data['price'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
                </div>
                <div class="form-group">
                    <label for="category">カテゴリ <span class="required">(必須)</span></label>
                    <select id="category" name="category" required>
                        <option value="">選択してください</option>
                        <?php foreach ($categories as $cat): ?>
                            <option value="<?= htmlspecialchars($cat['category_id'], ENT_QUOTES, 'UTF-8') ?>" <?= (isset($old_data['category']) && $old_data['category'] == $cat['category_id']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($cat['category_name'], ENT_QUOTES, 'UTF-8') ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label>タグ <span class="required">(必須)</span></label>
                    <div class="tag-categories-container">
                        <?php foreach ($tag_categories as $tag_cat): ?>
                            <?php if (isset($grouped_tags[$tag_cat['tag_category_name']])): ?>
                                <div class="tag-category-group">
                                    <h4 class="tag-category-name"><?= htmlspecialchars($tag_cat['tag_category_name'], ENT_QUOTES, 'UTF-8') ?></h4>
                                    <div class="tag-checkbox-row">
                                        <?php foreach ($grouped_tags[$tag_cat['tag_category_name']] as $tag): ?>
                                            <label class="tag-checkbox-label">
                                                <input type="checkbox" name="tags[]" value="<?= htmlspecialchars($tag['tag_id'], ENT_QUOTES, 'UTF-8') ?>" <?= (isset($old_data['tags']) && is_array($old_data['tags']) && in_array($tag['tag_id'], $old_data['tags'])) ? 'checked' : '' ?>>
                                                <?= htmlspecialchars($tag['tag_name'], ENT_QUOTES, 'UTF-8') ?>
                                            </label>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </div>
                </div>

                <div class="form-group">
                    <label for="features">商品の特徴 <span class="required">(必須)</span></label>
                    <textarea id="features" name="features" rows="3" required><?= htmlspecialchars($old_data['features'] ?? '', ENT_QUOTES, 'UTF-8') ?></textarea>
                </div>
                <div class="form-group">
                    <label for="recommendation">おすすめの飲み方 <span class="required">(必須)</span></label>
                    <textarea id="recommendation" name="recommendation" rows="3" required><?= htmlspecialchars($old_data['recommendation'] ?? '', ENT_QUOTES, 'UTF-8') ?></textarea>
                </div>
                <div class="form-group">
                    <label for="volume">内容量 <span class="required">(必須)</span></label>
                    <input type="text" id="volume" name="volume" required value="<?= htmlspecialchars($old_data['volume'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
                </div>
                <div class="form-group">
                    <label for="alcohol_percent">度数 (%) <span class="required">(必須)</span></label>
                    <input type="number" id="alcohol_percent" name="alcohol_percent" required min="0" max="100" step="0.1" value="<?= htmlspecialchars($old_data['alcohol_percent'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
                </div>
                <div class="form-group">
                    <label for="stock">在庫数 <span class="required">(必須)</span></label>
                    <input type="number" id="stock" name="stock" required min="0" value="<?= htmlspecialchars($old_data['stock'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
                </div>
                <div class="form-actions">
                    <button type="submit" class="admin-button admin-button--primary"><i class="fas fa-save"></i> 登録</button>
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

    // メイン画像とサブ画像に、それぞれ設定を適用
    setupImagePreview('main_image', 'mainFileName', 'mainImagePreview', 1);
    setupImagePreview('sub_images', 'subFileNames', 'subImagesPreview', 3);
    </script>
</body>
</html>
