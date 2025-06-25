<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OUR BRAND | お酒管理（一覧）</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@300;400;500;700&family=Zen+Old+Mincho:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../admincss/admin.css">
    <link rel="stylesheet" href="../admincss/admin_products.css">
</head>
<body>

    <header class="admin-header">
        <div class="admin-header__inner">
            <h1 class="admin-header__logo">
                <a href="admin.php">OUR BRAND 管理者ページ</a>
            </h1>            <nav class="admin-header__nav">
                <ul class="admin-nav__list">
                    <li><a href="admin_products.php" class="is-current">お酒管理</a></li>
                    <li><a href="admin_otsumami.php">おつまみ管理</a></li>
                    <li><a href="admin_users.php">一般ユーザー管理</a></li>
                    <li><a href="admin_client_users.php">企業ユーザー管理</a></li>
                    <li><a href="admin_posts.php">投稿管理</a></li>
                    <li><a href="admin_inquiries.php">お問い合わせ管理</a></li>
                    <li><a href="admin_faq.php">FAQ管理</a></li>
                    <li><a href="admin_reports.php">通報管理</a></li>
                    <li><a href="login.php">ログイン</a></li>
                </ul>
            </nav>
        </div>
    </header>

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
                                <th>商品名</th>
                                <th>メイン画像</th>
                                <th>サブ画像1</th>
                                <th>サブ画像2</th>
                                <th>サブ画像3</th>
                                <th>カテゴリ</th>
                                <th>タグ</th>
                                <th>商品説明</th>
                                <th>内容量</th>
                                <th>特徴</th>
                                <th>おすすめ飲み方</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>アサヒスーパードライ</td>
                                <td><img src="https://via.placeholder.com/60x60?text=Main" alt="スーパードライ メイン" class="product-thumb"></td>
                                <td><img src="https://via.placeholder.com/60x60?text=Sub1" alt="スーパードライ サブ1" class="product-thumb"></td>
                                <td><img src="https://via.placeholder.com/60x60?text=Sub2" alt="スーパードライ サブ2" class="product-thumb"></td>
                                <td><img src="https://via.placeholder.com/60x60?text=Sub3" alt="スーパードライ サブ3" class="product-thumb"></td>
                                <td>ビール</td>
                                <td>初心者向け</td>
                                <td>辛口でキレのあるビールです。</td>
                                <td>350ml</td>
                                <td>爽快感があり、食事に合わせやすい。</td>
                                <td>冷やしてそのまま飲む。</td>
                            </tr>
                            <tr>
                                <td>山崎シングルモルト</td>
                                <td><img src="https://via.placeholder.com/60x60?text=Main" alt="山崎 メイン" class="product-thumb"></td>
                                <td><img src="https://via.placeholder.com/60x60?text=Sub1" alt="山崎 サブ1" class="product-thumb"></td>
                                <td><img src="https://via.placeholder.com/60x60?text=Sub2" alt="山崎 サブ2" class="product-thumb"></td>
                                <td><img src="https://via.placeholder.com/60x60?text=Sub3" alt="山崎 サブ3" class="product-thumb"></td>
                                <td>ウイスキー</td>
                                <td>甘口</td>
                                <td>日本を代表するシングルモルトウイスキー。</td>
                                <td>700ml</td>
                                <td>フルーティーで複雑な香り。</td>
                                <td>ストレートやロックで香りを楽しむ。</td>
                            </tr>
                            <tr>
                                <td>梅乃宿 あらごしみかん</td>
                                <td><img src="https://via.placeholder.com/60x60?text=Main" alt="あらごしみかん メイン" class="product-thumb"></td>
                                <td><img src="https://via.placeholder.com/60x60?text=Sub1" alt="あらごしみかん サブ1" class="product-thumb"></td>
                                <td><img src="https://via.placeholder.com/60x60?text=Sub2" alt="あらごしみかん サブ2" class="product-thumb"></td>
                                <td><img src="https://via.placeholder.com/60x60?text=Sub3" alt="あらごしみかん サブ3" class="product-thumb"></td>
                                <td>日本酒</td>
                                <td>甘口</td>
                                <td>みかんの果肉がたっぷり入ったデザート感覚のお酒。</td>
                                <td>720ml</td>
                                <td>フルーティーでデザート感覚。</td>
                                <td>冷やしてデザートと一緒に楽しむ。</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </section>

        </div>
    </main>

    <footer class="admin-footer">
        <div class="admin-footer__inner">
            <p class="admin-footer__copyright">© OUR BRAND Admin All Rights Reserved.</p>
        </div>
    </footer>

</body>
</html>