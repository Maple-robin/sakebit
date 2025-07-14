<?php
// ログインチェックとセッション開始、デバッグ変数定義を共通ファイルに任せます
require_once 'auth_check.php';

// データベース操作クラスを読み込みます
require_once '../common/contents_db.php';

// データ取得処理
$product_db = new cproduct_info();
// auth_check.phpで定義された$client_idと$debugを渡します
$display_products = $product_db->get_product_list_for_admin($debug, $client_id, 0, 100);
$product_db = null;

?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>商品一覧 | OUR BRAND 管理者画面</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@300;400;500;700&family=Zen+Old+Mincho:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="../clientcss/client.css"> 
    <link rel="stylesheet" href="../clientcss/client_top.css"> 
</head>
<body class="admin-page-layout">
    <header class="admin-header">
        <div class="admin-header__inner">
            <h1 class="admin-header__logo">
                <a href="client_top.php">OUR BRAND 管理者画面</a>
            </h1>
            <nav class="admin-header__nav">
                <ul class="admin-nav__list">
                    <li><a href="client_top.php" class="is-active">商品一覧</a></li>
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
            <h2 class="admin-section-title">商品一覧</h2>
            <div class="admin-toolbar">
                <a href="client_add_product.php" class="admin-button admin-button--primary">
                    <i class="fas fa-plus-circle"></i> 新しいお酒を追加
                </a>
                <div class="admin-search">
                    <input type="text" placeholder="商品名を検索...">
                    <button type="submit"><i class="fas fa-search"></i></button>
                </div>
            </div>
            
            <div class="admin-table-wrapper">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>商品画像</th>
                            <th>商品名</th>
                            <th>商品説明</th>
                            <th>価格 (税込)</th>
                            <th>カテゴリ</th>
                            <th>タグ</th>
                            <th>商品の特徴</th>
                            <th>おすすめの飲み方</th>
                            <th>内容量</th>
                            <th>アルコール度数</th>
                            <th>在庫数</th>
                            <th>操作</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($display_products)): ?>
                            <tr>
                                <td colspan="12" style="text-align: center; padding: 40px;">商品はまだ登録されていません。</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($display_products as $product): ?>
                                <tr>
                                    <td>
                                        <?php 
                                        $images = [];
                                        if (!empty($product['image_paths'])) {
                                            $images = explode(';', $product['image_paths']);
                                        }
                                        $image_count = count($images);
                                        ?>
                                        <?php if ($image_count > 0): ?>
                                            <!-- ★★★ 画像の枚数に応じたクラスを付与 ★★★ -->
                                            <div class="admin-table__img-grid img-count-<?= $image_count ?>">
                                                <?php foreach ($images as $image_path): ?>
                                                    <img src="../<?= htmlspecialchars($image_path, ENT_QUOTES, 'UTF-8') ?>" alt="<?= htmlspecialchars($product['product_name'], ENT_QUOTES, 'UTF-8') ?>">
                                                <?php endforeach; ?>
                                            </div>
                                        <?php else: ?>
                                            <div class="admin-table__img-placeholder">画像なし</div>
                                        <?php endif; ?>
                                    </td>
                                    <td><?= htmlspecialchars($product['product_name'], ENT_QUOTES, 'UTF-8') ?></td>
                                    <td class="product-description"><div class="scrollable-cell"><?= nl2br(htmlspecialchars($product['product_description'], ENT_QUOTES, 'UTF-8')) ?></div></td>
                                    <td>¥ <?= number_format($product['product_price']) ?></td>
                                    <td><?= htmlspecialchars($product['category_name'] ?? '未分類', ENT_QUOTES, 'UTF-8') ?></td>
                                    <td>
                                        <?php if (!empty($product['tags_concat'])): ?>
                                            <div class="admin-table-tag-list">
                                                <?php 
                                                $tags = explode(',', $product['tags_concat']);
                                                foreach ($tags as $tag): 
                                                ?>
                                                    <span class="admin-table-tag">#<?= htmlspecialchars(trim($tag), ENT_QUOTES, 'UTF-8') ?></span>
                                                <?php endforeach; ?>
                                            </div>
                                        <?php endif; ?>
                                    </td>
                                    <td class="product-feature"><div class="scrollable-cell"><?= nl2br(htmlspecialchars($product['product_discription'], ENT_QUOTES, 'UTF-8')) ?></div></td>
                                    <td class="product-how"><div class="scrollable-cell"><?= nl2br(htmlspecialchars($product['product_How'], ENT_QUOTES, 'UTF-8')) ?></div></td>
                                    <td><?= htmlspecialchars($product['product_Contents'], ENT_QUOTES, 'UTF-8') ?></td>
                                    <td><?= htmlspecialchars($product['product_degree'] ?? 'N/A', ENT_QUOTES, 'UTF-8') ?> %</td>
                                    <td><?= htmlspecialchars($product['product_stock'], ENT_QUOTES, 'UTF-8') ?></td>
                                    <td>
                                        <div class="admin-action-buttons-group">
                                            <a href="client_edit_product.php?id=<?= $product['product_id'] ?>" class="admin-action-button admin-action-button--edit"><i class="fas fa-edit"></i> 編集</a>
                                            <a href="client_preview.php?id=<?= $product['product_id'] ?>" class="admin-action-button admin-action-button--preview"><i class="fas fa-eye"></i> プレビュー</a>
                                            <a href="#" class="admin-action-button admin-action-button--delete" onclick="return confirm('「<?= htmlspecialchars($product['product_name'], ENT_QUOTES, 'UTF-8') ?>」を本当に削除しますか？');"><i class="fas fa-trash-alt"></i> 削除</a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            
            <div class="pagination">
                <a href="#" class="page-link">&laquo; 前へ</a>
                <a href="#" class="page-link active">1</a>
                <a href="#" class="page-link">2</a>
                <a href="#" class="page-link">3</a>
                <a href="#" class="page-link">次へ &raquo;</a>
            </div>
        </div>
    </main>
    
    <footer class="admin-footer">
        <p class="admin-footer__copyright">© OUR BRAND Admin All Rights Reserved.</p>
    </footer>
</body>
</html>
