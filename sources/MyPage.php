<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>マイページ | OUR BRAND</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@300;400;500;700&family=Zen+Old+Mincho:wght@400;500;700&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="../css/MyPage.css">
    <link rel="stylesheet" href="../css/top.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
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

    <!-- スマホ用メニューも必要なら同様に -->
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
            <!-- ↓ここから追加 -->
            <li class="sp-menu__category-toggle">
                商品タグ <i class="fas fa-chevron-down category-icon"></i>
                <ul class="sp-menu__sub-list">
                    <li><a href="products_list.html?tag=初心者向け">初心者向け</a></li>
                    <li><a href="products_list.html?tag=甘口">甘口</a></li>
                    <li><a href="products_list.html?tag=辛口">辛口</a></li>
                    <li><a href="products_list.html?tag=度数高め">度数低め</a></li>
                    <li><a href="products_list.html?tag=度数低め">度数高め</a></li>
                </ul>
            </li>
            <!-- ↑ここまで追加 -->
            <li class="sp-menu__item"><a href="posts.html">投稿ページ</a></li>
            <li class="sp-menu__item"><a href="MyPage.html">マイページ</a></li>
        </ul>

        <div class="sp-menu__divider"></div>

        <ul class="sp-menu__list sp-menu__list--bottom">
            <li class="sp-menu__item"><a href="faq.html">よくある質問</a></li>
            <li class="sp-menu__item"><a href="contact.html">お問い合わせ</a></li>
        </ul>
    </nav>

    <main>
        <div class="mypage-container">
            <h1 class="page-title">
                <span class="en">MY PAGE</span>
                <span class="ja">( マイページ )</span>
            </h1>

            <section class="profile-section">
                <img src="https://placehold.co/100x100/FFD700/000000?text=USER" alt="ユーザーアイコン" class="profile-icon">
                <h2 class="profile-username">ユーザー名：サンプル太郎</h2>
                <p class="profile-birthday">誕生日：1990年1月1日</p>
                <p class="profile-bio">
                    お酒と美味しい料理をこよなく愛するサンプル太郎です。
                    特に日本酒の奥深さに魅了されており、週末は新しい銘柄を探しに出かけるのが趣味です。
                    皆さんとお酒に関する情報交換ができたら嬉しいです！
                </p>
                <button class="edit-profile-button">プロフィールを編集</button>
                <!-- 購入履歴ページへのリンクボタン -->
                <button class="history-button" onclick="window.location.href='history.html'">購入履歴を見る</button>
            </section>

            <section class="posts-section">
                <div class="tabs">
                    <button class="tab-button active" data-tab="my-posts">自分の投稿</button>
                    <button class="tab-button" data-tab="liked-posts">いいねした投稿</button>
                </div>
                <div id="my-posts-content" class="tab-content active">
                    <div class="posts-list">
                    </div>
                </div>
                <div id="liked-posts-content" class="tab-content">
                    <div class="posts-list">
                    </div>
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

    <script src="../js/script.js"></script>
    <script src="../js/MyPage.js"></script>
    <script>
        document.querySelector('.edit-profile-button').addEventListener('click', function () {
            window.location.href = 'profile_edit.html';
        });

        document.addEventListener('DOMContentLoaded', function () {
            // ハンバーガーメニュー
            const hamburgerMenu = document.querySelector('.hamburger-menu');
            const spMenu = document.querySelector('.sp-menu');
            if (hamburgerMenu && spMenu) {
                hamburgerMenu.addEventListener('click', function () {
                    hamburgerMenu.classList.toggle('is-active');
                    spMenu.classList.toggle('is-active');
                });
            }

            // カテゴリ・タグの開閉
            document.querySelectorAll('.sp-menu__category-toggle').forEach(function (toggle) {
                toggle.addEventListener('click', function (e) {
                    // アイコンやli以外のクリックは無視
                    if (e.target.tagName === 'A') return;
                    const subList = this.querySelector('.sp-menu__sub-list');
                    if (subList) {
                        subList.classList.toggle('is-open');
                    }
                    // アイコンの向きも切り替え
                    const icon = this.querySelector('.category-icon');
                    if (icon) {
                        icon.classList.toggle('open');
                    }
                });
            });
        });
    </script>
</body>

</html>