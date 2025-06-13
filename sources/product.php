<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>純米大吟醸 麗し乃雫 | OUR BRAND</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@300;400;500;700&family=Zen+Old+Mincho:wght@400;500;700&display=swap"
        rel="stylesheet">

    <link rel="stylesheet" href="https://unpkg.com/swiper@8/swiper-bundle.min.css" />
    <link rel="stylesheet" href="../css/top.css">
    <link rel="stylesheet" href="../css/product.css">
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
        <div class="breadcrumb">
            <div class="breadcrumb__inner common-inner">
                <a href="index.html">HOME</a> &gt; <a href="products.html">商品一覧</a> &gt; 純米大吟醸 麗し乃雫
            </div>
        </div>

        <section class="product-detail-section">
            <div class="product-detail-section__inner common-inner">
                <div class="product-detail-content">
                    <div class="product-gallery">
                        <div class="product-gallery__main">
                            <img src="../img/gingerale.png" alt="純米大吟醸 麗し乃雫">
                        </div>
                        <div class="product-gallery__thumbnails">
                            <img src="../img/gingerale.png" alt="サムネイル1" class="is-active">
                            <img src="../img/osake.png" alt="サムネイル2">
                            <img src="../img/sake.png" alt="サムネイル3">
                            <img src="../img/sake.png" alt="サムネイル4">
                        </div>
                    </div>

                    <div class="product-info">
                        <h2 class="product-info__name">純米大吟醸 麗し乃雫</h2>
                        <p class="product-info__type">日本酒</p>
                        <p class="product-info__catchcopy">伊勢の豊かな水と厳選米が織りなす、<br>芳醇な香りと透明感のある味わい。</p>
                        <p class="product-info__price">¥ 5,800<span>(税込)</span></p>
                        <p class="product-info__tax-note">※送料別途</p>

                        <!-- ボタンをまとめるラッパーで余白を統一 -->
                        <div class="product-info__buttons">
                            <div class="product-quantity-add-to-cart">
                                <div class="product-quantity-controls">
                                    <button class="quantity-minus" data-id="product-detail-qty">-</button>
                                    <input type="number" class="quantity-input" value="1" min="1"
                                        data-id="product-detail-qty">
                                    <button class="quantity-plus" data-id="product-detail-qty">+</button>
                                </div>
                                <button id="add-to-cart-btn" class="btn-to-ec-site">
                                    カートに入れる
                                </button>
                            </div>
                            <div class="product-info__favorite">
                                <button class="btn-favorite">
                                    <i class="far fa-heart"></i> <span class="favorite-text">お気に入りに追加</span>
                                </button>
                            </div>
                        </div>

                        <ul class="product-info__tags">
                            <li>#華やか</li>
                            <li>#ギフト</li>
                            <li>#純米大吟醸</li>
                        </ul>
                    </div>
                </div>

                <div class="product-accordion-item is-closed">
                    <h3 class="product-accordion-item__title product-description__title">商品の特徴<span
                            class="accordion-icon"></span></h3>
                    <div class="product-accordion-item__content">
                        <p>
                            伊勢の清らかな伏流水と、契約農家で大切に育てられた最高級の酒米「山田錦」を贅沢に使用し、手間暇かけて醸し上げました。
                            杜氏の技と情熱が凝縮された一本で、口に含むと華やかな吟醸香が広がり、米の旨味と清涼感が絶妙なバランスで溶け合います。
                            後味はすっきりとキレが良く、心地よい余韻が長く続きます。
                        </p>
                        <p>
                            特別な日の食卓を彩る一本として、また大切な方への贈り物としても最適です。
                            冷酒で、ワイングラスでお楽しみいただくと、その真価がさらに引き出されます。
                            より詳しい情報はECサイトの商品ページにてご確認いただけます。
                        </p>
                    </div>
                </div>

                <div class="product-accordion-item is-closed">
                    <h3 class="product-accordion-item__title product-description__title">おすすめの飲み方<span
                            class="accordion-icon"></span></h3>
                    <div class="product-accordion-item__content">
                        <p>
                            「純米大吟醸 麗し乃雫」を最大限に楽しむためには、冷酒でお召し上がりいただくのがおすすめです。特に、ワイングラスを使用することで、華やかな吟醸香がより一層引き立ちます。
                        </p>
                        <p>
                            食事とのペアリングでは、白身魚の刺身や軽めの和食と相性抜群です。また、チーズやナッツなどの洋風のおつまみともよく合います。特別な日の乾杯酒としても最適です。
                        </p>
                        <p>
                            冷蔵庫でしっかり冷やした後、グラスに注ぎ、ゆっくりと香りを楽しみながら味わってください。温度が少し上がると、米の旨味がさらに引き立つので、温度変化も楽しめます。
                        </p>
                    </div>
                </div>

                <div class="paired-snacks">
                    <h3 class="paired-snacks__title">相性のいいおつまみ</h3>
                    <div class="swiper paired-snacks-swiper">
                        <div class="swiper-wrapper">
                            <div class="swiper-slide">
                                <div class="product-item">
                                    <a href="otsumami.html">
                                        <div class="product-item__img-wrap">
                                            <img src="./img/otsumami1.jpg" alt="伊勢海老せんべい">
                                        </div>
                                        <h3 class="product-item__name">伊勢海老せんべい</h3>
                                        <p class="product-item__price">¥ 1,200<span>(税込)</span></p>
                                        <p class="product-item__tag">#海老 #香ばしい #お土産</p>
                                    </a>
                                </div>
                            </div>
                            <div class="swiper-slide">
                                <div class="product-item">
                                    <a href="otsumami.html">
                                        <div class="product-item__img-wrap">
                                            <img src="./img/otsumami2.jpg" alt="松阪牛しぐれ煮">
                                        </div>
                                        <h3 class="product-item__name">松阪牛しぐれ煮</h3>
                                        <p class="product-item__price">¥ 1,800<span>(税込)</span></p>
                                        <p class="product-item__tag">#牛肉 #濃厚 #ご飯のお供</p>
                                    </a>
                                </div>
                            </div>
                            <div class="swiper-slide">
                                <div class="product-item">
                                    <a href="otsumami.html">
                                        <div class="product-item__img-wrap">
                                            <img src="./img/otsumami3.jpg" alt="あおさ海苔の佃煮">
                                        </div>
                                        <h3 class="product-item__name">あおさ海苔の佃煮</h3>
                                        <p class="product-item__price">¥ 800<span>(税込)</span></p>
                                        <p class="product-item__tag">#海苔 #磯の香り #和食</p>
                                    </a>
                                </div>
                            </div>
                            <div class="swiper-slide">
                                <div class="product-item">
                                    <a href="otsumami.html">
                                        <div class="product-item__img-wrap">
                                            <img src="./img/otsumami4.jpg" alt="干物アソート">
                                        </div>
                                        <h3 class="product-item__name">干物アソート</h3>
                                        <p class="product-item__price">¥ 2,500<span>(税込)</span></p>
                                        <p class="product-item__tag">#海鮮 #シンプル #焼き魚</p>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="swiper-pagination"></div>
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

    <script src="https://unpkg.com/swiper@8/swiper-bundle.min.js"></script>
    <script src="../js/script.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Swiper初期化（既存）
            const pairedSwiper = new Swiper('.paired-snacks-swiper', {
                slidesPerView: 1.2,
                spaceBetween: 20,
                centeredSlides: true,
                pagination: {
                    el: '.swiper-pagination',
                    clickable: true,
                },
                breakpoints: {
                    768: {
                        slidesPerView: 3,
                        spaceBetween: 30,
                        centeredSlides: true,
                    }
                }
            });

            // アコーディオン（既存）
            const accordionItems = document.querySelectorAll('.product-accordion-item');
            accordionItems.forEach(item => {
                const title = item.querySelector('.product-accordion-item__title');
                const content = item.querySelector('.product-accordion-item__content');
                content.style.height = '0';
                content.style.overflow = 'hidden';
                content.style.transition = 'height 0.3s ease-out';
                title.addEventListener('click', () => {
                    item.classList.toggle('is-closed');
                    if (item.classList.contains('is-closed')) {
                        content.style.height = '0';
                    } else {
                        content.style.height = content.scrollHeight + 'px';
                    }
                });
            });

            // 数量コントロールのイベントリスナー設定 (商品詳細ページ用)
            const quantityMinusBtn = document.querySelector('.product-quantity-controls .quantity-minus');
            const quantityPlusBtn = document.querySelector('.product-quantity-controls .quantity-plus');
            const quantityInput = document.querySelector('.product-quantity-controls .quantity-input');

            if (quantityMinusBtn && quantityPlusBtn && quantityInput) {
                quantityMinusBtn.addEventListener('click', function () {
                    let quantity = parseInt(quantityInput.value);
                    if (quantity > 1) {
                        quantityInput.value = quantity - 1;
                    }
                });

                quantityPlusBtn.addEventListener('click', function () {
                    let quantity = parseInt(quantityInput.value);
                    quantityInput.value = quantity + 1;
                });

                quantityInput.addEventListener('change', function () {
                    let quantity = parseInt(this.value);
                    if (isNaN(quantity) || quantity < 1) {
                        this.value = 1;
                    }
                });
            }

            // 「カートに入れる」ボタンのクリックイベント
            const addToCartBtn = document.getElementById('add-to-cart-btn');
            if (addToCartBtn) {
                addToCartBtn.addEventListener('click', function () {
                    const selectedQuantity = parseInt(quantityInput.value);
                    // alert(`「純米大吟醸 麗し乃雫」を${selectedQuantity}個カートに追加しました！`); // ポップアップ削除
                    // 実際にはカートに商品と数量を追加する処理（例: LocalStorageへの保存）を行います。
                    // window.location.href = 'cart.html';
                });
            }
            // ★画像切り替え機能
            const mainImg = document.querySelector('.product-gallery__main img');
            const thumbs = document.querySelectorAll('.product-gallery__thumbnails img');
            thumbs.forEach(thumb => {
                thumb.addEventListener('click', function () {
                    // メイン画像をサムネイル画像に切り替え
                    mainImg.src = this.src;
                    mainImg.alt = this.alt;
                    // サムネイルのis-activeクラス切り替え
                    thumbs.forEach(t => t.classList.remove('is-active'));
                    this.classList.add('is-active');
                });
            });

            // ★修正済み：お気に入りボタンのクリックイベント (alert削除済み)
            const favoriteBtn = document.querySelector('.btn-favorite');
            if (favoriteBtn) {
                favoriteBtn.addEventListener('click', function () {
                    this.classList.toggle('is-favorited');
                    const icon = this.querySelector('i');
                    const favoriteTextSpan = this.querySelector('.favorite-text'); // span要素を取得

                    if (this.classList.contains('is-favorited')) {
                        icon.classList.remove('far'); // far (outline) を削除
                        icon.classList.add('fas'); // fas (solid) を追加
                        if (favoriteTextSpan) { // span要素が見つかった場合
                            favoriteTextSpan.textContent = 'お気に入り済み'; // spanのテキストを更新
                        } else {
                            // 万が一spanが見つからない場合のフォールバック
                            this.innerHTML =
                                '<i class="fas fa-heart"></i> <span class="favorite-text">お気に入り済み</span>';
                        }
                        // alert('お気に入りに登録しました！'); // ポップアップ削除
                    } else {
                        icon.classList.remove('fas');
                        icon.classList.add('far');
                        if (favoriteTextSpan) { // span要素が見つかった場合
                            favoriteTextSpan.textContent = 'お気に入りに追加'; // spanのテキストを更新
                        } else {
                            // 万が一spanが見つからない場合のフォールバック
                            this.innerHTML =
                                '<i class="far fa-heart"></i> <span class="favorite-text">お気に入りに追加</span>';
                        }
                        // alert('お気に入りから削除しました。'); // ポップアップ削除
                    }
                });
            }
        });
    </script>
</body>

</html>