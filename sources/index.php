<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OUR BRAND | 伊勢の地酒と和食にこだわった料亭</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@300;400;500;700&family=Zen+Old+Mincho:wght@400;500;700&display=swap"
        rel="stylesheet">

    <link rel="stylesheet" href="https://unpkg.com/swiper@8/swiper-bundle.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="css/top.css">
</head>

<body>
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
    <main>
        <section class="main-visual swiper mySwiperHero">
            <div class="swiper-wrapper">
                <div class="swiper-slide main-visual__item">
                    <img src="img/gingerale.png" alt="雄大な自然">
                </div>
                <div class="swiper-slide main-visual__item">
                    <img src="img/osake.png" alt="お酒と料理">
                </div>
                <div class="swiper-slide main-visual__item">
                    <img src="img/sake.png" alt="グラス">
                </div>
            </div>
            <div class="swiper-pagination"></div>
        </section>

        <section class="products">
            <div class="products__inner">
                <h2 class="section-title">
                    <span class="en">RANKING</span>
                    <span class="ja">( ランキング )</span>
                </h2>
                <div class="swiper mySwiperProducts">
                    <div class="swiper-wrapper">
                        <div class="swiper-slide product-item">
                            <a href="product.html">
                                <div class="product-item__img-wrap">
                                    <img src="img/gingerale.png" alt="商品名１">
                                </div>
                                <h3 class="product-item__name">純米大吟醸 麗し乃雫</h3>
                                <p class="product-item__price">¥ 5,800<span>(税込)</span></p>
                                <p class="product-item__tag">#日本酒 #華やか #ギフト</p>
                            </a>
                        </div>
                        <div class="swiper-slide product-item">
                            <a href="product.html">
                                <div class="product-item__img-wrap">
                                    <img src="img/osake.png" alt="商品名２">
                                </div>
                                <h3 class="product-item__name">果実酒 桃源郷の誘い</h3>
                                <p class="product-item__price">¥ 3,200<span>(税込)</span></p>
                                <p class="product-item__tag">#果実酒 #甘口 #女子会</p>
                            </a>
                        </div>
                        <div class="swiper-slide product-item">
                            <a href="product.html">
                                <div class="product-item__img-wrap">
                                    <img src="img/sake.png" alt="商品名３">
                                </div>
                                <h3 class="product-item__name">スパークリングワイン 煌</h3>
                                <p class="product-item__price">¥ 4,500<span>(税込)</span></p>
                                <p class="product-item__tag">#ワイン #スパークリング #パーティー</p>
                            </a>
                        </div>
                        <div class="swiper-slide product-item">
                            <a href="product.html">
                                <div class="product-item__img-wrap">
                                    <img src="images/product4.jpg" alt="商品名４">
                                </div>
                                <h3 class="product-item__name">焼酎 琥珀の夢</h3>
                                <p class="product-item__price">¥ 3,800<span>(税込)</span></p>
                                <p class="product-item__tag">#焼酎 #ロック #本格派</p>
                            </a>
                        </div>
                        <div class="swiper-slide product-item">
                            <a href="product.html">
                                <div class="product-item__img-wrap">
                                    <img src="images/product5.jpg" alt="商品名５">
                                </div>
                                <h3 class="product-item__name">リキュール 桜日和</h3>
                                <p class="product-item__price">¥ 2,900<span>(税込)</span></p>
                                <p class="product-item__tag">#リキュール #カクテル #映え</p>
                            </a>
                        </div>
                    </div>
                    <div class="swiper-pagination"></div>
                </div>
                <a href="products_list.html?sort=ranking" class="btn-all-products">ランキング一覧を見る</a>
            </div>
        </section>
        <section class="products beginner-friendly">
            <div class="products__inner">
                <h2 class="section-title">
                    <span class="en">FOR BEGINNERS</span>
                    <span class="ja">( 初めての方へおすすめの一杯 )</span>
                </h2>
                <div class="swiper mySwiperBeginners">
                    <div class="swiper-wrapper">
                        <div class="swiper-slide product-item">
                            <a href="product.html">
                                <div class="product-item__img-wrap">
                                    <img src="img/gingerale.png" alt="初心者向け商品１">
                                </div>
                                <h3 class="product-item__name">月桂冠 スパークリング</h3>
                                <p class="product-item__price">¥ 1,500<span>(税込)</span></p>
                                <p class="product-item__tag">#日本酒 #甘口 #飲みやすい</p>
                            </a>
                        </div>
                        <div class="swiper-slide product-item">
                            <a href="product.html">
                                <div class="product-item__img-wrap">
                                    <img src="img/osake.png" alt="初心者向け商品２">
                                </div>
                                <h3 class="product-item__name">白桃とレモングラスの果実酒</h3>
                                <p class="product-item__price">¥ 2,800<span>(税込)</span></p>
                                <p class="product-item__tag">#果実酒 #フルーティー #女性に人気</p>
                            </a>
                        </div>
                        <div class="swiper-slide product-item">
                            <a href="product.html">
                                <div class="product-item__img-wrap">
                                    <img src="img/sake.png" alt="初心者向け商品３">
                                </div>
                                <h3 class="product-item__name">サントリー ほろよい</h3>
                                <p class="product-item__price">¥ 200<span>(税込)</span></p>
                                <p class="product-item__tag">#低アルコール #お手軽 #家飲み</p>
                            </a>
                        </div>
                        <div class="swiper-slide product-item">
                            <a href="product.html">
                                <div class="product-item__img-wrap">
                                    <img src="images/product4.jpg" alt="初心者向け商品４">
                                </div>
                                <h3 class="product-item__name">カルピスサワー</h3>
                                <p class="product-item__price">¥ 350<span>(税込)</span></p>
                                <p class="product-item__tag">#サワー #定番 #甘い</p>
                            </a>
                        </div>
                        <div class="swiper-slide product-item">
                            <a href="product.html">
                                <div class="product-item__img-wrap">
                                    <img src="images/product5.jpg" alt="初心者向け商品５">
                                </div>
                                <h3 class="product-item__name">北海道ワイン おたる 赤 甘口</h3>
                                <p class="product-item__price">¥ 1,800<span>(税込)</span></p>
                                <p class="product-item__tag">#ワイン #甘口 #国産</p>
                            </a>
                        </div>
                    </div>
                    <div class="swiper-pagination"></div>
                </div>
                <a href="products_list.html?tag=初心者向け" class="btn-all-products">初心者向け一覧を見る</a>
            </div>
        </section>
        <section class="categories">
            <div class="categories__inner">
                <h2 class="section-title">
                    <span class="en">CATEGORIES</span>
                    <span class="ja">カテゴリー</span>
                </h2>
                <ul class="category-list">
                    <li class="category-list__item">
                        <a href="products_list.html?category=日本酒" class="category-card">
                            <img src="img/sake.png" alt="日本酒" class="category-card__img">
                            <h3 class="category-card__name">日本酒</h3>
                        </a>
                    </li>
                    <li class="category-list__item">
                        <a href="products_list.html?category=中国酒" class="category-card">
                            <img src="img/国花瓷(こっかじ) 中国酒　斜めを向いて倒れている　中国風のものに囲まれている.png" alt="中国酒" class="category-card__img">
                            <h3 class="category-card__name">中国酒</h3>
                        </a>
                    </li>
                    <li class="category-list__item">
                        <a href="products_list.html?category=梅酒" class="category-card">
                            <img src="img/梅酒原酒_image1.png" alt="梅酒" class="category-card__img">
                            <h3 class="category-card__name">梅酒</h3>
                        </a>
                    </li>
                    <li class="category-list__item">
                        <a href="products_list.html?category=缶チューハイ" class="category-card">
                            <img src="img/chuhai.png" alt="缶チューハイ" class="category-card__img">
                            <h3 class="category-card__name">缶チューハイ</h3>
                        </a>
                    </li>
                    <li class="category-list__item">
                        <a href="products_list.html?category=焼酎" class="category-card">
                            <img src="img/shochu.png" alt="焼酎" class="category-card__img">
                            <h3 class="category-card__name">焼酎</h3>
                        </a>
                    </li>
                    <li class="category-list__item">
                        <a href="products_list.html?category=ウィスキー" class="category-card">
                            <img src="img/whisky.png" alt="ウィスキー" class="category-card__img">
                            <h3 class="category-card__name">ウィスキー</h3>
                        </a>
                    </li>
                    <li class="category-list__item">
                        <a href="products_list.html?category=スピリッツ" class="category-card">
                            <img src="img/ボンベイ・サファイア スピリッツ　大きく表示　斜めをむいて倒れている　レモンに囲まれている.png" alt="スピリッツ" class="category-card__img">
                            <h3 class="category-card__name">スピリッツ</h3>
                        </a>
                    </li>
                    <li class="category-list__item">
                        <a href="products_list.html?category=リキュール" class="category-card">
                            <img src="img/liqueur.png" alt="リキュール" class="category-card__img">
                            <h3 class="category-card__name">リキュール</h3>
                        </a>
                    </li>
                    <li class="category-list__item">
                        <a href="products_list.html?category=ワイン" class="category-card">
                            <img src="img/wine.png" alt="ワイン" class="category-card__img">
                            <h3 class="category-card__name">ワイン</h3>
                        </a>
                    </li>
                    <li class="category-list__item">
                        <a href="products_list.html?category=ビール" class="category-card">
                            <img src="img/beer.png" alt="ビール" class="category-card__img">
                            <h3 class="category-card__name">ビール</h3>
                        </a>
                    </li>
                </ul>
            </div>
            </ul>
            <a href="products_list.html?sort=newest" class="btn-all-products" style="margin-top: 32px;">すべての商品を見る</a>
            </div>
        </section>
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
                    <img src="img/logo.png" alt="OUR BRAND" style="height:32px;">
                </a>
            </div>
            <p class="footer__copyright">© OUR BRAND All Rights Reserved.</p>
        </div>
    </footer>

    <script src="https://unpkg.com/swiper@8/swiper-bundle.min.js"></script>
    <script src="js/script.js"></script>
</body>

</html>