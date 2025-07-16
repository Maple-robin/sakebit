<?php
/*!
@file admin_products.php
@brief 管理画面：お酒管理（一覧）
@copyright Copyright (c) 2024 Your Name.
*/

// PHPスクリプトの冒頭でセッションを開始（必要であれば）
// session_start();

// ログイン状態のチェック (必要であればコメントアウトを解除して使用)
// if (!isset($_SESSION['admin_user_id']) || empty($_SESSION['admin_user_id'])) {
//     header('Location: admin_login.php'); // ログインページのパスに修正
//     exit();
// }

require_once __DIR__ . '/../common/contents_db.php'; // contents_db.php を読み込み

// DEBUGモードの定義（config.phpで定義されていることを前提とするが、念のため）
if (!defined('DEBUG')) {
    define('DEBUG', true);
}

$product_info_obj = new cproduct_info();
$order_items_obj = new corder_items(); // 売れた数を取得するためのインスタンス
$product_views_obj = new cproduct_views(); // 訪問数を取得するためのインスタンス

// ページネーションを考慮せず全て取得しますが、データ量が多い場合はページネーション実装を検討してください
$products = $product_info_obj->get_product_list_for_admin(DEBUG, null, 0, 9999); // 全ての商品を取得
if ($products === false) {
    $products = []; // 取得失敗時は空の配列を設定
    error_log("Failed to fetch all product data.");
}

?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SAKE BIT | お酒管理（一覧）</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@300;400;500;700&family=Zen+Old+Mincho:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../admincss/admin.css">
    <link rel="stylesheet" href="../admincss/admin_products.css">
</head>
<body>

    <?php require_once 'admin_header.php'; ?>

    <main class="admin-main">
        <div class="admin-main__inner">
            <h2 class="admin-page-title">
                <span class="en">PRODUCTS MANAGEMENT</span>
                <span class="ja">( お酒管理 )</span>
            </h2>

            <section class="admin-section admin-liquor-list">
                <div class="table-container">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>企業名</th>
                                <th>商品名</th>
                                <th>画像</th>
                                <th>商品説明</th>
                                <th>価格</th>
                                <th>カテゴリ</th>
                                <th>タグ</th>
                                <th>商品の特徴</th>
                                <th>おすすめの飲み方</th>
                                <th>内容量</th>
                                <th>度数</th>
                                <th>在庫数</th>
                                <th>売れた数</th><!-- 新しい列 -->
                                <th>訪問数</th><!-- 新しい列 -->
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($products)): ?>
                                <?php foreach ($products as $product): ?>
                                    <?php
                                        // 商品IDを取得
                                        $product_id = $product['product_id'];

                                        // 画像パスを配列に変換し、各画像を表示
                                        $image_paths_str = $product['image_paths'] ?? '';
                                        $image_paths = !empty($image_paths_str) ? explode(';', $image_paths_str) : [];

                                        // タグを配列に変換（カンマ区切りで取得されている前提）
                                        $tags_str = $product['tags_concat'] ?? '';
                                        $tags = !empty($tags_str) ? explode(',', $tags_str) : [];

                                        // 売れた数を取得
                                        $sold_count = $order_items_obj->get_total_sold_count_by_product_id(DEBUG, $product_id);

                                        // 訪問数を取得
                                        $view_count = $product_views_obj->get_product_view_count(DEBUG, $product_id);
                                    ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($product['company_name'] ?? 'N/A'); ?></td>
                                        <td><?php echo htmlspecialchars($product['product_name'] ?? 'N/A'); ?></td>
                                        <td>
                                            <div class="image-thumbs">
                                                <?php if (!empty($image_paths)): ?>
                                                    <?php foreach ($image_paths as $image_path): ?>
                                                        <img src="../<?php echo htmlspecialchars($image_path); ?>" 
                                                             alt="<?php echo htmlspecialchars($product['product_name'] ?? '商品'); ?> 画像"
                                                             class="product-thumb">
                                                    <?php endforeach; ?>
                                                <?php else: ?>
                                                    <img src="https://placehold.co/60x60?text=NoImage" alt="画像なし" class="product-thumb">
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="scrollable-content">
                                                <?php echo nl2br(htmlspecialchars($product['product_description'] ?? 'N/A')); ?>
                                            </div>
                                        </td>
                                        <td><?php echo htmlspecialchars(number_format($product['product_price'] ?? 0)) . '円'; ?></td>
                                        <td><?php echo htmlspecialchars($product['category_name'] ?? 'N/A'); ?></td>
                                        <td>
                                            <div class="product-tags">
                                                <?php if (!empty($tags)): ?>
                                                    <?php foreach ($tags as $tag_name): ?>
                                                        <span><?php echo htmlspecialchars(trim($tag_name)); ?></span>
                                                    <?php endforeach; ?>
                                                <?php else: ?>
                                                    <span>N/A</span>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="scrollable-content">
                                                <?php echo nl2br(htmlspecialchars($product['product_discription'] ?? 'N/A')); ?>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="scrollable-content">
                                                <?php echo nl2br(htmlspecialchars($product['product_How'] ?? 'N/A')); ?>
                                            </div>
                                        </td>
                                        <td><?php echo htmlspecialchars($product['product_Contents'] ?? 'N/A'); ?></td>
                                        <td><?php echo htmlspecialchars($product['product_degree'] ?? 'N/A') . '%'; ?></td>
                                        <td><?php echo htmlspecialchars($product['product_stock'] ?? 'N/A'); ?></td>
                                        <td><?php echo htmlspecialchars($sold_count); ?></td><!-- 売れた数 -->
                                        <td><?php echo htmlspecialchars($view_count); ?></td><!-- 訪問数 -->
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="14">商品情報がありません。</td><!-- 列数が増えたのでcolspanも変更 -->
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </section>

        </div>
    </main>

    <footer class="admin-footer">
        <div class="admin-footer__inner">
            <p class="admin-footer__copyright">© SAKE BIT Admin All Rights Reserved.</p>
        </div>
    </footer>
    <!-- Font Awesomeの読み込み（CDN） -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/js/all.min.js"></script>

    <script src="../adminjs/admin.js"></script>
</body>
</html>