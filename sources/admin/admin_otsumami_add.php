<?php
session_start();

if (!isset($_SESSION['admin_user_id']) || empty($_SESSION['admin_user_id'])) {
    header('Location: admin_login.php');
    exit();
}

require_once '../common/contents_db.php';
    
$debug = false;

$db_categories = new cotumami_categories();
$db_tags = new cotumami_tags();

$categories = $db_categories->get_all_categories($debug);
if ($categories === false) $categories = [];

$tags = $db_tags->get_all_tags($debug);
if ($tags === false) $tags = [];

$messages = $_SESSION['message'] ?? '';
$errors = $_SESSION['errors'] ?? [];
$old_input = $_SESSION['old_input'] ?? [];

unset($_SESSION['message'], $_SESSION['errors'], $_SESSION['old_input']);
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OUR BRAND | 新しいおつまみを登録</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@300;400;500;700&family=Zen+Old+Mincho:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../admincss/admin.css">
    <link rel="stylesheet" href="../admincss/admin_otsumami_add.css">
</head>
<body>

    <header class="admin-header">
        <div class="admin-header__inner">
            <h1 class="admin-header__logo">
                <a href="admin.php">OUR BRAND 管理者ページ</a>
            </h1>
            <nav class="admin-header__nav">
                <ul class="admin-nav__list">
                    <li><a href="admin_products.php">お酒管理</a></li>
                    <li><a href="admin_otsumami.php" class="is-current">おつまみ管理</a></li>
                    <li><a href="admin_users.php">ユーザー管理</a></li>
                    <li><a href="admin_posts.php">投稿管理</a></li>
                    <li><a href="admin_inquiries.php">お問い合わせ管理</a></li>
                    <li><a href="admin_faq.php">FAQ登録</a></li>
                    <li><a href="admin_reports.php">通報管理</a></li>
                    <li><a href="admin_logout.php">ログアウト</a></li> 
                </ul>
            </nav>
        </div>
    </header>

    <main class="admin-main">
        <div class="admin-main__inner">
            <h2 class="admin-page-title">
                <span class="en">ADD SNACK</span>
                <span class="ja">( 新しいおつまみを登録 )</span>
            </h2>

            <?php if (!empty($messages)): ?>
                <div class="success-message"><?= htmlspecialchars($messages) ?></div>
            <?php endif; ?>
            <?php if (!empty($errors)): ?>
                <div class="error-messages">
                    <ul>
                        <?php foreach ($errors as $error): ?>
                            <li><?= htmlspecialchars($error) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <section class="admin-section admin-otsumami-add-form">
                <form action="process_add_otsumami.php" method="post" enctype="multipart/form-data" class="admin-form">
                    <div class="form-group">
                        <label for="otsumami_name">おつまみ名 <span class="required-tag">必須</span></label>
                        <input type="text" id="otsumami_name" name="otsumami_name" required maxlength="128" value="<?= htmlspecialchars($old_input['otsumami_name'] ?? '') ?>">
                    </div>

                    <!-- ★★★★★★★★★★★★★★★★★★★★★★ -->
                    <!-- ★★★ ここからが修正箇所 ★★★ -->
                    <!-- ★★★★★★★★★★★★★★★★★★★★★★ -->
                    <div class="form-group">
                        <label for="main_image">メイン画像 <span class="required-tag">必須</span></label>
                        <input type="file" id="main_image" name="main_image" accept="image/*" required>
                        <div id="mainImagePreview" class="image-preview"></div>
                    </div>

                    <div class="form-group">
                        <label for="sub_images">サブ画像 (最大3枚)</label>
                        <input type="file" id="sub_images" name="sub_images[]" accept="image/*" multiple>
                        <div id="subImagesPreview" class="image-preview"></div>
                    </div>
                    <!-- ★★★★★★★★★★★★★★★★★★★★★★ -->
                    <!-- ★★★        修正箇所ここまで        ★★★ -->
                    <!-- ★★★★★★★★★★★★★★★★★★★★★★ -->

                    <div class="form-group">
                        <label for="category">合うお酒のカテゴリー <span class="required-tag">必須</span></label>
                        <select id="category" name="category" required>
                            <option value="">選択してください</option>
                            <?php foreach ($categories as $category): ?>
                                <option value="<?= htmlspecialchars($category['category_id']) ?>" <?= (isset($old_input['category']) && $old_input['category'] == $category['category_id']) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($category['category_name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>おつまみタグ</label>
                        <div class="checkbox-group">
                            <?php foreach ($tags as $tag): ?>
                                <label>
                                    <input type="checkbox" name="tags[]" value="<?= htmlspecialchars($tag['tag_id']) ?>" <?= (isset($old_input['tags']) && is_array($old_input['tags']) && in_array($tag['tag_id'], $old_input['tags'])) ? 'checked' : '' ?>>
                                    <?= htmlspecialchars($tag['tag_name']) ?>
                                </label>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="desc1">おつまみ説明1 <span class="required-tag">必須</span></label>
                        <textarea id="desc1" name="desc1" rows="4" required maxlength="200"><?= htmlspecialchars($old_input['desc1'] ?? '') ?></textarea>
                    </div>
                    <div class="form-group">
                        <label for="desc2">おつまみ説明2</label>
                        <textarea id="desc2" name="desc2" rows="4" maxlength="200"><?= htmlspecialchars($old_input['desc2'] ?? '') ?></textarea>
                    </div>

                    <div class="form-group">
                        <label for="price">価格 <span class="required-tag">必須</span></label>
                        <input type="number" id="price" name="price" required min="0" step="1" value="<?= htmlspecialchars($old_input['price'] ?? '') ?>">
                    </div>
                    <div class="form-group">
                        <label for="stock">在庫数 <span class="required-tag">必須</span></label>
                        <input type="number" id="stock" name="stock" required min="0" step="1" value="<?= htmlspecialchars($old_input['stock'] ?? '') ?>">
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary btn-lg">このおつまみを登録する</button>
                    </div>
                </form>
            </section>

            <div class="back-to-list-button-area">
                <a href="admin_otsumami.php" class="btn btn-secondary btn-back-to-list">おつまみ管理一覧に戻る</a>
            </div>
        </div>
    </main>

    <footer class="admin-footer">
        <div class="admin-footer__inner">
            <p class="admin-footer__copyright">© OUR BRAND Admin All Rights Reserved.</p>
        </div>
    </footer>

    <script>
    function previewImages(input, previewContainer) {
        const preview = document.querySelector(previewContainer);
        preview.innerHTML = '';
        const files = Array.from(input.files);
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
    }
    document.getElementById('main_image').addEventListener('change', function() {
        previewImages(this, '#mainImagePreview');
    });
    document.getElementById('sub_images').addEventListener('change', function() {
        previewImages(this, '#subImagesPreview');
    });
    </script>

</body>
</html>
