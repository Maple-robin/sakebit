<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ビールランキング | OUR BRAND</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@300;400;500;700&family=Zen+Old+Mincho:wght@400;500;700&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <!-- CSSファイルを修正後のproducts_list.cssに更新 -->
    <link rel="stylesheet" href="../css/products_list.css">
    <link rel="stylesheet" href="../css/top.css">
</head>

<body>
    <!-- 共通ヘッダー：index.htmlからコピー -->
    <header class="header">
        <div class="header__inner">
            <!-- ハンバーガーメニューを左端に配置 -->
            <button class="hamburger-menu">
                <span></span>
                <span></span>
                <span></span>
            </button>
            <!-- ロゴを中央に配置 -->
            <h1 class="header__logo">
                <a href="index.html">OUR BRAND</a>
            </h1>
            <!-- ナビゲーションとアイコンを右端に配置 -->
            <nav class="header__nav">
                <ul class="nav__list pc-only">
                    <li><a href="products_list.html">商品一覧</a></li>
                    <li><a href="contact.html">お問い合わせ</a></li>
                </ul>
                <div class="header__icons">
                    <a href="wishlist.html" class="header__icon-link">
                        <i class="fas fa-heart"></i>
                    </a>
                    <a href="cart.html" class="header__icon-link">
                        <i class="fas fa-shopping-cart"></i>
                    </a>
                </div>
            </nav>
        </div>
    </header>

    <nav class="sp-menu">
        <div class="sp-menu__header">
            <div class="sp-menu__login">
                <i class="fas fa-user-circle"></i> ログイン
            </div>
        </div>
        <div class="sp-menu__search">
            <input type="text" placeholder="検索...">
            <button type="submit"><i class="fas fa-search"></i></button>
        </div>
        <ul class="sp-menu__list">
            <li class="sp-menu__category-toggle">
                商品カテゴリ <i class="fas fa-chevron-down category-icon"></i>
                <ul class="sp-menu__sub-list">
                    <li><a href="products_list.html?category=日本酒">日本酒</a></li>
                    <li><a href="products_list.html?category=中国酒">中国酒</a></li>
                    <li><a href="products_list.html?category=梅酒">梅酒</a></li>
                    <li><a href="products_list.html?category=缶チューハイ">缶チューハイ</a></li>
                    <li><a href="products_list.html?category=焼酎">焼酎</a></li>
                    <li><a href="products_list.html?category=ウィスキー">ウィスキー</a></li>
                    <li><a href="products_list.html?category=スピリッツ">スピリッツ</a></li>
                    <li><a href="products_list.html?category=リキュール">リキュール</a></li>
                    <li><a href="products_list.html?category=ワイン">ワイン</a></li>
                    <li><a href="products_list.html?category=ビール">ビール</a></li>
                </ul>
            </li>
            <li class="sp-menu__category-toggle">
                商品タグ <i class="fas fa-chevron-down category-icon"></i>
                <ul class="sp-menu__sub-list">
                    <li><a href="products_list.html?tag=初心者向け">初心者向け</a></li>
                    <li><a href="products_list.html?tag=甘口">甘口</a></li>
                    <li><a href="products_list.html?tag=辛口">辛口</a></li>
                    <li><a href="products_list.html?tag=度数低め">度数低め</a></li>
                    <li><a href="products_list.html?tag=度数高め">度数高め</a></li>
                </ul>
            </li>
            <li class="sp-menu__item"><a href="posts.html">投稿ページ</a></li>
            <li class="sp-menu__item"><a href="MyPage.html">マイページ</a></li>
        </ul>
        <div class="sp-menu__divider"></div>
        <ul class="sp-menu__list sp-menu__list--bottom">
            <li class="sp-menu__item"><a href="faq.html">よくある質問</a></li>
            <li class="sp-menu__item"><a href="contact.html">お問い合わせ</a></li>
        </ul>
    </nav>

    <!-- ここから下はランキング等のメインコンテンツ -->
    <main>
        <div class="ranking-container">
            <h1 class="page-title">
                <span class="en">BEER LIST</span>
                <span class="ja">( ビール一覧 )</span>
            </h1>

            <div class="controls-section">
                <div class="filter-sort-buttons">
                    <button class="filter-button" id="filter-button">
                        絞り込み <i class="fas fa-bars"></i>
                    </button>
                    <button class="sort-button" id="sort-button">
                        並び替え <i class="fas fa-sort-amount-down"></i>
                    </button>
                </div>
                <div class="display-mode-buttons">
                    <button class="display-grid" id="display-grid"><i class="fas fa-th-large"></i></button>
                    <button class="display-list active" id="display-list"><i class="fas fa-list"></i></button>
                </div>
            </div>

            <div class="overlay" id="filter-overlay">
                <div class="filter-panel">
                    <div class="filter-panel__header">
                        <h2>絞り込み</h2>
                        <button class="close-button" id="filter-close-button"><i class="fas fa-times"></i></button>
                    </div>
                    <div class="filter-panel__content">
                        <div class="filter-group">
                            <h3>商品カテゴリ</h3>
                            <label><input type="checkbox" name="category" value="すべて"> すべての商品</label>
                            <label><input type="checkbox" name="category" value="日本酒"> 日本酒</label>
                            <label><input type="checkbox" name="category" value="中国酒"> 中国酒</label>
                            <label><input type="checkbox" name="category" value="梅酒"> 梅酒</label>
                            <label><input type="checkbox" name="category" value="缶チューハイ"> 缶チューハイ</label>
                            <label><input type="checkbox" name="category" value="焼酎"> 焼酎</label>
                            <label><input type="checkbox" name="category" value="ウィスキー"> ウィスキー</label>
                            <label><input type="checkbox" name="category" value="スピリッツ"> スピリッツ</label>
                            <label><input type="checkbox" name="category" value="リキュール"> リキュール</label>
                            <label><input type="checkbox" name="category" value="ワイン"> ワイン</label>
                            <label><input type="checkbox" name="category" value="ビール"> ビール</label>
                        </div>
                        <div class="filter-group">
                            <h3>商品タグ</h3>
                            <label><input type="checkbox" name="tag" value="初心者向け"> 初心者向け</label>
                            <label><input type="checkbox" name="tag" value="甘口"> 甘口</label>
                            <label><input type="checkbox" name="tag" value="辛口"> 辛口</label>
                            <label><input type="checkbox" name="tag" value="度数低め"> 度数低め</label>
                            <label><input type="checkbox" name="tag" value="度数高め"> 度数高め</label>
                        </div>
                        <button class="apply-filter-button">絞り込む</button>
                    </div>
                </div>
            </div>

            <div class="overlay" id="sort-overlay">
                <div class="sort-panel">
                    <div class="sort-panel__header">
                        <h2>並び替え</h2>
                        <button class="close-button" id="sort-close-button"><i class="fas fa-times"></i></button>
                    </div>
                    <div class="sort-panel__content">
                        <label><input type="radio" name="sort_order" value="newest"> 新しい順</label>
                        <label><input type="radio" name="sort_order" value="highest_price"> 価格の高い順</label>
                        <label><input type="radio" name="sort_order" value="lowest_price"> 価格の安い順</label>
                        <label><input type="radio" name="sort_order" value="ranking" checked> ランキング順</label>
                        <button class="apply-sort-button">並び替える</button>
                    </div>
                </div>
            </div>

            <section class="ranking-list-section">
                <!-- product-grid には、JavaScriptで生成される商品カードが挿入されます -->
                <div class="product-list" id="product-list">
                </div>
            </section>
        </div>
    </main>

    <footer class="footer">
        <div class="footer__inner">
            <ul class="footer__nav">
                <li>
                    <span class="footer__nav-title">商品一覧</span>
                    <ul class="footer__subnav">
                        <li><a href="products_list.html?category=日本酒">日本酒</a></li>
                        <li><a href="products_list.html?category=中国酒">中国酒</a></li>
                        <li><a href="products_list.html?category=梅酒">梅酒</a></li>
                        <li><a href="products_list.html?category=缶チューハイ">缶チューハイ</a></li>
                        <li><a href="products_list.html?category=焼酎">焼酎</a></li>
                        <li><a href="products_list.html?category=ウィスキー">ウィスキー</a></li>
                        <li><a href="products_list.html?category=スピリッツ">スピリッツ</a></li>
                        <li><a href="products_list.html?category=リキュール">リキュール</a></li>
                        <li><a href="products_list.html?category=ワイン">ワイン</a></li>
                        <li><a href="products_list.html?category=ビール">ビール</a></li>
                    </ul>
                </li>
                <li><a href="faq.html">よくあるご質問／お問合せ</a></li>
                <li><a href="MyPage.html">会員登録・ログイン</a></li>
                <li><a href="history.html">購入履歴</a></li>
                <li><a href="cart.html">買い物かごを見る</a></li>
                <li><a href="privacy.html">プライバシーポリシー</a></li>
                <li><a href="terms.html">利用規約</a></li>
            </ul>
            <div class="footer__logo" style="margin: 24px 0 12px;">
                <a href="index.html">
                    <img src="../img/logo.png" alt="OUR BRAND" style="height:32px;">
                </a>
            </div>
            <p class="footer__copyright">© OUR BRAND All Rights Reserved.</p>
        </div>
    </footer>

    <!-- JavaScriptファイルを修正後のproducts_list.jsに更新 -->
    <script src="../js/products_list.js"></script>
    <script src="../js/sticky-controls.js"></script>
    <script src="../js/script.js"></script>
</body>

</html>