<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>お酒ガイド | OUR BRAND</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@300;400;500;700&family=Zen+Old+Mincho:wght@400;500;700&display=swap"
        rel="stylesheet">

    <link rel="stylesheet" href="https://unpkg.com/swiper@8/swiper-bundle.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="css/guide.css"> 

    <link rel="stylesheet" href="css/top.css">

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
                <a href="index.php">OUR BRAND</a>
            </h1>
            <nav class="header__nav">
                <ul class="nav__list pc-only">
                    <li><a href="products_list.php">商品一覧</a></li>
                    <li><a href="contact.php">お問い合わせ</a></li>
                </ul>
                <div class="header__icons">
                    <a href="wishlist.php" class="header__icon-link">
                        <i class="fas fa-heart"></i>
                    </a>
                    <a href="cart.php" class="header__icon-link">
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
                    <li><a href="products_list.php?category=日本酒">日本酒</a></li>
                    <li><a href="products_list.php?category=中国酒">中国酒</a></li>
                    <li><a href="products_list.php?category=梅酒">梅酒</a></li>
                    <li><a href="products_list.php?category=缶チューハイ">缶チューハイ</a></li>
                    <li><a href="products_list.php?category=焼酎">焼酎</a></li>
                    <li><a href="products_list.php?category=ウィスキー">ウィスキー</a></li>
                    <li><a href="products_list.php?category=スピリッツ">スピリッツ</a></li>
                    <li><a href="products_list.php?category=リキュール">リキュール</a></li>
                    <li><a href="products_list.php?category=ワイン">ワイン</a></li>
                    <li><a href="products_list.php?category=ビール">ビール</a></li>
                </ul>
            </li>
            <li class="sp-menu__category-toggle">
                商品タグ <i class="fas fa-chevron-down category-icon"></i>
                <ul class="sp-menu__sub-list">
                    <li><a href="products_list.php?tag=初心者向け">初心者向け</a></li>
                    <li><a href="products_list.php?tag=甘口">甘口</a></li>
                    <li><a href="products_list.php?tag=辛口">辛口</a></li>
                    <li><a href="products_list.php?tag=度数低め">度数低め</a></li>
                    <li><a href="products_list.php?tag=度数高め">度数高め</a></li>
                </ul>
            </li>
            <li class="sp-menu__item"><a href="posts.php">投稿ページ</a></li>
            <li class="sp-menu__item"><a href="MyPage.php">マイページ</a></li>
        </ul>

        <div class="sp-menu__divider"></div>

        <ul class="sp-menu__list sp-menu__list--bottom">
            <li class="sp-menu__item"><a href="faq.php">よくある質問</a></li>
            <li class="sp-menu__item"><a href="contact.php">お問い合わせ</a></li>
        </ul>
    </nav>    <main>
        <!-- ヒーローセクション -->
        <section class="guide-hero" style="background-image: url('img/sake-guide-hero.jpg');">
            <div class="guide-hero__inner">
                <h2 class="guide-hero__title">はじめての<br>お酒ガイド</h2>
                <p class="guide-hero__subtitle">お酒が苦手な人でも楽しめる！簡単スタートガイド</p>
            </div>
        </section>

        <!-- お酒って何？ -->
        <section class="guide-section intro-section">
            <div class="section-inner">
                <h3 class="section-title">
                    <span class="en">WHAT IS ALCOHOL?</span>
                    <span class="ja">お酒って何？怖くないよ！</span>
                </h3>
                <div class="intro-content" style="background-image: url('img/osake.png');">
                    <div class="intro-text-overlay">
                        <h4>🍶 お酒は「果物」や「お米」から作られる飲み物</h4>
                        <p>お酒は、ぶどうやお米、麦など、身近な食材から作られています。アルコールが入っているので、ちょっと大人の味がします。</p>
                        
                        <h4>🌟 最初は「甘くて飲みやすい」ものから始めよう</h4>
                        <p>お酒が苦手な人でも大丈夫！ジュースみたいに甘いものや、アルコールが少ないものから始めると安心です。</p>
                        
                        <h4>💡 無理は禁物！ゆっくり楽しもう</h4>
                        <p>お酒は楽しく飲むもの。無理して飲まなくても大丈夫です。</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- 初心者におすすめのお酒 -->
        <section class="guide-section beginner-types">
            <div class="section-inner">
                <h3 class="section-title">
                    <span class="en">BEGINNER FRIENDLY</span>
                    <span class="ja">初心者におすすめ！飲みやすいお酒</span>
                </h3>
                <div class="alcohol-types">
                    <div class="type-card" style="background-image: url('img/sake.png');">
                        <div class="type-overlay">
                            <h4>🍹 缶チューハイ・サワー</h4>
                            <div class="type-details">
                                <p class="sweetness">甘さ: ★★★★☆</p>
                                <p class="alcohol">アルコール: 3-5%（弱め）</p>
                                <p class="price">値段: 100-200円</p>
                                <p class="description">レモンや桃、ぶどう味など、ジュースみたいに甘くて飲みやすい！コンビニで買えるのも嬉しい。</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="type-card" style="background-image: url('img/gingerale.png');">
                        <div class="type-overlay">
                            <h4>🍾 スパークリング</h4>
                            <div class="type-details">
                                <p class="sweetness">甘さ: ★★★★☆</p>
                                <p class="alcohol">アルコール: 5-8%（普通）</p>
                                <p class="price">値段: 800-2000円</p>
                                <p class="description">シュワシュワして爽やか！お祝いの時にぴったり。甘いタイプを選べば初心者でも安心。</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="type-card" style="background-image: url('img/osake.png');">
                        <div class="type-overlay">
                            <h4>🍑 梅酒・果実酒</h4>
                            <div class="type-details">
                                <p class="sweetness">甘さ: ★★★★★</p>
                                <p class="alcohol">アルコール: 8-12%（普通）</p>
                                <p class="price">値段: 1000-3000円</p>
                                <p class="description">フルーツの甘みたっぷり！梅酒は特に優しい味で、ロックや水割りで楽しめます。</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- 簡単な飲み方 -->
        <section class="guide-section how-to-drink">
            <div class="section-inner">
                <h3 class="section-title">
                    <span class="en">HOW TO DRINK</span>
                    <span class="ja">家で簡単！おいしい飲み方</span>
                </h3>
                <div class="drink-methods">
                    <div class="method-card" style="background-image: url('img/冷酒サンプル.png');">
                        <div class="method-overlay">
                            <h4>🧊 冷やして飲む</h4>
                            <div class="method-steps">
                                <p class="step-title">準備するもの</p>
                                <ul>
                                    <li>お酒</li>
                                    <li>普通のグラス</li>
                                    <li>氷（あれば）</li>
                                </ul>
                                <p class="step-title">やり方</p>
                                <p>冷蔵庫で冷やしてグラスに注ぐだけ！氷を入れてもOK。夏におすすめ。</p>
                            </div>
                        </div>
                    </div>

                    <div class="method-card" style="background-image: url('img/sake-roomtemp.jpg');">
                        <div class="method-overlay">
                            <h4>🥤 水で割る</h4>
                            <div class="method-steps">
                                <p class="step-title">準備するもの</p>
                                <ul>
                                    <li>お酒</li>
                                    <li>水またはお湯</li>
                                    <li>グラス</li>
                                </ul>
                                <p class="step-title">やり方</p>
                                <p>お酒1：水1の割合で混ぜる。アルコールが薄くなって飲みやすくなります。</p>
                            </div>
                        </div>
                    </div>

                    <div class="method-card" style="background-image: url('img/sake-warm.jpg');">
                        <div class="method-overlay">
                            <h4>🥤 ジュースで割る</h4>
                            <div class="method-steps">
                                <p class="step-title">準備するもの</p>
                                <ul>
                                    <li>お酒</li>
                                    <li>好きなジュース</li>
                                    <li>グラス</li>
                                </ul>
                                <p class="step-title">やり方</p>
                                <p>お酒1：ジュース2の割合で混ぜる。オレンジジュースやりんごジュースがおすすめ！</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- 注意事項 -->
        <section class="guide-section safety-section">
            <div class="section-inner">
                <h3 class="section-title">
                    <span class="en">SAFETY FIRST</span>
                    <span class="ja">安全に楽しむために</span>
                </h3>
                <div class="safety-content" style="background-image: url('img/sake-guide-hero.jpg');">
                    <div class="safety-overlay">
                        <div class="safety-tips">
                            <div class="tip">
                                <h4>🚫 20歳未満は飲酒禁止</h4>
                                <p>法律で決まっています。20歳になってから楽しみましょう。</p>
                            </div>
                            <div class="tip">
                                <h4>🚗 車の運転はNG</h4>
                                <p>お酒を飲んだら絶対に運転しないでください。</p>
                            </div>
                            <div class="tip">
                                <h4>💧 水分補給を忘れずに</h4>
                                <p>お酒と一緒に水も飲むと、次の日が楽になります。</p>
                            </div>
                            <div class="tip">
                                <h4>🍚 食べ物と一緒に</h4>
                                <p>空腹でお酒を飲むと酔いやすくなります。何か食べながら飲みましょう。</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- おすすめ商品 -->
        <section class="products beginner-friendly">
            <div class="products__inner">
                <h2 class="section-title">
                    <span class="en">RECOMMENDED</span>
                    <span class="ja">初心者におすすめの商品</span>
                </h2>
                <div class="swiper mySwiperBeginners">
                    <div class="swiper-wrapper">
                        <div class="swiper-slide product-item">
                            <a href="product.php">
                                <div class="product-item__img-wrap">
                                    <img src="img/gingerale.png" alt="初心者向け商品１">
                                </div>
                                <h3 class="product-item__name">月桂冠 スパークリング</h3>
                                <p class="product-item__price">¥ 1,500<span>(税込)</span></p>
                                <p class="product-item__tag">#初心者OK #甘口 #飲みやすい</p>
                            </a>
                        </div>
                        <div class="swiper-slide product-item">
                            <a href="product.php">
                                <div class="product-item__img-wrap">
                                    <img src="img/osake.png" alt="初心者向け商品２">
                                </div>
                                <h3 class="product-item__name">白桃とレモングラスの果実酒</h3>
                                <p class="product-item__price">¥ 2,800<span>(税込)</span></p>
                                <p class="product-item__tag">#果実酒 #フルーティー #女性に人気</p>
                            </a>
                        </div>
                        <div class="swiper-slide product-item">
                            <a href="product.php">
                                <div class="product-item__img-wrap">
                                    <img src="img/sake.png" alt="初心者向け商品３">
                                </div>
                                <h3 class="product-item__name">サントリー ほろよい</h3>
                                <p class="product-item__price">¥ 200<span>(税込)</span></p>
                                <p class="product-item__tag">#低アルコール #お手軽 #家飲み</p>
                            </a>
                        </div>
                    </div>
                    <div class="swiper-pagination"></div>
                </div>
                <a href="products_list.php" class="btn-all-products">初心者向け一覧を見る</a>
            </div>
        </section>
    </main>

    <footer class="footer">
        <div class="footer__inner">
            <ul class="footer__nav">
                <li>
                    <span class="footer__nav-title">商品一覧</span>
                    <ul class="footer__subnav">
                        <li><a href="products_list.php?category=日本酒">日本酒</a></li>
                        <li><a href="products_list.php?category=中国酒">中国酒</a></li>
                        <li><a href="products_list.php?category=梅酒">梅酒</a></li>
                        <li><a href="products_list.php?category=缶チューハイ">缶チューハイ</a></li>
                        <li><a href="products_list.php?category=焼酎">焼酎</a></li>
                        <li><a href="products_list.php?category=ウィスキー">ウィスキー</a></li>
                        <li><a href="products_list.php?category=スピリッツ">スピリッツ</a></li>
                        <li><a href="products_list.php?category=リキュール">リキュール</a></li>
                        <li><a href="products_list.php?category=ワイン">ワイン</a></li>
                        <li><a href="products_list.php?category=ビール">ビール</a></li>
                    </ul>
                </li>
                <li><a href="faq.php">よくあるご質問／お問合せ</a></li>
                <li><a href="MyPage.php">会員登録・ログイン</a></li>
                <li><a href="history.php">購入履歴</a></li>
                <li><a href="cart.php">買い物かごを見る</a></li>
                <li><a href="privacy.php">プライバシーポリシー</a></li>
                <li><a href="terms.php">利用規約</a></li>
            </ul>
            <div class="footer__logo" style="margin: 24px 0 12px;">
                <a href="index.php">
                    <img src="img/logo.png" alt="OUR BRAND" style="height:32px;">
                </a>
            </div>
            <p class="footer__copyright">© OUR BRAND All Rights Reserved.</p>
        </div>
    </footer>

    <script src="https://unpkg.com/swiper@8/swiper-bundle.min.js"></script>
    <script src="js/script.js"></script>

    <script src="js/guide.js"></script>
</body>

</html>