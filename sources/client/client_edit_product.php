<?php
session_start();

// ログインチェックとDB接続クラスの読み込み
require_once 'auth_check.php';
require_once '../common/contents_db.php';

// ----------------------------------------------------------------
// 1. GETパラメータから商品IDを取得し、妥当性をチェック
// ----------------------------------------------------------------
$product_id = $_GET['id'] ?? null;
if (!$product_id || !is_numeric($product_id)) {
    // IDが無効な場合は一覧ページに戻す
    $_SESSION['message'] = ['type' => 'error', 'text' => '無効な商品IDです。'];
    header('Location: client_top.php');
    exit();
}

// ----------------------------------------------------------------
// 2. 必要なDBクラスを準備し、各種データを取得
// ----------------------------------------------------------------
$db_product = new cproduct_info();
$db_categories = new ccategories();
$db_tags = new ctags_for_products();
$db_product_tags = new cproduct_tags_relation();

// 編集対象の商品データを取得 (get_full_product_detailsは架空のメソッド。実際には必要な情報をJOINして取得する)
// ここでは、既存のget_product_list_for_adminを流用して対象商品を探します。
$product_list = $db_product->get_product_list_for_admin($debug, $_SESSION['client_id']);
$product = null;
foreach($product_list as $p) {
    if ($p['product_id'] == $product_id) {
        $product = $p;
        break;
    }
}


// 他のクライアントの商品を編集しようとした場合、または商品が見つからない場合は一覧に戻す
if (!$product) {
    $_SESSION['message'] = ['type' => 'error', 'text' => '商品が見つからないか、アクセス権がありません。'];
    header('Location: client_top.php');
    exit();
}

// フォームの選択肢用に、カテゴリとタグの全リストを取得
$categories = $db_categories->get_all($debug, 0, 9999);
$tag_categories_data = $db_tags->get_all_tags_grouped_by_category($debug);


// 現在の商品に紐づいているタグIDのリストを作成
$current_tag_ids_result = $db_product_tags->get_tags_by_product_id($debug, $product_id);
$current_tag_ids = array_column($current_tag_ids_result, 'tag_id');

// ----------------------------------------------------------------
// 3. セッションからエラー情報や入力保持データを取得
// ----------------------------------------------------------------
$errors = $_SESSION['edit_errors'] ?? [];
// エラーで戻ってきた場合はPOSTデータを、そうでなければDBのデータを表示
$old_data = $_SESSION['edit_old_data'] ?? $product; 

