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
                    <li><a href="client_add_product.php" class="is-active">お酒追加</a></li> <li><a href="client_preview.php">プレビュー</a></li>
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
                    <input type="file" id="product_image" name="product_image" accept="image/*">
                    <p class="form-help-text">画像ファイルを最大四つまでPNG形式でアップロードしてください。</p>
                </div>
                <div class="form-group">
                    <label for="description">商品説明</label>
                    <textarea id="description" name="description" rows="5"></textarea>
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
                    <label for="tags">タグ <span class="required">(必須)</span></label>
                    <select id="tags" name="tags" required>
                        <option value="">選択してください</option>
                        <option value="初心者向け">初心者向け</option>
                        <option value="甘口">甘口</option>
                        <option value="辛口">辛口</option>
                        <option value="度数低め">度数低め</option>
                        <option value="度数高め">度数高め</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="features">商品の特徴</label>
                    <textarea id="features" name="features" rows="3"></textarea>
                </div>
                <div class="form-group">
                    <label for="recommendation">おすすめの飲み方</label>
                    <textarea id="recommendation" name="recommendation" rows="3"></textarea>
                </div>
                <div class="form-group">
                    <label for="volume">内容量 <span class="required">(必須)</span></label>
                    <input type="text" id="volume" name="volume" required>
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
</body>
</html>