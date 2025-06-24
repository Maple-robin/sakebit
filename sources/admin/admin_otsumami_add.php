<?php
// セッションを開始 (ファイルの先頭に必ず記述)
session_start();

// ログイン状態のチェック
// $_SESSION['admin_user_id']が設定されていない、または空の場合はログインページにリダイレクト
// ★変更点: admin_login.php から login.php に修正
if (!isset($_SESSION['admin_user_id']) || empty($_SESSION['admin_user_id'])) {
    header('Location: login.php'); // ログインページのパスに修正
    exit();
}

// contents_db.php を読み込む
// /home/j2025g/public_html/admin/admin_otsumami_add.php から見て
// /home/j2025g/public_html/common/contents_db.php への相対パス
require_once '../common/contents_db.php';
    
$debug = false; // デバッグモードをオンにするかどうか。開発中はtrue、本番環境ではfalseにすることを推奨。

// cotumami_categories および cotumami_tags クラスのインスタンスを生成
$db_categories = new cotumami_categories();
$db_tags = new cotumami_tags();

// カテゴリーデータを取得
$categories = $db_categories->get_all_categories($debug);
if ($categories === false) {
    $categories = []; // 取得失敗時は空の配列を設定
    error_log("Failed to fetch categories."); // エラーログに出力
}

// タグデータを取得
$tags = $db_tags->get_all_tags($debug);
if ($tags === false) {
    $tags = []; // 取得失敗時は空の配列を設定
    error_log("Failed to fetch tags."); // エラーログに出力
}

// セッションからメッセージと古い入力を取得し、表示後にクリア
$messages = $_SESSION['message'] ?? '';
$errors = $_SESSION['errors'] ?? [];
$old_input = $_SESSION['old_input'] ?? [];

unset($_SESSION['message']);
unset($_SESSION['errors']);
unset($_SESSION['old_input']);
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
                    <li><a href="admin_otsumami.php" >おつまみ管理</a></li>
                    <li><a href="admin_users.php">ユーザー管理</a></li>
                    <li><a href="admin_posts.php">投稿管理</a></li>
                    <li><a href="admin_inquiries.php">お問い合わせ管理</a></li>
                    <li><a href="admin_faq.php">FAQ登録</a></li>
                    <li><a href="admin_reports.php">通報管理</a></li>
                    <!-- ここにログアウトリンクを追加すると良いでしょう -->
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
                <div style="color: green; padding: 10px; border: 1px solid green; margin-bottom: 20px;">
                    <?php echo htmlspecialchars($messages); ?>
                </div>
            <?php endif; ?>
            <?php if (!empty($errors)): ?>
                <div style="color: red; padding: 10px; border: 1px solid red; margin-bottom: 20px;">
                    <ul>
                        <?php foreach ($errors as $error): ?>
                            <li><?php echo htmlspecialchars($error); ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <section class="admin-section admin-otsumami-add-form">
                <form action="process_add_otsumami.php" method="post" enctype="multipart/form-data" class="admin-form">
                    <div class="form-group">
                        <label for="otsumami_name">おつまみ名 <span class="required-tag">必須</span></label>
                        <input type="text" id="otsumami_name" name="otsumami_name" required maxlength="128"
                               value="<?php echo htmlspecialchars($old_input['otsumami_name'] ?? ''); ?>">
                    </div>

                    <div class="form-group">
                        <label>おつまみ画像 <span class="required-tag">最低1枚必須・最大4枚</span></label>
                        <input type="file" id="images" name="images[]" accept="image/*" multiple required>
                        <small class="form-note">1枚以上4枚まで選択してください。</small>
                        <div id="image-preview" class="image-preview">
                            <?php 
                            // フォームがエラーで戻ってきた場合、画像プレビューは再表示されないため、注意が必要です。
                            // 永続的なプレビューが必要な場合は、複雑なJS/Ajax処理が必要になります。
                            ?>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="category">おつまみカテゴリー <span class="required-tag">必須</span></label>
                        <select id="category" name="category" required>
                            <option value="">選択してください</option>
                            <?php 
                            $old_category_id = $old_input['category'] ?? '';
                            foreach ($categories as $category): ?>
                                <option value="<?php echo htmlspecialchars($category['category_id']); ?>"
                                    <?php echo ($old_category_id == $category['category_id']) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($category['category_name']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>おつまみタグ</label>
                        <div class="checkbox-group">
                            <?php 
                            $old_selected_tags = $old_input['tags'] ?? [];
                            if (!empty($tags)): ?>
                                <?php foreach ($tags as $tag): ?>
                                    <label>
                                        <input type="checkbox" name="tags[]" value="<?php echo htmlspecialchars($tag['tag_id']); ?>"
                                            <?php echo in_array($tag['tag_id'], $old_selected_tags) ? 'checked' : ''; ?>>
                                        <?php echo htmlspecialchars($tag['tag_name']); ?>
                                    </label>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <p>利用可能なタグがありません。</p>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="desc1">おつまみ説明1 <span class="required-tag">必須</span></label>
                        <textarea id="desc1" name="desc1" rows="4" required maxlength="200"><?php echo htmlspecialchars($old_input['desc1'] ?? ''); ?></textarea>
                    </div>
                    <div class="form-group">
                        <label for="desc2">おつまみ説明2</label>
                        <textarea id="desc2" name="desc2" rows="4" maxlength="200"><?php echo htmlspecialchars($old_input['desc2'] ?? ''); ?></textarea>
                    </div>

                    <div class="form-group">
                        <label for="price">価格 <span class="required-tag">必須</span></label>
                        <input type="number" id="price" name="price" required min="0" step="1"
                               value="<?php echo htmlspecialchars($old_input['price'] ?? ''); ?>">
                    </div>
                    <div class="form-group">
                        <label for="stock">在庫数 <span class="required-tag">必須</span></label>
                        <input type="number" id="stock" name="stock" required min="0" step="1"
                               value="<?php echo htmlspecialchars($old_input['stock'] ?? ''); ?>">
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary btn-lg">このおつまみを登録する</button>
                    </div>
                </form>
            </section>

            <div class="back-to-list-button-area">
                <a href="admin_otsumami.php" class="btn btn-secondary btn-back-to-list">
                    おつまみ管理一覧に戻る
                </a>
            </div>

        </div>
    </main>

    <footer class="admin-footer">
        <div class="admin-footer__inner">
            <p class="admin-footer__copyright">© OUR BRAND Admin All Rights Reserved.</p>
        </div>
    </footer>

    <script>
document.getElementById('images').addEventListener('change', function(e) {
    const preview = document.getElementById('image-preview');
    preview.innerHTML = '';
    const files = Array.from(e.target.files).slice(0, 4); // 最大4枚
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
