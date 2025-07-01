document.addEventListener('DOMContentLoaded', function() {
    // --- ランキングページ固有のロジック ---
    const filterButton = document.getElementById('filter-button');
    const sortButton = document.getElementById('sort-button');
    const filterOverlay = document.getElementById('filter-overlay');
    const sortOverlay = document.getElementById('sort-overlay');
    const filterCloseButton = document.getElementById('filter-close-button');
    const sortCloseButton = document.getElementById('sort-close-button');
    const applyFilterButton = document.querySelector('#filter-overlay .apply-filter-button');
    const applySortButton = document.querySelector('#sort-overlay .apply-sort-button');
    const displayGridButton = document.getElementById('display-grid');
    const displayListButton = document.getElementById('display-list');
    const productList = document.getElementById('product-list');
    
    const pageTitleEn = document.querySelector('.page-title .en');
    const pageTitleJa = document.querySelector('.page-title .ja');

    // 商品データ
    let products = [
        {
            id: 1,
            name: '【新発売】ベリーベリー カシス',
            image: 'https://placehold.co/300x200/F08080/000000?text=AleBeer',
            volume: '200ml/720ml',
            price: 1750,
            tags: ['新発売', '甘口', '初心者向け'],
            category: 'カクテル',
            releaseDate: '2024-06-01',
            rankingScore: 150,
            isFavorite: true
        },
        {
            id: 2,
            name: 'オリジナルエールビール',
            image: 'https://placehold.co/300x200/F08080/000000?text=AleBeer',
            volume: '350ml',
            price: 550,
            tags: ['定番', '辛口', '度数高め'],
            category: 'ビール',
            releaseDate: '2022-01-01',
            rankingScore: 120,
            isFavorite: false 
        },
        {
            id: 3,
            name: 'フルーツIPA',
            image: 'https://placehold.co/300x200/87CEFA/000000?text=FruitIPA',
            volume: '350ml',
            price: 600,
            tags: ['限定', '甘口', '初心者向け'],
            category: 'ビール',
            releaseDate: '2024-04-10',
            rankingScore: 110,
            isFavorite: true 
        },
        {
            id: 4,
            name: 'ダークラガー',
            image: 'https://placehold.co/300x200/8B4513/FFFFFF?text=DarkLager',
            volume: '350ml',
            price: 580,
            tags: ['濃厚', '辛口'],
            category: 'ビール',
            releaseDate: '2023-11-20',
            rankingScore: 105,
            isFavorite: false
        },
        {
            id: 5,
            name: 'ホワイトビール',
            image: 'https://placehold.co/300x200/F5DEB3/000000?text=WhiteBeer',
            volume: '330ml',
            price: 520,
            tags: ['爽やか', '初心者向け'],
            category: 'ビール',
            releaseDate: '2023-05-15',
            rankingScore: 98,
            isFavorite: false
        },
        {
            id: 6,
            name: 'ペールエール',
            image: 'https://placehold.co/300x200/FFDAB9/000000?text=PaleAle',
            volume: '350ml',
            price: 570,
            tags: ['ホップ', '苦味'],
            category: 'ビール',
            releaseDate: '2022-09-01',
            rankingScore: 92,
            isFavorite: true
        },
        {
            id: 7,
            name: 'スタウト',
            image: 'https://placehold.co/300x200/36454F/FFFFFF?text=Stout',
            volume: '330ml',
            price: 620,
            tags: ['ロースト', '濃厚'],
            category: 'ビール',
            releaseDate: '2023-02-28',
            rankingScore: 85,
            isFavorite: false
        },
        // ハイボール
        {
            id: 8,
            name: 'オリジナルハイボール',
            image: 'https://placehold.co/300x200/A0522D/FFFFFF?text=Highball',
            volume: '500ml',
            price: 450,
            tags: ['定番', '爽快'],
            category: 'ハイボール',
            releaseDate: '2022-03-01',
            rankingScore: 80,
            isFavorite: false
        },
        // カクテル
        {
            id: 9,
            name: 'モヒート',
            image: 'https://placehold.co/300x200/6A5ACD/FFFFFF?text=Cocktail',
            volume: '300ml',
            price: 700,
            tags: ['限定', '甘口', '初心者向け'],
            category: 'カクテル',
            releaseDate: '2024-05-01',
            rankingScore: 95,
            isFavorite: true
        },
        // ワイン
        {
            id: 10,
            name: '赤ワイン ボルドー',
            image: 'https://placehold.co/300x200/8B0000/FFFFFF?text=Wine',
            volume: '750ml',
            price: 2500,
            tags: ['濃厚', '辛口'],
            category: 'ワイン',
            releaseDate: '2021-10-01',
            rankingScore: 130,
            isFavorite: false
        },
        // 日本酒
        {
            id: 11,
            name: '純米大吟醸 〇〇',
            image: 'https://placehold.co/300x200/F0F8FF/000000?text=Sake',
            volume: '720ml',
            price: 3000,
            tags: ['華やか', 'ギフト'],
            category: '日本酒',
            releaseDate: '2023-09-01',
            rankingScore: 115,
            isFavorite: false
        },
        // ウイスキー
        {
            id: 12,
            name: 'シングルモルト 〇〇aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa',
            image: '../img/berry.png',
            volume: '700ml',
            price: 4000,
            tags: ['本格派', '熟成'],
            category: 'ウイスキー',
            releaseDate: '2020-07-01',
            rankingScore: 140,
            isFavorite: false
        }
    ];

    // URLパラメータを解析する関数
    function getQueryParams() {
        const params = {};
        window.location.search.substring(1).split('&').forEach(param => {
            const parts = param.split('=');
            if (parts.length === 2) {
                params[decodeURIComponent(parts[0])] = decodeURIComponent(parts[1]);
            }
        });
        return params;
    }

    let currentFilters = {
        categories: ['すべて'], // デフォルトは「すべて」
        tags: []
    };
    let currentSortOrder = 'ranking'; // デフォルトの並び順
    let currentDisplayMode = 'list'; // デフォルトをリスト表示に変更

    // URLパラメータを読み込み、初期フィルター/ソートを設定
    const queryParams = getQueryParams();

    if (queryParams.category) {
        currentFilters.categories = [queryParams.category];
        currentFilters.tags = []; // カテゴリが指定されたらタグはリセット
    } else if (queryParams.tag) {
        currentFilters.tags = [queryParams.tag];
        currentFilters.categories = ['すべて']; // タグが指定されたらカテゴリは「すべて」
    }

    if (queryParams.sort) {
        currentSortOrder = queryParams.sort;
    }

    // 商品をレンダリングする関数
    function renderProducts(productsToRender, displayMode) {
        productList.innerHTML = '';
        productList.className = ''; // クラスをリセット
        
        // PCでは常にグリッド、スマホでは選択されたモードに
        if (window.innerWidth > 767) { // PCのブレークポイントを768pxから767pxに変更
            productList.classList.add('product-grid');
            productList.classList.remove('product-list');
        } else {
            productList.classList.add(`product-${displayMode}`);
        }

        productsToRender.forEach(product => {
            const productCard = document.createElement('div');
            productCard.classList.add('product-card');

            // スマホのリスト表示時にのみ 'product-list-item' クラスを追加
            if (displayMode === 'list' && window.innerWidth <= 767) { // 767px以下でリスト表示の場合
                productCard.classList.add('product-list-item');
            }

            // ハートアイコンのクラスを動的に設定
            const favoriteClass = product.isFavorite ? 'fas fa-heart is-favorite' : 'far fa-heart';

            productCard.innerHTML = `
                <img src="${product.image}" alt="${product.name}" class="product-card__image">
                <i class="${favoriteClass} product-card__favorite" data-product-id="${product.id}"></i>
                <div class="product-card__details">
                    <h3 class="product-card__title">${product.name}</h3>
                    <p class="product-card__volume">${product.volume}</p>
                    <p class="product-card__price">¥ ${product.price.toLocaleString()} <span>~ 【税込】</span></p>
                    <span class="product-card__tag">${product.tags[0] || ''}</span>
                </div>
            `;
            productList.appendChild(productCard);
        });

        // ハートアイコンのクリックイベントリスナーを設定
        document.querySelectorAll('.product-card__favorite').forEach(heartIcon => {
            heartIcon.addEventListener('click', function() {
                this.classList.toggle('far');
                this.classList.toggle('fas');
                this.classList.toggle('is-favorite');
                
                const productId = parseInt(this.dataset.productId);
                const product = products.find(p => p.id === productId);
                if (product) {
                    product.isFavorite = !product.isFavorite;
                }
            });
        });
    }

    // フィルターとソートを適用する関数
    function applyFiltersAndSort() {
        let filteredProducts = [...products];

        if (currentFilters.categories.length > 0 && !currentFilters.categories.includes('すべて')) {
            filteredProducts = filteredProducts.filter(product =>
                currentFilters.categories.includes(product.category)
            );
        } else if (currentFilters.categories.includes('すべて')) {
            // 「すべて」が選択されている場合は、全てのカテゴリを表示
        } 

        if (currentFilters.tags.length > 0) {
            filteredProducts = filteredProducts.filter(product =>
                product.tags.some(tag => currentFilters.tags.includes(tag))
            );
        }

        if (currentSortOrder === 'newest') {
            filteredProducts.sort((a, b) => new Date(b.releaseDate) - new Date(a.releaseDate));
        } else if (currentSortOrder === 'highest_price') {
            filteredProducts.sort((a, b) => b.price - a.price);
        } else if (currentSortOrder === 'lowest_price') {
            filteredProducts.sort((a, b) => a.price - b.price);
        } else if (currentSortOrder === 'ranking') {
            filteredProducts.sort((a, b) => b.rankingScore - a.rankingScore);
        }

        updatePageTitle();

        // 表示モードを決定
        const modeToRender = (window.innerWidth > 767) ? 'grid' : currentDisplayMode; // PCではグリッド固定、スマホでは現在の選択

        renderProducts(filteredProducts, modeToRender);
    }    // ページタイトルを更新する関数
    function updatePageTitle() {
        let enTitle = "PRODUCTS LIST";
        let jaTitle = "( 商品一覧 )";
        let guideLink = "#"; // デフォルトのリンク先
        let description = ""; // カテゴリ説明文

        // カテゴリが選択されている場合（カテゴリを優先）
        if (currentFilters.categories.length === 1 && currentFilters.categories[0] !== 'すべて') {
            const selectedCategory = currentFilters.categories[0];
            switch (selectedCategory) {                case '日本酒':
                    enTitle = "SAKE LIST";
                    jaTitle = "( 日本酒一覧 )";
                    guideLink = "guide_sake.php";
                    description = "米と水、<ruby>麹<rt>こうじ</rt></ruby>で<ruby>造<rt>つく</rt></ruby>る日本の<ruby>伝統酒<rt>でんとうしゅ</rt></ruby>。<ruby>冷<rt>ひや</rt></ruby>でも<ruby>燗<rt>かん</rt></ruby>でも、<ruby>料理<rt>りょうり</rt></ruby>と<ruby>合<rt>あ</rt></ruby>わせて<ruby>楽<rt>たの</rt></ruby>しめます。";
                    break;
                case 'リキュール':
                    enTitle = "LIQUEUR LIST";
                    jaTitle = "( リキュール一覧 )";
                    guideLink = "guide_liquor.php";
                    description = "<ruby>果実<rt>かじつ</rt></ruby>やハーブが<ruby>香<rt>かお</rt></ruby>るお<ruby>酒<rt>さけ</rt></ruby>。カクテルやデザートに、<ruby>楽<rt>たの</rt></ruby>しみ<ruby>方<rt>かた</rt></ruby>は<ruby>自由自在<rt>じゆうじざい</rt></ruby>です。";
                    break;
                case 'ビール':
                    enTitle = "BEER LIST";
                    jaTitle = "( ビール一覧 )";
                    guideLink = "guide_beer.php";
                    description = "<ruby>麦芽<rt>ばくが</rt></ruby>とホップの<ruby>豊<rt>ゆた</rt></ruby>かな<ruby>香<rt>かお</rt></ruby>り。<ruby>世界中<rt>せかいじゅう</rt></ruby>で<ruby>愛<rt>あい</rt></ruby>される、<ruby>多彩<rt>たさい</rt></ruby>な<ruby>味<rt>あじ</rt></ruby>わいのビールです。";
                    break;
                case 'ワイン':
                    enTitle = "WINE LIST";
                    jaTitle = "( ワイン一覧 )";
                    guideLink = "guide_wine.php";
                    description = "ぶどうから<ruby>造<rt>つく</rt></ruby>られる、<ruby>香<rt>かお</rt></ruby>り<ruby>高<rt>たか</rt></ruby>いお<ruby>酒<rt>さけ</rt></ruby>。<ruby>赤<rt>あか</rt></ruby>・<ruby>白<rt>しろ</rt></ruby>・ロゼ、<ruby>料理<rt>りょうり</rt></ruby>との<ruby>組<rt>く</rt></ruby>み<ruby>合<rt>あ</rt></ruby>わせも<ruby>無限大<rt>むげんだい</rt></ruby>です。";
                    break;
                case '焼酎':
                    enTitle = "SHOCHU LIST";
                    jaTitle = "( 焼酎一覧 )";
                    guideLink = "guide_shochu.php";
                    description = "<ruby>芋<rt>いも</rt></ruby>や<ruby>麦<rt>むぎ</rt></ruby>など、<ruby>原料<rt>げんりょう</rt></ruby>の<ruby>個性<rt>こせい</rt></ruby>が<ruby>光<rt>ひか</rt></ruby>る<ruby>日本<rt>にほん</rt></ruby>の<ruby>蒸留酒<rt>じょうりゅうしゅ</rt></ruby>。<ruby>水割<rt>みずわ</rt></ruby>り、お<ruby>湯割<rt>ゆわ</rt></ruby>り、ロックでどうぞ。";
                    break;
                case 'ウィスキー':
                case 'ウイスキー': // どちらも対応
                    enTitle = "WHISKY LIST";
                    jaTitle = "( ウィスキー一覧 )";
                    guideLink = "guide_whisky.php";
                    description = "<ruby>樽熟成<rt>たるじゅくせい</rt></ruby>が<ruby>生<rt>う</rt></ruby>む、<ruby>深<rt>ふか</rt></ruby>い<ruby>香<rt>かお</rt></ruby>りと<ruby>味<rt>あじ</rt></ruby>わい。ストレートやハイボールでじっくりと。";
                    break;
                case 'スピリッツ':
                    enTitle = "SPIRITS LIST";
                    jaTitle = "( スピリッツ一覧 )";
                    guideLink = "guide_spirits.php";
                    description = "アルコール<ruby>度数<rt>どすう</rt></ruby>の<ruby>高<rt>たか</rt></ruby>い<ruby>蒸留酒<rt>じょうりゅうしゅ</rt></ruby>の<ruby>総称<rt>そうしょう</rt></ruby>。カクテルのベースとして<ruby>豊<rt>ゆた</rt></ruby>かな<ruby>個性<rt>こせい</rt></ruby>が<ruby>光<rt>ひか</rt></ruby>ります。";
                    break;
                case '梅酒':
                    enTitle = "UMESHU LIST";
                    jaTitle = "( 梅酒一覧 )";
                    guideLink = "guide_umeshu.php";
                    description = "<ruby>青梅<rt>あおうめ</rt></ruby>をじっくり<ruby>漬<rt>つ</rt></ruby>け<ruby>込<rt>こ</rt></ruby>んだ、<ruby>甘酸<rt>あまず</rt></ruby>っぱいお<ruby>酒<rt>さけ</rt></ruby>。ロックやソーダ<ruby>割<rt>わ</rt></ruby>りで、<ruby>食前<rt>しょくぜん</rt></ruby><ruby>食後<rt>しょくご</rt></ruby>に。";
                    break;
                case '缶チューハイ':
                    enTitle = "CHUHAI LIST";
                    jaTitle = "( 缶チューハイ一覧 )";
                    guideLink = "guide_chuhai.php";
                    description = "シュワっと<ruby>弾<rt>はじ</rt></ruby>ける、<ruby>手軽<rt>てがる</rt></ruby>な美味しさ。<ruby>豊富<rt>ほうふ</rt></ruby>なフレーバーから、<ruby>今日<rt>きょう</rt></ruby>の<ruby>気分<rt>きぶん</rt></ruby>で<ruby>選<rt>えら</rt></ruby>べます。";
                    break;
                case '中国酒':
                    enTitle = "CHINESE LIQUOR LIST";
                    jaTitle = "( 中国酒一覧 )";
                    guideLink = "guide_chinese_liquor.php";
                    description = "<ruby>高粱<rt>こうりゃん</rt></ruby>などから<ruby>造<rt>つく</rt></ruby>られる、<ruby>中国<rt>ちゅうごく</rt></ruby><ruby>伝統<rt>でんとう</rt></ruby>の<ruby>蒸留酒<rt>じょうりゅうしゅ</rt></ruby>。<ruby>独特<rt>どくとく</rt></ruby>の<ruby>香<rt>かお</rt></ruby>りが、<ruby>中華<rt>ちゅうか</rt></ruby><ruby>料理<rt>りょうり</rt></ruby>の<ruby>味<rt>あじ</rt></ruby>を<ruby>引<rt>ひ</rt></ruby>き<ruby>立<rt>た</rt></ruby>てます。";
                    break;
                default:
                    enTitle = "PRODUCTS LIST";
                    jaTitle = "( 商品一覧 )";
                    guideLink = "#";
                    description = "";
                    break;
            }
        }
        // タグが選択されている場合（カテゴリが選択されていない場合のみ適用）
        else if (currentFilters.tags.length === 1) {
            const selectedTag = currentFilters.tags[0];
            switch (selectedTag) {                case '初心者向け':
                    enTitle = "FOR BEGINNERS";
                    jaTitle = "( 初めての方へおすすめの一杯 )";
                    description = "お<ruby>酒<rt>さけ</rt></ruby><ruby>初心者<rt>しょしんしゃ</rt></ruby>の<ruby>方<rt>かた</rt></ruby>でも<ruby>安心<rt>あんしん</rt></ruby>してお<ruby>楽<rt>たの</rt></ruby>しみいただける<ruby>商品<rt>しょうひん</rt></ruby>を<ruby>厳選<rt>げんせん</rt></ruby>しました。アルコール<ruby>度数<rt>どすう</rt></ruby>が<ruby>低<rt>ひく</rt></ruby>めで<ruby>飲<rt>の</rt></ruby>みやすく、クセの<ruby>少<rt>すく</rt></ruby>ない<ruby>味<rt>あじ</rt></ruby>わいのものを<ruby>中心<rt>ちゅうしん</rt></ruby>にラインナップ。まずはこちらから<ruby>始<rt>はじ</rt></ruby>めて、お<ruby>酒<rt>さけ</rt></ruby>の<ruby>世界<rt>せかい</rt></ruby>を<ruby>広<rt>ひろ</rt></ruby>げていきましょう。";
                    break;
                case '甘口':
                    enTitle = "SWEET SELECTION";
                    jaTitle = "( 甘口一覧 )";
                    description = "<ruby>甘口<rt>あまくち</rt></ruby>のお<ruby>酒<rt>さけ</rt></ruby>は、フルーティーで<ruby>飲<rt>の</rt></ruby>みやすく、デザート<ruby>感覚<rt>かんかく</rt></ruby>で<ruby>楽<rt>たの</rt></ruby>しめます。<ruby>食後酒<rt>しょくごしゅ</rt></ruby>としても<ruby>人気<rt>にんき</rt></ruby>があり、<ruby>甘<rt>あま</rt></ruby>いものがお<ruby>好<rt>す</rt></ruby>きな<ruby>方<rt>かた</rt></ruby>や<ruby>女性<rt>じょせい</rt></ruby>の<rt>かた</rt></ruby>に<ruby>特<rt>とく</rt></ruby>におすすめです。アルコールの<ruby>辛味<rt>からみ</rt></ruby>が<ruby>苦手<rt>にがて</rt></ruby>な<ruby>方<rt>かた</rt></ruby>にもぴったりです。";
                    break;
                case '辛口':
                    enTitle = "DRY SELECTION";
                    jaTitle = "( 辛口一覧 )";
                    description = "<ruby>辛口<rt>からくち</rt></ruby>のお<ruby>酒<rt>さけ</rt></ruby>は、キレが<ruby>良<rt>よ</rt></ruby>く、すっきりとした<ruby>味<rt>あじ</rt></ruby>わいが<ruby>特徴<rt>とくちょう</rt></ruby>です。<ruby>食事<rt>しょくじ</rt></ruby>との<ruby>相性<rt>あいしょう</rt></ruby>が<ruby>良<rt>よ</rt></ruby>く、<ruby>特<rt>とく</rt></ruby>に<ruby>和食<rt>わしょく</rt></ruby>や<ruby>魚料理<rt>さかなりょうり</rt></ruby>によく<ruby>合<rt>あ</rt></ruby>います。アルコール<ruby>本来<rt>ほんらい</rt></ruby>の<ruby>味<rt>あじ</rt></ruby>わいを<ruby>楽<rt>たの</rt></ruby>しみたい<ruby>方<rt>かた</rt></ruby>におすすめです。";
                    break;
                case '度数低め':
                    enTitle = "LOW ALCOHOL";
                    jaTitle = "( 度数低め一覧 )";
                    description = "アルコール<ruby>度数<rt>どすう</rt></ruby>5%<ruby>以下<rt>いか</rt></ruby>の<ruby>商品<rt>しょうひん</rt></ruby>を<ruby>集<rt>あつ</rt></ruby>めました。お<ruby>酒<rt>さけ</rt></ruby>に<ruby>慣<rt>な</rt></ruby>れていない<ruby>方<rt>かた</rt></ruby>や、<ruby>軽<rt>かる</rt></ruby>く<ruby>飲<rt>の</rt></ruby>みたいときにすすめです。アルコール<ruby>感<rt>かん</rt></ruby>が<ruby>少<rt>すく</rt></ruby>なく、ジュース<ruby>感覚<rt>かんかく</rt></ruby>で<ruby>楽<rt>たの</rt></ruby>しめるものも<ruby>多数<rt>たすう</rt></ruby>ご<ruby>用意<rt>ようい</rt></ruby>しています。";
                    break;
                case '度数高め':
                    enTitle = "HIGH ALCOHOL";
                    jaTitle = "( 度数高め一覧 )";
                    description = "アルコール<ruby>度数<rt>どすう</rt></ruby>20%<ruby>以上<rt>いじょう</rt></ruby>の<ruby>本格的<rt>ほんかくてき</rt></ruby>なお<ruby>酒<rt>さけ</rt></ruby>をお<ruby>探<rt>さが</rt></ruby>しの<ruby>方<rt>かた</rt></ruby>に。しっかりとした<ruby>飲<rt>の</rt></ruby>みごたえと<ruby>深<rt>ふか</rt></ruby>い<ruby>味<rt>あじ</rt></ruby>わいを<ruby>楽<rt>たの</rt></ruby>しめます。ストレートやロックで、お<ruby>酒<rt>さけ</rt></ruby><ruby>本来<rt>ほんらい</rt></ruby>の<ruby>味<rt>あじ</rt></ruby>をじっくりと<ruby>味<rt>あじ</rt></ruby>わってください。";
                    break;
                case '新発売':
                    enTitle = "NEW ARRIVALS";
                    jaTitle = "( 新発売商品 )";
                    description = "<ruby>話題<rt>わだい</rt></ruby>の<ruby>新商品<rt>しんしょうひん</rt></ruby>をいち<ruby>早<rt>はや</rt></ruby>くお<ruby>試<rt>ため</rt></ruby>しください。<ruby>限定品<rt>げんていひん</rt></ruby>や<ruby>季節<rt>きせつ</rt></ruby><ruby>商品<rt>しょうひん</rt></ruby>も<ruby>含<rt>ふく</rt></ruby>まれており、<ruby>新<rt>あたら</rt></ruby>しい<ruby>味<rt>あじ</rt></ruby>わいとの<ruby>出会<rt>であ</rt></ruby>いをお<ruby>楽<rt>たの</rt></ruby>しみいただけます。トレンドを<ruby>抑<rt>おさ</rt></ruby>えたい<ruby>方<rt>かた</rt></ruby>におすすめです。";
                    break;
                default:
                    enTitle = `${selectedTag.toUpperCase()} LIST`;
                    jaTitle = `( ${selectedTag}一覧 )`;
                    description = "";
                    break;
            }
        }        // デフォルトのタイトル
        else {
            enTitle = "PRODUCTS LIST";
            jaTitle = "( 商品一覧 )";
            guideLink = "guide.php"; // デフォルトでguide.phpに変更
            description = "";
        }

        pageTitleEn.textContent = enTitle;
        pageTitleJa.textContent = jaTitle;        // 説明文を更新
        const categoryDescription = document.getElementById('category-description');
        const descriptionText = document.getElementById('description-text');
        if (description) {
            descriptionText.innerHTML = description.replace(/。/g, '。<br>'); // 「。」を「。<br>」に置換
            categoryDescription.style.display = 'block';
        } else {
            categoryDescription.style.display = 'none';
        }

        // お酒ガイドボタンのリンクを更新
        const guideButton = document.getElementById('guide-button');
        if (guideButton) {
            guideButton.href = guideLink;

            // リンクが反応しない場合は非表示にする
            if (guideLink === "#") {
                guideButton.style.display = "none";
            } else {
                guideButton.style.display = "inline-block";
            }
        }
    }

    // フィルターボタンクリック時の処理
    filterButton.addEventListener('click', () => {
        // フィルターパネルを開くときに、現在のフィルター状態をチェックボックスに反映
        document.querySelectorAll('#filter-overlay input[name="category"]').forEach(checkbox => {
            checkbox.checked = currentFilters.categories.includes(checkbox.value);
        });
        document.querySelectorAll('#filter-overlay input[name="tag"]').forEach(checkbox => {
            checkbox.checked = currentFilters.tags.includes(checkbox.value);
        });
        filterOverlay.classList.add('is-active');
        document.body.classList.add('no-scroll');
    });

    // カテゴリチェックボックスの変更イベントリスナー
    document.querySelectorAll('#filter-overlay input[name="category"]').forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const allCheckbox = document.querySelector('#filter-overlay input[name="category"][value="すべて"]');
            
            if (this.value === 'すべて') {
                if (this.checked) {
                    document.querySelectorAll('#filter-overlay input[name="category"]').forEach(otherCheckbox => {
                        if (otherCheckbox.value !== 'すべて') {
                            otherCheckbox.checked = false;
                        }
                    });
                }
            } else {
                if (this.checked && allCheckbox && allCheckbox.checked) {
                    allCheckbox.checked = false;
                }
                const checkedCategories = Array.from(document.querySelectorAll('#filter-overlay input[name="category"]:checked'))
                                                     .map(cb => cb.value)
                                                     .filter(value => value !== 'すべて');
                if (checkedCategories.length === 0 && allCheckbox) {
                    allCheckbox.checked = true;
                }
            }
        });
    });

    // ソートボタンクリック時の処理
    sortButton.addEventListener('click', () => {
        // ソートパネルを開くときに、現在のソート状態をラジオボタンに反映
        document.querySelector(`#sort-overlay input[name="sort_order"][value="${currentSortOrder}"]`).checked = true;
        sortOverlay.classList.add('is-active');
        document.body.classList.add('no-scroll');
    });

    // フィルターパネルの閉じるボタン
    filterCloseButton.addEventListener('click', () => {
        filterOverlay.classList.remove('is-active');
        document.body.classList.remove('no-scroll');
    });

    // ソートパネルの閉じるボタン
    sortCloseButton.addEventListener('click', () => {
        sortOverlay.classList.remove('is-active');
        document.body.classList.remove('no-scroll');
    });

    // フィルター適用ボタン
    applyFilterButton.addEventListener('click', () => {
        currentFilters.categories = [];
        currentFilters.tags = [];

        document.querySelectorAll('#filter-overlay input[name="category"]:checked').forEach(checkbox => {
            currentFilters.categories.push(checkbox.value);
        });
        document.querySelectorAll('#filter-overlay input[name="tag"]:checked').forEach(checkbox => {
            currentFilters.tags.push(checkbox.value);
        });

        if (currentFilters.categories.length === 0 || (currentFilters.categories.length > 0 && currentFilters.categories.includes('すべて'))) {
            currentFilters.categories = ['すべて'];
            document.querySelectorAll('#filter-overlay input[name="category"]').forEach(checkbox => {
                if (checkbox.value !== 'すべて') {
                    checkbox.checked = false;
                } else {
                    checkbox.checked = true;
                }
            });
        }
        
        filterOverlay.classList.remove('is-active');
        document.body.classList.remove('no-scroll');
        applyFiltersAndSort();
    });

    // ソート適用ボタン
    applySortButton.addEventListener('click', () => {
        currentSortOrder = document.querySelector('#sort-overlay input[name="sort_order"]:checked').value;
        sortOverlay.classList.remove('is-active');
        document.body.classList.remove('no-scroll');
        applyFiltersAndSort();
    });

    // 表示モードボタン（グリッド）
    displayGridButton.addEventListener('click', () => {
        displayGridButton.classList.add('active');
        displayListButton.classList.remove('active');
        currentDisplayMode = 'grid'; // 表示モードを更新
        applyFiltersAndSort();
    });

    // 表示モードボタン（リスト）
    displayListButton.addEventListener('click', () => {
        displayListButton.classList.add('active');
        displayGridButton.classList.remove('active');
        currentDisplayMode = 'list'; // 表示モードを更新
        applyFiltersAndSort();
    });

    // 初期表示モードを決定
    // PCでは常にグリッドがデフォルトになるため、ボタンのアクティブ状態は初期化時に調整
    if (window.innerWidth > 767) { // PCの場合
        displayGridButton.classList.add('active');
        displayListButton.classList.remove('active');
        currentDisplayMode = 'grid'; // PCなので常にグリッド
    } else { // スマホの場合
        displayListButton.classList.add('active'); // スマホの初期はリストに設定
        displayGridButton.classList.remove('active');
        currentDisplayMode = 'list'; // スマホなのでリスト表示
    }

    // URLパラメータに基づく初期フィルター/ソートのUI同期は変更なし
    if (queryParams.category) {
        const categoryCheckbox = document.querySelector(`#filter-overlay input[name="category"][value="${queryParams.category}"]`);
        if (categoryCheckbox) {
            categoryCheckbox.checked = true;
            const allCategoryCheckbox = document.querySelector('#filter-overlay input[name="category"][value="すべて"]');
            if (allCategoryCheckbox) allCategoryCheckbox.checked = false;
        }
    } else if (queryParams.tag) {
        const tagCheckbox = document.querySelector(`#filter-overlay input[name="tag"][value="${queryParams.tag}"]`);
        if (tagCheckbox) {
            tagCheckbox.checked = true;
        }
    } else {
        const allCategoryCheckbox = document.querySelector('#filter-overlay input[name="category"][value="すべて"]');
        if (allCategoryCheckbox) {
            allCategoryCheckbox.checked = true;
        }
    }

    if (queryParams.sort) {
        const sortRadio = document.querySelector(`#sort-overlay input[name="sort_order"][value="${queryParams.sort}"]`);
        if (sortRadio) {
            sortRadio.checked = true;
        }
    } else {
        const defaultSortRadio = document.querySelector(`#sort-overlay input[name="sort_order"][value="ranking"]`);
        if (defaultSortRadio) {
            defaultSortRadio.checked = true;
        }
    }

    // ウィンドウのリサイズ時に適用されるロジックを追加し、表示モードを調整
    window.addEventListener('resize', () => {
        applyFiltersAndSort();
        // リサイズ時にPC/スマホの切り替えでボタンのアクティブ状態も調整
        if (window.innerWidth > 767) {
            displayGridButton.classList.add('active');
            displayListButton.classList.remove('active');
            currentDisplayMode = 'grid'; // PCになったらグリッドに強制
        } else {
            // スマホの場合、現在のcurrentDisplayModeを維持 (ボタンクリックで切り替わった状態)
            if (currentDisplayMode === 'grid') {
                displayGridButton.classList.add('active');
                displayListButton.classList.remove('active');
            } else {
                displayListButton.classList.add('active');
                displayGridButton.classList.remove('active');
            }
        }
    });

    applyFiltersAndSort(); // 初期表示
});