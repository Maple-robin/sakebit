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
    <header class="admin-header">
        <div class="admin-header__inner">
            <h1 class="admin-header__logo">
                <a href="client_top.php">OUR BRAND 管理者画面</a>
            </h1>
            <nav class="admin-header__nav">
                <ul class="admin-nav__list">
                    <li><a href="client_top.php">商品一覧</a></li>
                    <li><a href="client_add_product.php">お酒追加</a></li>
                    <li><a href="client_preview.php">プレビュー</a></li>
                    <li><a href="client_analytics.php" class="is-active">情報確認</a></li> </ul>
                <div class="admin-header__actions">
                    <a href="client_login.php" class="admin-header__logout">
                        <i class="fas fa-sign-out-alt"></i> ログアウト
                    </a>
                </div>
            </nav>
        </div>
    </header>

    <main class="admin-main">
        <div class="admin-container">
            <h2 class="admin-section-title">情報確認</h2>
            <div class="analytics-content">
                <div class="analytics-section">
                    <h3><i class="fas fa-chart-line"></i> サイト全体の閲覧情報 (直近30日間)</h3>
                    <ul class="stats-list">
                        <li><strong>総アクセス数:</strong> 15,234</li>
                        <li><strong>ユニークユーザー数:</strong> 8,912</li>
                        <li><strong>平均滞在時間:</strong> 00:03:45</li>
                        <li><strong>直帰率:</strong> 45.2%</li>
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
                                <tr>
                                    <td>1</td>
                                    <td>純米大吟醸 麗し乃雫</td>
                                    <td>120</td>
                                    <td>¥ 696,000</td>
                                </tr>
                                <tr>
                                    <td>2</td>
                                    <td>果実酒 桃源郷の誘い</td>
                                    <td>95</td>
                                    <td>¥ 304,000</td>
                                </tr>
                                <tr>
                                    <td>3</td>
                                    <td>スパークリングワイン 煌</td>
                                    <td>70</td>
                                    <td>¥ 315,000</td>
                                </tr>
                                <tr>
                                    <td>4</td>
                                    <td>月桂冠 スパークリング</td>
                                    <td>55</td>
                                    <td>¥ 82,500</td>
                                </tr>
                                <tr>
                                    <td>5</td>
                                    <td>白桃とレモングラスの果実酒</td>
                                    <td>48</td>
                                    <td>¥ 134,400</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="analytics-section">
                    <h3><i class="fas fa-tags"></i> 人気カテゴリ別閲覧数 (直近30日間)</h3>
                    <ul class="stats-list">
                        <li><strong>日本酒:</strong> 5,100 PV</li>
                        <li><strong>ワイン:</strong> 3,200 PV</li>
                        <li><strong>梅酒:</strong> 2,800 PV</li>
                        <li><strong>缶チューハイ:</strong> 1,500 PV</li>
                    </ul>
                </div>

                <div class="analytics-section">
                    <h3><i class="fas fa-hashtag"></i> 人気タグ別閲覧数 (直近30日間)</h3>
                    <ul class="stats-list">
                        <li><strong>#甘口:</strong> 4,800 PV</li>
                        <li><strong>#ギフト:</strong> 3,500 PV</li>
                        <li><strong>#初心者向け:</strong> 2,900 PV</li>
                        <li><strong>#華やか:</strong> 2,100 PV</li>
                        <li><strong>#家飲み:</strong> 1,800 PV</li>
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