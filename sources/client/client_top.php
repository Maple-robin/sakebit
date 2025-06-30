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
                    <li><a href="client_preview.php">プレビュー</a></li> <li><a href="client_analytics.php">情報確認</a></li>
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
                            <th>商品名</th>
                            <th>商品画像</th>
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
                        <tr>
                            <td>純米大吟醸 麗し乃雫</td>
                            <td><img src="../img/gingerale.png" alt="純米大吟醸 麗し乃雫" class="admin-table__img"></td>
                            <td>伊勢志摩地方の豊かな自然で育まれた上質な米と清らかな伏流水から生まれた純米大吟醸。</td>
                            <td>¥ 5,800</td>
                            <td>日本酒</td>
                            <td>
                                <div class="admin-table-tag-list">
                                    <span class="admin-table-tag">#日本酒</span>
                                    <span class="admin-table-tag">#華やか</span>
                                    <span class="admin-table-tag">#ギフト</span>
                                </div>
                            </td>
                            <td>芳醇な香りと透明感のある味わい。</td>
                            <td>冷酒でお召し上がりいただくのがおすすめ。</td>
                            <td>720ml</td>
                            <td>15.5%</td>
                            <td>
                                <div class="admin-action-buttons-group">
                                    <a href="client_add_product.php?id=1" class="admin-action-button admin-action-button--edit"><i class="fas fa-edit"></i> 編集</a>
                                    <a href="client_preview.php?id=1" class="admin-action-button admin-action-button--preview"><i class="fas fa-eye"></i> プレビュー</a>
                                    <a href="#" class="admin-action-button admin-action-button--delete"><i class="fas fa-trash-alt"></i> 削除</a>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>果実酒 桃源郷の誘い</td>
                            <td><img src="../img/osake.png" alt="果実酒 桃源郷の誘い" class="admin-table__img"></td>
                            <td>厳選された桃を贅沢に使用し、自然な甘みと香りを引き出した果実酒。</td>
                            <td>¥ 3,200</td>
                            <td>梅酒</td>
                            <td>#果実酒, #甘口, #女子会</td>
                            <td>フルーティーな香りとまろやかな味わい。</td>
                            <td>ロックまたはソーダ割りがおすすめ。</td>
                            <td>500ml</td>
                            <td>10%</td>
                            <td>
                                <div class="admin-action-buttons-group">
                                    <a href="client_add_product.php?id=2" class="admin-action-button admin-action-button--edit"><i class="fas fa-edit"></i> 編集</a>
                                    <a href="client_preview.php?id=2" class="admin-action-button admin-action-button--preview"><i class="fas fa-eye"></i> プレビュー</a>
                                    <a href="#" class="admin-action-button admin-action-button--delete"><i class="fas fa-trash-alt"></i> 削除</a>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>スパークリングワイン 煌</td>
                            <td><img src="../img/sake.png" alt="スパークリングワイン 煌" class="admin-table__img"></td>
                            <td>選りすぐりのブドウを使用し、華やかな泡立ちとフレッシュな酸味が特徴のスパークリングワイン。</td>
                            <td>¥ 4,500</td>
                            <td>ワイン</td>
                            <td>#ワイン, #スパークリング, #パーティー</td>
                            <td>クリーミーな泡と豊かな果実味。</td>
                            <td>冷やしてそのまま、またはカクテルベースに。</td>
                            <td>750ml</td>
                            <td>12%</td>
                            <td>
                                <div class="admin-action-buttons-group">
                                    <a href="client_add_product.php?id=3" class="admin-action-button admin-action-button--edit"><i class="fas fa-edit"></i> 編集</a>
                                    <a href="client_preview.php?id=3" class="admin-action-button admin-action-button--preview"><i class="fas fa-eye"></i> プレビュー</a>
                                    <a href="#" class="admin-action-button admin-action-button--delete"><i class="fas fa-trash-alt"></i> 削除</a>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>サントリー ほろよい</td>
                            <td><img src="../img/chuhai.png" alt="サントリー ほろよい" class="admin-table__img"></td>
                            <td>サントリーが贈る、すっきりとした飲み口の缶チューハイ。</td>
                            <td>¥ 200</td>
                            <td>缶チューハイ</td>
                            <td>#低アルコール, #お手軽, #家飲み</td>
                            <td>飲みやすく、さっぱりとした後味。</td>
                            <td>そのままでも、氷を入れても美味しくいただけます。</td>
                            <td>350ml</td>
                            <td>3%</td>
                            <td>
                                <div class="admin-action-buttons-group">
                                    <a href="client_add_product.php?id=4" class="admin-action-button admin-action-button--edit"><i class="fas fa-edit"></i> 編集</a>
                                    <a href="client_preview.php?id=4" class="admin-action-button admin-action-button--preview"><i class="fas fa-eye"></i> プレビュー</a>
                                    <a href="#" class="admin-action-button admin-action-button--delete"><i class="fas fa-trash-alt"></i> 削除</a>
                                </div>
                            </td>
                        </tr>
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