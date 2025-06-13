<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>買い物かご | OUR BRAND</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@300;400;500;700&family=Zen+Old+Mincho:wght@400;500;700&display=swap"
        rel="stylesheet">

    <link rel="stylesheet" href="https://unpkg.com/swiper@8/swiper-bundle.min.css" />
    <link rel="stylesheet" href="../css/top.css">
    <link rel="stylesheet" href="../css/cart.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>

<body>

    <header class="header">
        <div class="header__inner">
            <button class="hamburger-menu">
                <span></span>
                <span></span>
                <span></span>
            </button>
            <h1 class="header__logo">
                <a href="index.html">OUR BRAND</a>
            </h1>
            <nav class="header__nav">
                <ul class="nav__list pc-only">
                    <li><a href="products_list.html">商品一覧</a></li>
                    <li><a href="contact.html">お問い合わせ</a></li>
                </ul>
                <div class="header__icons">
                    <a href="wishlist.html" class="header__icon-link">
                        <i class="fas fa-heart"></i>
                    </a>
                    <a href="cart.html" class="header__icon-link active">
                        <!-- カートページなのでactiveクラスを追加 -->
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

    <main>
        <section class="cart-section">
            <div class="cart-section__inner common-inner">
                <h2 class="section-title">
                    <span class="en">SHOPPING CART</span>
                    <span class="ja">(買い物かご)</span>
                </h2>

                <div class="cart-links-top"> <a href="product.html" class="btn-continue-shopping-top">
                        <i class="fas fa-chevron-left"></i> お買い物を続ける
                    </a>
                </div>

                <div class="cart-main-container">
                    <div class="cart-items" id="cart-items-container">
                        <div class="cart-item">
                            <div class="cart-item__image">
                                <img src="../img/gingerale.png" alt="イセ ポメロモ ヒート">
                            </div>
                            <div class="cart-item__details">
                                <h3 class="cart-item__name">【販売再開】イセ ポメロモ ヒート</h3>
                                <p class="cart-item__size">200ml</p>
                                <p class="cart-item__price">¥ 1,750</p>
                                <div class="cart-item__quantity-controls">
                                    <button class="quantity-minus" data-id="product-1">-</button>
                                    <input type="number" class="quantity-input" value="1" min="1" data-id="product-1">
                                    <button class="quantity-plus" data-id="product-1">+</button>
                                </div>
                            </div>
                            <button class="cart-item__remove" data-id="product-1">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </div>
                        <div class="cart-item">
                            <div class="cart-item__image">
                                <img src="../img/berry.png" alt="セレンディピティ・レモン">
                            </div>
                            <div class="cart-item__details">
                                <h3 class="cart-item__name">セレンディピティ・レモン</h3>
                                <p class="cart-item__size">300ml</p>
                                <p class="cart-item__price">¥ 2,500</p>
                                <div class="cart-item__quantity-controls">
                                    <button class="quantity-minus" data-id="product-2">-</button>
                                    <input type="number" class="quantity-input" value="1" min="1" data-id="product-2">
                                    <button class="quantity-plus" data-id="product-2">+</button>
                                </div>
                            </div>
                            <button class="cart-item__remove" data-id="product-2">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </div>
                    </div>

                    <div class="cart-summary">
                        <p class="cart-summary__shipping-info">税込 5,200 円 以上 購入 で 送料 無料 に なり ます。</p>
                        <div class="cart-summary__subtotal">
                            <p>小計</p>
                            <p class="subtotal-price" id="total-price">¥ 4,250<span> JPY</span></p>
                        </div>
                        <p class="cart-summary__tax-info">送料と税金はチェックアウト時に計算されます</p>

                        <div class="cart-delivery-options">
                            <div class="delivery-option">
                                <label for="delivery-date">配送希望日</label>
                                <input type="date" id="delivery-date" class="delivery-select">
                            </div>
                            <div class="delivery-option">
                                <label for="delivery-time">配送希望時間</label>
                                <select id="delivery-time" class="delivery-select">
                                    <option value="none">希望しない</option>
                                    <option value="am">午前中</option>
                                    <option value="12-14">12時〜14時</option>
                                    <option value="14-16">14時〜16時</option>
                                    <option value="16-18">16時〜18時</option>
                                    <option value="18-20">18時〜20時</option>
                                    <option value="18-21">18時〜21時</option>
                                    <option value="19-21">19時〜21時</option>
                                </select>
                            </div>
                        </div>

                        <button class="btn-checkout">チェックアウト</button>
                    </div>
                </div>

            </div>
        </section>

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
    <script src="../js/cart.js"></script>
</body>

</html>