unset($_SESSION['edit_errors'], $_SESSION['edit_old_data']);
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>商品情報を編集 | SAKE BIT 管理者画面</title>
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
            <h1 class="admin-header__logo"><a href="client_top.php">SAKE BIT 管理者画面</a></h1>
            <nav class="admin-header__nav">
                <ul class="admin-nav__list">
                    <li><a href="client_top.php" class="is-active">商品一覧</a></li>
                    <li><a href="client_add_product.php">お酒追加</a></li>
                    <li><a href="client_preview.php">プレビュー</a></li>
                    <li><a href="client_analytics.php">情報確認</a></li>
                </ul>
                <div class="admin-header__actions">
                    <a href="client_login.php" class="admin-header__logout"><i class="fas fa-sign-out-alt"></i> ログアウト</a>
                </div>
            </nav>
        </div>
    </header>

    <main class="admin-main">
        <div class="admin-container">
            <h2 class="admin-section-title">商品情報を編集</h2>
            
            <?php if (!empty($errors)): ?>
                <div class="error-messages">
                    <?php foreach ($errors as $error): ?>
                        <p><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></p>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <form action="process_edit_product.php" method="post" class="add-product-form" enctype="multipart/form-data">
                <input type="hidden" name="product_id" value="<?= htmlspecialchars($product_id, ENT_QUOTES, 'UTF-8') ?>">

                <div class="form-group">
                    <label for="product_name">商品名 <span class="required">(必須)</span></label>
                    <input type="text" id="product_name" name="product_name" required value="<?= htmlspecialchars($old_data['product_name'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
                </div>

                <div class="form-group">
                    <label>現在の画像</label>
                    <div class="image-preview">
                        <?php 
                        if (!empty($product['image_paths'])) {
                            $image_paths = explode(';', $product['image_paths']);
                            foreach ($image_paths as $path) {
                                echo '<img src="../' . htmlspecialchars($path, ENT_QUOTES, 'UTF-8') . '" alt="現在の画像" class="preview-thumb">';
                            }
                        } else {
                            echo '<p>画像は登録されていません。</p>';
                        }
                        ?>
                    </div>
                    <p class="form-help-text">画像を更新する場合は、下のボタンから新しい画像をすべて選択し直してください。</p>
                    
                    <label for="main_image" style="margin-top:15px;">新しいメイン画像 <span class="required">(必須)</span></label>
                     <div class="custom-file-input-wrapper">
                        <input type="file" id="main_image" name="main_image" accept="image/*" style="display:none;">
                        <button type="button" class="custom-file-btn" onclick="document.getElementById('main_image').click();"><i class="fas fa-image"></i> メイン画像を選択</button>
                        <span id="mainFileName" class="file-names">未選択</span>
                    </div>
                    <div id="mainImagePreview" class="image-preview"></div>

                    <label for="sub_images" style="margin-top:15px;">新しいサブ画像 (最大3枚)</label>
                    <div class="custom-file-input-wrapper">
                        <input type="file" id="sub_images" name="sub_images[]" accept="image/*" multiple style="display:none;">
                        <button type="button" class="custom-file-btn" onclick="document.getElementById('sub_images').click();"><i class="fas fa-images"></i> サブ画像を選択</button>
                        <span id="subFileNames" class="file-names">未選択</span>
                    </div>
                    <div id="subImagesPreview" class="image-preview"></div>
                </div>

                <div class="form-group">
                    <label for="description">商品説明 <span class="required">(必須)</span></label>
                    <textarea id="description" name="description" rows="5" required><?= htmlspecialchars($old_data['product_description'] ?? '', ENT_QUOTES, 'UTF-8') ?></textarea>
                </div>
                <div class="form-group">
                    <label for="price">価格 (税込) <span class="required">(必須)</span></label>
                    <input type="number" id="price" name="price" required min="0" value="<?= htmlspecialchars($old_data['product_price'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
                </div>
                <div class="form-group">
                    <label for="category">カテゴリ <span class="required">(必須)</span></label>
                    <select id="category" name="category" required>
                        <?php foreach ($categories as $cat): ?>
                            <option value="<?= $cat['category_id'] ?>" <?= (isset($old_data['product_category']) && $old_data['product_category'] == $cat['category_id']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($cat['category_name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="form-group">
                    <label>タグ</label>
                    <div class="tag-categories-container">
                        <?php foreach ($tag_categories_data as $tag_cat): ?>
                            <div class="tag-category-group">
                                <h4 class="tag-category-name"><?= htmlspecialchars($tag_cat['tag_category_name'], ENT_QUOTES, 'UTF-8') ?></h4>
                                <div class="tag-checkbox-row">
                                    <?php foreach ($tag_cat['tags'] as $tag): ?>
                                        <label class="tag-checkbox-label">
                                            <input type="checkbox" name="tags[]" value="<?= $tag['tag_id'] ?>" 
                                                <?= in_array($tag['tag_id'], $current_tag_ids) ? 'checked' : '' ?>>
                                            <?= htmlspecialchars($tag['tag_name']) ?>
                                        </label>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <div class="form-group">
                    <label for="features">商品の特徴</label>
                    <textarea id="features" name="features" rows="3"><?= htmlspecialchars($old_data['product_discription'] ?? '', ENT_QUOTES, 'UTF-8') ?></textarea>
                </div>
                <div class="form-group">
                    <label for="recommendation">おすすめの飲み方</label>
                    <textarea id="recommendation" name="recommendation" rows="3"><?= htmlspecialchars($old_data['product_How'] ?? '', ENT_QUOTES, 'UTF-8') ?></textarea>
                </div>
                <div class="form-group">
                    <label for="volume">内容量</label>
                    <input type="text" id="volume" name="volume" value="<?= htmlspecialchars($old_data['product_Contents'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
                </div>
                <div class="form-group">
                    <label for="alcohol_percent">度数 (%)</label>
                    <input type="number" id="alcohol_percent" name="alcohol_percent" min="0" max="100" step="0.1" value="<?= htmlspecialchars($old_data['product_degree'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
                </div>
                <div class="form-group">
                    <label for="stock">在庫数</label>
                    <input type="number" id="stock" name="stock" min="0" value="<?= htmlspecialchars($old_data['product_stock'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
                </div>

                <div class="form-actions">
                    <button type="submit" class="admin-button admin-button--primary"><i class="fas fa-save"></i> 変更を保存</button>
                    <a href="client_top.php" class="admin-button admin-button--secondary"><i class="fas fa-times-circle"></i> キャンセル</a>
                </div>
            </form>
        </div>
    </main>

    <footer class="admin-footer">
        <p class="admin-footer__copyright">© SAKE BIT Admin All Rights Reserved.</p>
    </footer>

    <script>
    // 画像プレビューとファイル名表示用の共通関数
    function setupImagePreview(inputId, fileNameId, previewContainerId, maxFiles) {
        const input = document.getElementById(inputId);
        const fileNameSpan = document.getElementById(fileNameId);
        const previewContainer = document.getElementById(previewContainerId);

        input.addEventListener('change', function(e) {
            const files = Array.from(e.target.files);
            
            if (files.length > 0) {
                if (files.length > maxFiles) {
                    fileNameSpan.textContent = maxFiles + '枚まで選択できます';
                    input.value = ""; 
                    previewContainer.innerHTML = '';
                    return;
                }
                fileNameSpan.textContent = files.map(f => f.name).join(', ');
            } else {
                fileNameSpan.textContent = '未選択';
            }

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
