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

    <?php
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
    ?>

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

            <section class="admin-section admin-otsumami-add-form">
                <form action="#" method="post" enctype="multipart/form-data" class="admin-form">
                    <div class="form-group">
                        <label for="otsumami_name">おつまみ名 <span class="required-tag">必須</span></label>
                        <input type="text" id="otsumami_name" name="otsumami_name" required maxlength="128">
                    </div>

                    <div class="form-group">
                        <label>おつまみ画像 <span class="required-tag">最低1枚必須・最大4枚</span></label>
                        <input type="file" id="images" name="images[]" accept="image/*" multiple required>
                        <small class="form-note">1枚以上4枚まで選択してください。</small>
                        <div id="image-preview" class="image-preview"></div>
                    </div>

                    <div class="form-group">
                        <label for="category">おつまみカテゴリー <span class="required-tag">必須</span></label>
                        <select id="category" name="category" required>
                            <option value="">選択してください</option>
                            <?php foreach ($categories as $category): // ここに動的なカテゴリーオプションを挿入 ?>
                                <option value="<?php echo htmlspecialchars($category['category_id']); ?>">
                                    <?php echo htmlspecialchars($category['category_name']); ?>
                                </option>
                            <?php endforeach; ?>
                            <!-- 元の静的なオプションは削除またはコメントアウトしてください -->
                            <!--
                            <option value="和食">和食</option>
                            <option value="洋食">洋食</option>
                            <option value="中華">中華</option>
                            <option value="エスニック">エスニック</option>
                            <option value="スイーツ">スイーツ</option>
                            <option value="その他">その他</option>
                            -->
                        </select>
                    </div>

                    <div class="form-group">
                        <label>おつまみタグ</label>
                        <div class="checkbox-group">
                            <?php if (!empty($tags)): // ここに動的なタグチェックボックスを挿入 ?>
                                <?php foreach ($tags as $tag): ?>
                                    <label>
                                        <input type="checkbox" name="tags[]" value="<?php echo htmlspecialchars($tag['tag_id']); ?>">
                                        <?php echo htmlspecialchars($tag['tag_name']); ?>
                                    </label>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <p>利用可能なタグがありません。</p>
                            <?php endif; ?>
                            <!-- 元の静的なチェックボックスは削除またはコメントアウトしてください -->
                            <!--
                            <label><input type="checkbox" name="tags[]" value="簡単">簡単</label>
                            <label><input type="checkbox" name="tags[]" value="時短">時短</label>
                            <label><input type="checkbox" name="tags[]" value="おしゃれ">おしゃれ</label>
                            <label><input type="checkbox" name="tags[]" value="ヘルシー">ヘルシー</label>
                            <label><input type="checkbox" name="tags[]" value="おつまみ定番">おつまみ定番</label>
                            <label><input type="checkbox" name="tags[]" value="子供向け">子供向け</label>
                            <label><input type="checkbox" name="tags[]" value="大人向け">大人向け</label>
                            -->
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="desc1">おつまみ説明1 <span class="required-tag">必須</span></label>
                        <textarea id="desc1" name="desc1" rows="4" required maxlength="200"></textarea>
                    </div>
                    <div class="form-group">
                        <label for="desc2">おつまみ説明2</label>
                        <textarea id="desc2" name="desc2" rows="4" maxlength="200"></textarea>
                    </div>

                    <div class="form-group">
                        <label for="price">価格 <span class="required-tag">必須</span></label>
                        <input type="number" id="price" name="price" required min="0" step="1">
                    </div>
                    <div class="form-group">
                        <label for="stock">在庫数 <span class="required-tag">必須</span></label>
                        <input type="number" id="stock" name="stock" required min="0" step="1">
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
            // 元のCSSがimgタグに適用されるように、クラスは追加しません。
            // もし必要であれば、img.classList.add('thumbnail'); を追加してください。
            preview.appendChild(img);
        };
        reader.readAsDataURL(file);
    });
});
    </script>

</body>
</html>
