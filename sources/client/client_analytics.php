<?php
// --- ▼ データベース接続とデータ取得処理 ▼ ---

// ★【修正】このファイルがある場所の親ディレクトリのパスを取得します
$project_root = dirname(__DIR__); 

// ★【修正】正しい場所(/common/contents_db.php)を絶対パスで読み込みます
require_once($project_root . '/common/contents_db.php'); 

// 分析用クラスのインスタンスを作成します
$canalytics = new canalytics(); 
// 分析クラスのインスタンスが持つデータベース接続情報を取得します
$db_connection = $canalytics->get_pdo(); 

// データ取得期間（デフォルトは直近30日間）
$date_from = date('Y-m-d H:i:s', strtotime('-30 day'));
$date_to = date('Y-m-d H:i:s');

// 取得したデータベース接続情報を各メソッドに渡します
// 1. サイト全体の閲覧情報を取得
$site_stats = $canalytics->get_site_stats($db_connection, $date_from, $date_to);

// 2. 売上ランキングを取得 (上位5件)
$sales_ranking = $canalytics->get_sales_ranking($db_connection, $date_from, $date_to, 5);

// 3. 人気カテゴリ別閲覧数を取得 (上位4件)
$category_ranking = $canalytics->get_category_view_ranking($db_connection, $date_from, $date_to, 4);

// 4. 人気タグ別閲覧数を取得 (上位5件)
$tag_ranking = $canalytics->get_tag_view_ranking($db_connection, $date_from, $date_to, 5);

// --- ▲ データベース接続とデータ取得処理 ▲ ---
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>情報確認 | OUR BRAND 管理者画面</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@300;400;500;700&family=Zen+Old+Mincho:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="../clientcss/client.css"> 
    <link rel="stylesheet" href="../clientcss/client_analytics.css"> 
</head>
<body class="admin-page-layout">
    <?php include 'client_header.php'; ?>

    <main class="admin-main">
        <div class="admin-container">
            <h2 class="admin-section-title">情報確認</h2>
            <div class="analytics-content">
                <div class="analytics-section">
                    <h3><i class="fas fa-chart-line"></i> サイト全体の閲覧情報 (直近30日間)</h3>
                    <ul class="stats-list">
                        <!-- ▼ PHPで動的に表示 ▼ -->
                        <li><strong>総アクセス数:</strong> <?php echo number_format($site_stats['total_views'] ?? 0); ?></li>
                        <li><strong>ユニークユーザー数:</strong> <?php echo number_format($site_stats['unique_users'] ?? 0); ?>
                            <br><span>※ユニークユーザー数とは、同じ期間内にサイトを訪れた重複しない利用者の数です。1人が複数回アクセスしても1人としてカウントされます。</span>
                        </li>
                        <!-- ▲ PHPで動的に表示 ▲ -->
                    </ul>
                </div>

                <div class="analytics-section">
                    <h3><i class="fas fa-trophy"></i> 売上ランキング (直近30日間)</h3>
                    <div class="admin-table-wrapper">
                        <table class="admin-table analytics-table">
                            <thead>
                                <tr>
                                    <th>順位</th>
                                    <th>商品名</th>
                                    <th>販売数</th>
                                    <th>売上 (税込)</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- ▼ PHPで動的に表示 ▼ -->
                                <?php if (!empty($sales_ranking)): ?>
                                    <?php foreach ($sales_ranking as $index => $item): ?>
                                    <tr>
                                        <td><?php echo $index + 1; ?></td>
                                        <td><?php echo htmlspecialchars($item['product_name'], ENT_QUOTES, 'UTF-8'); ?></td>
                                        <td><?php echo number_format($item['total_quantity']); ?></td>
                                        <td>¥ <?php echo number_format($item['total_sales']); ?></td>
                                    </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr><td colspan="4">データがありません。</td></tr>
                                <?php endif; ?>
                                <!-- ▲ PHPで動的に表示 ▲ -->
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="analytics-section">
                    <h3><i class="fas fa-tags"></i> 人気カテゴリ別閲覧数 (直近30日間)</h3>
                    <ul class="stats-list">
                        <!-- ▼ PHPで動的に表示 ▼ -->
                        <?php if (!empty($category_ranking)): ?>
                            <?php foreach ($category_ranking as $item): ?>
                                <li><strong><?php echo htmlspecialchars($item['category_name'], ENT_QUOTES, 'UTF-8'); ?>:</strong> <?php echo number_format($item['view_count']); ?> PV</li>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <li>データがありません。</li>
                        <?php endif; ?>
                        <!-- ▲ PHPで動的に表示 ▲ -->
                    </ul>
                </div>

                <div class="analytics-section">
                    <h3><i class="fas fa-hashtag"></i> 人気タグ別閲覧数 (直近30日間)</h3>
                    <ul class="stats-list">
                        <!-- ▼ PHPで動的に表示 ▼ -->
                        <?php if (!empty($tag_ranking)): ?>
                            <?php foreach ($tag_ranking as $item): ?>
                                <li><strong>#<?php echo htmlspecialchars($item['tag_name'], ENT_QUOTES, 'UTF-8'); ?>:</strong> <?php echo number_format($item['view_count']); ?> PV</li>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <li>データがありません。</li>
                        <?php endif; ?>
                        <!-- ▲ PHPで動的に表示 ▲ -->
                    </ul>
                </div>
                
            </div>
        </div>
    </main>

    <footer class="admin-footer">
        <p class="admin-footer__copyright">© OUR BRAND Admin All Rights Reserved.</p>
    </footer>
</body>
</html>
