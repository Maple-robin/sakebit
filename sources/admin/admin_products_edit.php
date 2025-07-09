<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OUR BRAND | お酒管理（編集）</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@300;400;500;700&family=Zen+Old+Mincho:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../admincss/admin.css">
    <link rel="stylesheet" href="../admincss/admin_products_add.css">
</head>
<body>

    <header class="admin-header">
        <div class="admin-header__inner">            <h1 class="admin-header__logo">
                <a href="admin_products.php">OUR BRAND 管理者ページ</a>
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
                    <li><a href="admin_login.php">ログイン</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <main class="admin-main">
        <div class="admin-main__inner">
            <h2 class="admin-page-title">
                <span class="en">EDIT PRODUCT</span>
                <span class="ja">( お酒データ編集 )</span>
            </h2>

            <section class="admin-section admin-liquor-edit-form">
                <form action="#" method="post" enctype="multipart/form-data" class="admin-form">
                    <div class="form-group">
                        <label for="product_name">商品名 <span class="required">必須</span></label>
                        <input type="text" id="product_name" name="product_name" required maxlength="32" value="既存の商品名">
                    </div>

                    <div class="form-group">
                        <label for="main_image">メイン画像 <span class="required">必須</span></label>
                        <input type="file" id="main_image" name="main_image" accept="image/*">
                    </div>

                    <div class="form-group">
                        <label for="sub_image_1">サブ画像1</label>
                        <input type="file" id="sub_image_1" name="sub_image_1" accept="image/*">
                    </div>

                    <div class="form-group">
                        <label for="sub_image_2">サブ画像2</label>
                        <input type="file" id="sub_image_2" name="sub_image_2" accept="image/*">
                    </div>

                    <div class="form-group">
                        <label for="sub_image_3">サブ画像3</label>
                        <input type="file" id="sub_image_3" name="sub_image_3" accept="image/*">
                    </div>

                    <div class="form-group">
                        <label for="product_category">カテゴリ <span class="required">必須</span></label>
                        <select id="product_category" name="product_category" required>
                            <option value="">選択してください</option>
                            <option value="日本酒">日本酒</option>
                            <option value="中国酒">中国酒</option>
                            <option value="梅酒">梅酒</option>
                            <option value="缶チューハイ">缶チューハイ</option>
                            <option value="焼酎">焼酎</option>
                            <option value="ウィスキー">ウィスキー</option>
                            <option value="スピリッツ">スピリッツ</option>
                            <option value="リキュール">リキュール</option>
                            <option value="ワイン">ワイン</option>
                            <option value="ビール">ビール</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="product_tag">タグ <span class="required">必須</span></label>
                        <select id="product_tag" name="product_tag" required>
                            <option value="">選択してください</option>
                            <option value="初心者向け">初心者向け</option>
                            <option value="甘口">甘口</option>
                            <option value="辛口">辛口</option>
                            <option value="度数低め">度数低め</option>
                            <option value="度数高め">度数高め</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="product_description">商品説明 <span class="required">必須</span></label>
                        <textarea id="product_description" name="product_description" rows="6" maxlength="256">既存の商品説明</textarea>
                    </div>

                    <div class="form-group">
                        <label for="product_volume">内容量 <span class="required">必須</span></label>
                        <input type="text" id="product_volume" name="product_volume" required maxlength="32" value="既存の内容量">
                    </div>

                    <div class="form-group">
                        <label for="product_features">特徴 <span class="required">必須</span></label>
                        <textarea id="product_features" name="product_features" rows="4" maxlength="256" required>既存の特徴</textarea>
                    </div>

                    <div class="form-group">
                        <label for="product_recommendation">おすすめの飲み方 <span class="required">必須</span></label>
                        <textarea id="product_recommendation" name="product_recommendation" rows="4" maxlength="256" required>既存のおすすめの飲み方</textarea>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary btn-lg">この内容で保存する</button>
                    </div>
                </form>
            </section>

            <div class="back-to-list-button-area">
                <a href="admin_products.php" class="btn btn-secondary btn-back-to-list">
                    お酒管理一覧に戻る
                </a>
            </div>
        </div>
    </main>

    <footer class="admin-footer">
        <div class="admin-footer__inner">
            <p class="admin-footer__copyright">© OUR BRAND Admin All Rights Reserved.</p>
        </div>
    </footer>

    <script src="../adminjs/admin_liquor_edit.js"></script>

</body>
</html>
