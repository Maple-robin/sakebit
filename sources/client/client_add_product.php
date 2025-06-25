<?php
// PHPスクリプトの冒頭でセッションを開始 (必要に応じて)
// session_start();

// contents_db.php を読み込む
// パスはあなたの環境に合わせて調整してください
require_once '../common/contents_db.php';

$debug = true; // デバッグモードをオンにするかどうか。開発中はtrue、本番環境ではfalseにすることを推奨。

// product_info 用のタグカテゴリーとタグのDBクラスインスタンスを生成
$db_tag_categories = new ctag_categories_for_products();
$db_tags = new ctags_for_products();

// 全てのタグカテゴリーを取得
$tag_categories = $db_tag_categories->get_all_tag_categories($debug);
if ($tag_categories === false) {
    $tag_categories = []; // 取得失敗時は空の配列を設定
    error_log("Failed to fetch tag categories for products.");
}

// 全てのタグを取得し、カテゴリーごとにグループ化
$all_tags = $db_tags->get_all_tags_with_category($debug);
$grouped_tags = [];
if ($all_tags) {
    foreach ($all_tags as $tag) {
        $grouped_tags[$tag['tag_category_name']][] = $tag;
    }
}

// 既存のカテゴリー（お酒の種類）を取得
$db_categories = new ccategories(); // お酒のカテゴリークラス
$categories = $db_categories->get_all($debug, 0, 9999); // 全て取得 (仮)
if ($categories === false) {
    $categories = [];
    error_log("Failed to fetch product categories.");
}

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
    <header class="admin-header">
        <div class="admin-header__inner">
            <h1 class="admin-header__logo">
                <a href="client_top.php">OUR BRAND 管理者画面</a>
            </h1>
            <nav class="admin-header__nav">
                <ul class="admin-nav__list">
                    <li><a href="client_top.php">商品一覧</a></li>
                    <li><a href="client_add_product.php" class="is-active">お酒追加</a></li>
                    <li><a href="client_preview.php">プレビュー</a></li>
                    <li><a href="client_analytics.php">情報確認</a></li>
                </ul>
                <div class="admin-header__actions">
                    <a href="login.php" class="admin-header__logout">
                        <i class="fas fa-sign-out-alt"></i> ログアウト
                    </a>
                </div>
            </nav>
        </div>
    </header>

    <main class="admin-main">
        <div class="admin-container">
            <h2 class="admin-section-title">新しいお酒を追加</h2>
            <form action="#" method="post" class="add-product-form" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="product_name">商品名 <span class="required">(必須)</span></label>
                    <input type="text" id="product_name" name="product_name" required>
                </div>
                <div class="form-group">
                    <label for="product_image">商品画像</label>
                    <div class="custom-file-input-wrapper">
                        <input type="file" id="product_image" name="product_image[]" accept="image/png,image/jpeg,image/jpg" multiple style="display:none;" required>
                        <button type="button" id="customFileBtn" class="custom-file-btn">
                            <i class="fas fa-image"></i> 画像を選択（最大4枚）
                        </button>
                        <span id="fileNames" class="file-names">未選択</span>
                    </div>
                    <div id="imagePreview" class="image-preview"></div>
                    <p class="form-help-text">画像ファイルを最大四つまでPNG形式でアップロードしてください。</p>
                </div>
                <div class="form-group">
                    <label for="description">商品説明 <span class="required">(必須)</span></label>
                    <textarea id="description" name="description" rows="5" required></textarea>
                    <p class="form-help-text">トップに表示される簡単な説明です。</p>
                </div>
                <div class="form-group">
                    <label for="price">価格 (税込) <span class="required">(必須)</span></label>
                    <input type="number" id="price" name="price" required min="0">
                </div>
                <div class="form-group">
                    <label for="category">カテゴリ <span class="required">(必須)</span></label>
                    <select id="category" name="category" required>
                        <option value="">選択してください</option>
                        <?php foreach ($categories as $cat): ?>
                            <option value="<?php echo htmlspecialchars($cat['category_id']); ?>">
                                <?php echo htmlspecialchars($cat['category_name']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- タグの複数選択とカテゴリー分け表示 -->
                <div class="form-group">
                    <label>タグ <span class="required">(必須)</span></label>
                    <div class="tag-categories-container">
                        <?php if (!empty($grouped_tags)): ?>
                            <?php foreach ($tag_categories as $tag_cat): ?>
                                <?php if (isset($grouped_tags[$tag_cat['tag_category_name']])): ?>
                                    <div class="tag-category-group">
                                        <div class="tag-category-name">
                                            <?php echo htmlspecialchars($tag_cat['tag_category_name']); ?>:
                                        </div>
                                        <div class="tag-checkbox-row">
                                            <?php foreach ($grouped_tags[$tag_cat['tag_category_name']] as $tag): ?>
                                                <label class="tag-checkbox-label">
                                                    <input type="checkbox" name="tags[]" value="<?php echo htmlspecialchars($tag['tag_id']); ?>">
                                                    <?php echo htmlspecialchars($tag['tag_name']); ?>
                                                </label>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p>利用可能なタグがありません。</p>
                        <?php endif; ?>
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
                    <input type="text" id="volume" name="volume" required>
                </div>
                <div class="form-group">
                    <label for="alcohol_percent">度数 (%) <span class="required">(必須)</span></label>
                    <input type="number" id="alcohol_percent" name="alcohol_percent" required min="0" max="100" step="0.1">
                </div>
                <div class="form-group">
                    <label for="stock">在庫数 <span class="required">(必須)</span></label>
                    <input type="number" id="stock" name="stock" required min="0">
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
document.getElementById('customFileBtn').addEventListener('click', function() {
    document.getElementById('product_image').click();
});

document.getElementById('product_image').addEventListener('change', function(e) {
    const files = Array.from(this.files).slice(0, 4); // 最大4枚
    // ファイル名表示
    document.getElementById('fileNames').textContent = files.length
        ? files.map(f => f.name).join(', ')
        : '未選択';

    // プレビュー
    const preview = document.getElementById('imagePreview');
    preview.innerHTML = '';
    files.forEach(file => {
        if (!file.type.startsWith('image/')) return;
        const reader = new FileReader();
        reader.onload = function(evt) {
            const img = document.createElement('img');
            img.src = evt.target.result;
            preview.appendChild(img);
        };
        reader.readAsDataURL(file);
    });
});
</script>
</body>
</html>
