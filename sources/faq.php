<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>よくある質問 | 伊勢の地酒と和食にこだわった料亭</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@300;400;500;700&family=Zen+Old+Mincho:wght@400;500;700&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="../css/top.css">
    <link rel="stylesheet" href="../css/faq.css">
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
                    <li><a href="products_list.html?tag=度数低め">度数低め</a></li>
                    <li><a href="products_list.html?tag=度数高め">度数高め</a></li>
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
        <section class="faq-hero">
            <div class="faq-hero__content">
                <h2 class="section-title">
                    <span class="en">FAQ</span>
                    <span class="ja">（ よくある質問 ）</span>
                </h2>
            </div>
        </section>

        <section class="faq-section">
            <div class="faq-section__inner">
                <div class="faq-category">
                    <h3 class="faq-category__title">当サイトについて</h3>
                    <div class="faq-item">
                        <h4 class="faq-item__question">
                            <span class="q-mark">Q.</span>
                            <span class="question-text">このサイトはどんな目的で利用できますか？</span>
                            <span class="icon"></span>
                        </h4>
                        <div class="faq-item__answer">
                            <p>A.
                                このサイトは、お酒をこれから楽しみたい初心者の方が、お酒の基本的な知識を学んだり、自分好みのお酒を見つけたり、他の方のおすすめ情報を参考にしたりするための情報サイトです。お酒の販売は行っていません。
                            </p>
                        </div>
                    </div>
                    <div class="faq-item">
                        <h4 class="faq-item__question">
                            <span class="q-mark">Q.</span>
                            <span class="question-text">お酒の知識が全くないのですが、どこから始めればいいですか？</span>
                            <span class="icon"></span>
                        </h4>
                        <div class="faq-item__answer">
                            <p>A. まずは「初心者の手引き」をご覧ください。お酒の種類や選び方、基本的な飲み方など、初めての方に役立つ情報がまとめられています。</p>
                        </div>
                    </div>
                </div>

                <div class="faq-category">
                    <h3 class="faq-category__title">ログイン・会員登録について</h3>
                    <div class="faq-item">
                        <h4 class="faq-item__question">
                            <span class="q-mark">Q.</span>
                            <span class="question-text">ログインすると何ができますか？</span>
                            <span class="icon"></span>
                        </h4>
                        <div class="faq-item__answer">
                            <p>A. ログインすると、お気に入りのお酒を保存したり、過去に投稿した内容を編集・管理したり、他のユーザーをフォローして最新の投稿をチェックしたりできるようになります。</p>
                        </div>
                    </div>
                    <div class="faq-item">
                        <h4 class="faq-item__question">
                            <span class="q-mark">Q.</span>
                            <span class="question-text">新規会員登録はどのように行えばいいですか？</span>
                            <span class="icon"></span>
                        </h4>
                        <div class="faq-item__answer">
                            <p>A. トップページの「新規登録」ボタンから、メールアドレスとパスワードを設定してご登録いただけます。登録完了後、すぐにログインしてサービスをご利用いただけます。</p>
                        </div>
                    </div>
                    <div class="faq-item">
                        <h4 class="faq-item__question">
                            <span class="q-mark">Q.</span>
                            <span class="question-text">登録した情報を変更したい場合はどうすればいいですか？</span>
                            <span class="icon"></span>
                        </h4>
                        <div class="faq-item__answer">
                            <p>A. ログイン後、マイページの「会員情報編集」から、登録情報（メールアドレス、パスワードなど）を変更できます。</p>
                        </div>
                    </div>
                    <div class="faq-item">
                        <h4 class="faq-item__question">
                            <span class="q-mark">Q.</span>
                            <span class="question-text">パスワードを忘れてしまいました。どうすればいいですか？</span>
                            <span class="icon"></span>
                        </h4>
                        <div class="faq-item__answer">
                            <p>A. ログインページの「パスワードを忘れた方」リンクをクリックし、ご登録のメールアドレスを入力してください。パスワード再設定用のURLをお送りします。</p>
                        </div>
                    </div>
                    <div class="faq-item">
                        <h4 class="faq-item__question">
                            <span class="q-mark">Q.</span>
                            <span class="question-text">退会したい場合はどうすればいいですか？</span>
                            <span class="icon"></span>
                        </h4>
                        <div class="faq-item__answer">
                            <p>A. ログイン後、マイページの「退会手続き」からお手続きいただけます。退会すると、保存したお気に入りや投稿履歴などはすべて削除されますのでご注意ください。</p>
                        </div>
                    </div>
                </div>

                <div class="faq-category">
                    <h3 class="faq-category__title">お酒の情報について</h3>
                    <div class="faq-item">
                        <h4 class="faq-item__question">
                            <span class="q-mark">Q.</span>
                            <span class="question-text">どんなお酒を探すことができますか？</span>
                            <span class="icon"></span>
                        </h4>
                        <div class="faq-item__answer">
                            <p>A.
                                検索機能を使って特定のお酒を探したり、おすすめ欄から人気のお酒やテーマに沿ったお酒を見つけたりできます。また、「みんなの投稿」ページでは、他の方がおすすめするお酒の情報も参考にできます。
                            </p>
                        </div>
                    </div>
                    <div class="faq-item">
                        <h4 class="faq-item__question">
                            <span class="q-mark">Q.</span>
                            <span class="question-text">探したお酒のページでは何が見られますか？</span>
                            <span class="icon"></span>
                        </h4>
                        <div class="faq-item__answer">
                            <p>A. 各お酒のページでは、商品の詳しい説明、購入できるECサイトへのリンク、そしてそのお酒に合うおすすめのおつまみ情報をご覧いただけます。</p>
                        </div>
                    </div>
                    <div class="faq-item">
                        <h4 class="faq-item__question">
                            <span class="q-mark">Q.</span>
                            <span class="question-text">このサイトで直接お酒を購入することはできますか？</span>
                            <span class="icon"></span>
                        </h4>
                        <div class="faq-item__answer">
                            <p>A. いいえ、当サイトではお酒の販売は行っておりません。各お酒のページに記載されているECサイトのリンクから、外部のサイトでご購入いただけます。</p>
                        </div>
                    </div>
                    <div class="faq-item">
                        <h4 class="faq-item__question">
                            <span class="q-mark">Q.</span>
                            <span class="question-text">掲載されているお酒の情報は常に最新ですか？</span>
                            <span class="icon"></span>
                        </h4>
                        <div class="faq-item__answer">
                            <p>A. 掲載情報には細心の注意を払っておりますが、商品のリニューアルや価格変更などにより、情報が更新される場合があります。ECサイトへのリンク先で最終的な情報をご確認ください。
                            </p>
                        </div>
                    </div>
                </div>

                <div class="faq-category">
                    <h3 class="faq-category__title">投稿について</h3>
                    <div class="faq-item">
                        <h4 class="faq-item__question">
                            <span class="q-mark">Q.</span>
                            <span class="question-text">お酒のおすすめや感想を投稿したいのですが、どうすればいいですか？</span>
                            <span class="icon"></span>
                        </h4>
                        <div class="faq-item__answer">
                            <p>A. 「みんなの投稿」ページから、あなたのおすすめのお酒や、お酒と料理の素晴らしい組み合わせなどを自由に投稿できます。</p>
                        </div>
                    </div>
                    <div class="faq-item">
                        <h4 class="faq-item__question">
                            <span class="q-mark">Q.</span>
                            <span class="question-text">投稿する際に注意することはありますか？</span>
                            <span class="icon"></span>
                        </h4>
                        <div class="faq-item__answer">
                            <p>A. 投稿内容は、他の方が楽しく利用できるよう、公序良俗に反しないもの、誹謗中傷を含まないものをお願いします。具体的な注意点は「投稿ガイドライン」をご確認ください。</p>
                        </div>
                    </div>
                </div>

                <div class="faq-category">
                    <h3 class="faq-category__title">その他</h3>
                    <div class="faq-item">
                        <h4 class="faq-item__question">
                            <span class="q-mark">Q.</span>
                            <span class="question-text">お酒に関する質問や、サイトへの要望がある場合はどうすればいいですか？</span>
                            <span class="icon"></span>
                        </h4>
                        <div class="faq-item__answer">
                            <p>A. サイト下部にある「お問い合わせ」からご連絡ください。皆様のご意見やご要望を参考に、より良いサイト運営を目指してまいります。</p>
                        </div>
                    </div>
                </div>

                <div class="faq-contact-info">
                    <p>上記以外にご不明な点がございましたら、お気軽にお問い合わせください。</p>
                    <a href="contact.html" class="btn-contact">お問い合わせはこちら</a>
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

    <script src="https://unpkg.com/swiper@8/swiper-bundle.min.js"></script>
    <script src="../js/script.js"></script>
    <script src="../js/faq.js"></script>
</body>

</html>