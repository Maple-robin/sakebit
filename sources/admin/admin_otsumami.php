<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OUR BRAND | おつまみ管理（一覧）</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@300;400;500;700&family=Zen+Old+Mincho:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../admincss/admin.css">
    <link rel="stylesheet" href="../admincss/admin_otsumami.css">
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
                    <li><a href="admin_otsumami.php" class="is-current">おつまみ管理</a></li>
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
                <span class="en">SNACK MANAGEMENT</span>
                <span class="ja">( おつまみ管理 )</span>
            </h2>

            <section class="admin-section admin-otsumami-list">
                <div class="table-container">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>説明</th>
                                <th>合うお酒の種類</th>
                                <th>合うお酒のカテゴリ</th>
                                <th>お酒名</th>
                                <th>おつまみ名</th>
                                <th>メイン画像</th>
                                <th>サブ画像1</th>
                                <th>サブ画像2</th>
                                <th>サブ画像3</th>
                                <th>操作</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>クリームチーズと生ハムを乗せたクラッカー。</td>
                                <td>ワイン</td>
                                <td>軽め、フルーティー</td>
                                <td>銘柄ワインA</td>
                                <td>生ハムとクリームチーズクラッカー</td>
                                <td><img src="https://via.placeholder.com/60x60?text=Main" alt="クラッカー メイン" class="product-thumb"></td>
                                <td><img src="https://via.placeholder.com/60x60?text=Sub1" alt="クラッカー サブ1" class="product-thumb"></td>
                                <td><img src="https://via.placeholder.com/60x60?text=Sub2" alt="クラッカー サブ2" class="product-thumb"></td>
                                <td><img src="https://via.placeholder.com/60x60?text=Sub3" alt="クラッカー サブ3" class="product-thumb"></td>
                                <td>
                                    <div class="action-buttons">
                                        <a href="admin_otumami_edit.php" class="btn btn-sm btn-edit">編集</a>
                                        <button class="btn btn-sm btn-delete">削除</button>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>鶏むね肉とブロッコリーを蒸し、ポン酢とごま油で和える。</td>
                                <td>ビール</td>
                                <td>さっぱり</td>
                                <td>アサヒスーパードライ</td>
                                <td>蒸し鶏とブロッコリーの和え物</td>
                                <td><img src="https://via.placeholder.com/60x60?text=Main" alt="蒸し鶏 メイン" class="product-thumb"></td>
                                <td><img src="https://via.placeholder.com/60x60?text=Sub1" alt="蒸し鶏 サブ1" class="product-thumb"></td>
                                <td><img src="https://via.placeholder.com/60x60?text=Sub2" alt="蒸し鶏 サブ2" class="product-thumb"></td>
                                <td><img src="https://via.placeholder.com/60x60?text=Sub3" alt="蒸し鶏 サブ3" class="product-thumb"></td>
                                <td>
                                    <div class="action-buttons">
                                        <a href="#" class="btn btn-sm btn-edit">編集</a>
                                        <button class="btn btn-sm btn-delete">削除</button>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>タコとキュウリを一口大に切り、わさび醤油で和える。</td>
                                <td>日本酒</td>
                                <td>辛口、淡麗</td>
                                <td>獺祭 純米大吟醸</td>
                                <td>タコとキュウリのわさび和え</td>
                                <td><img src="https://via.placeholder.com/60x60?text=Main" alt="タコキュウリ メイン" class="product-thumb"></td>
                                <td><img src="https://via.placeholder.com/60x60?text=Sub1" alt="タコキュウリ サブ1" class="product-thumb"></td>
                                <td><img src="https://via.placeholder.com/60x60?text=Sub2" alt="タコキュウリ サブ2" class="product-thumb"></td>
                                <td><img src="https://via.placeholder.com/60x60?text=Sub3" alt="タコキュウリ サブ3" class="product-thumb"></td>
                                <td>
                                    <div class="action-buttons">
                                        <a href="#" class="btn btn-sm btn-edit">編集</a>
                                        <button class="btn btn-sm btn-delete">削除</button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </section>

            <div class="add-otsumami-button-area">
                <a href="admin_otsumami_add.php" class="btn btn-primary btn-add-new">
                    <span class="btn-icon">＋</span> 新しいおつまみを登録する
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