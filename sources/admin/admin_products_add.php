<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OUR BRAND | 新しいお酒を登録</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@300;400;500;700&family=Zen+Old+Mincho:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../admincss/admin.css">
    <link rel="stylesheet" href="../admincss/admin_products_add.css">
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
                    <li><a href="admin_otsumami.php">おつまみ管理</a></li>
                    <li><a href="admin_users.php">一般ユーザー管理</a></li>
                    <li><a href="admin_client_users.php">企業ユーザー管理</a></li>
                    <li><a href="admin_posts.php">投稿管理</a></li>
                    <li><a href="admin_inquiries.php">お問い合わせ管理</a></li>
                    <li><a href="admin_faq.php">FAQ管理</a></li>
                    <li><a href="admin_reports.php">通報管理</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <main class="admin-main">
        <div class="admin-main__inner">
            <h2 class="admin-page-title">
                <span class="en">ADD PRODUCT</span>
                <span class="ja">( 新しいお酒を登録 )</span>
            </h2>

            <section class="admin-section admin-liquor-add-form">
                <form action="admin_products_add_submit.php" method="post" enctype="multipart/form-data" class="admin-form">
                    <div class="form-group">
                        <label for="product-name">商品名 <span class="required">必須</span></label>
                        <input type="text" id="product-name" name="product_name" required maxlength="32">
                    </div>

                    <div class="form-group">
                        <label for="main-image">メイン画像 <span class="required">必須</span></label>
                        <input type="file" id="main-image" name="main_image" accept="image/*" required>
                    </div>

                    <div class="form-group">
                        <label for="sub-image1">サブ画像1</label>
                        <input type="file" id="sub-image1" name="sub_image1" accept="image/*">
                    </div>

                    <div class="form-group">
                        <label for="sub-image2">サブ画像2</label>
                        <input type="file" id="sub-image2" name="sub_image2" accept="image/*">
                    </div>

                    <div class="form-group">
                        <label for="sub-image3">サブ画像3</label>
                        <input type="file" id="sub-image3" name="sub_image3" accept="image/*">
                    </div>

                    <div class="form-group">
                        <label for="category">カテゴリ <span class="required">必須</span></label>
                        <select id="category" name="category" required>
                            <option value="">選択してください</option>
                            <option value="ビール">ビール</option>
                            <option value="ウイスキー">ウイスキー</option>
                            <option value="日本酒">日本酒</option>
                            <option value="中国酒">中国酒</option>
                            <option value="梅酒">梅酒</option>
                            <option value="缶チューハイ">缶チューハイ</option>
                            <option value="焼酎">焼酎</option>
                            <option value="スピリッツ">スピリッツ</option>
                            <option value="リキュール">リキュール</option>
                            <option value="ワイン">ワイン</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="tags">タグ <span class="required">必須</span></label>
                        <select id="tags" name="tags" required>
                            <option value="">選択してください</option>
                            <option value="初心者向け">初心者向け</option>
                            <option value="甘口">甘口</option>
                            <option value="辛口">辛口</option>
                            <option value="度数低め">度数低め</option> <!-- 新しいタグ -->
                            <option value="度数高め">度数高め</option> <!-- 新しいタグ -->
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="description">商品説明 <span class="required">必須</span></label>
                        <textarea id="description" name="description" rows="4" required maxlength="256"></textarea>
                    </div>

                    <div class="form-group">
                        <label for="volume">内容量 <span class="required">必須</span></label> <!-- 新規追加 -->
                        <input type="text" id="volume" name="volume" placeholder="例: 350ml, 700ml" required>
                    </div>

                    <div class="form-group">
                        <label for="features">特徴</label>
                        <textarea id="features" name="features" rows="4" maxlength="256"></textarea>
                    </div>

                    <div class="form-group">
                        <label for="recommendation">おすすめ飲み方</label>
                        <textarea id="recommendation" name="recommendation" rows="4" maxlength="256"></textarea>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary btn-lg">このお酒を登録する</button>
                    </div>
                </form>
            </section>

            <div class="back-to-list-button-area">
                <a href="admin_products.php" class="btn btn-secondary btn-back-to-list"> お酒管理一覧に戻る
                </a>
            </div>

        </div>
    </main>

    <footer class="admin-footer">
        <div class="admin-footer__inner">
            <p class="admin-footer__copyright">© OUR BRAND Admin All Rights Reserved.</p>
        </div>
    </footer>

</body>
</html>