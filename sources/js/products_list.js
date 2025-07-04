document.addEventListener('DOMContentLoaded', function() {
    // PHPから渡された商品データを取得
    let products = initialProductsData; 

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
    const paginationContainer = document.getElementById('pagination-container');


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
    let currentPage = 1; // 現在のページ番号

    // URLパラメータを読み込み、初期フィルター/ソートを設定
    const queryParams = getQueryParams();

    if (queryParams.category) {
        currentFilters.categories = [queryParams.category];
        currentFilters.tags = []; 
    } else if (queryParams.tag) {
        currentFilters.tags = [queryParams.tag];
        currentFilters.categories = ['すべて']; 
    }

    if (queryParams.sort) {
        currentSortOrder = queryParams.sort;
    }

    // 商品をレンダリングする関数
    function renderProducts(productsToRender, displayMode, totalProducts) {
        productList.innerHTML = '';
        productList.className = ''; 
        
        if (window.innerWidth > 767) { 
            productList.classList.add('product-grid');
            productList.classList.remove('product-list');
        } else {
            productList.classList.add(`product-${displayMode}`);
        }

        if (productsToRender.length === 0) {
            productList.innerHTML = '<p class="no-results">該当する商品が見つかりませんでした。</p>';
        } else {
            productsToRender.forEach(product => {
                // ★★★ ここから修正 ★★★
                // 商品カードを div から a タグに変更し、リンク先を設定
                const productCard = document.createElement('a');
                productCard.href = `product.php?id=${product.id}`;
                // ★★★ ここまで修正 ★★★

                productCard.classList.add('product-card');
                productCard.dataset.productId = product.id;

                if (displayMode === 'list' && window.innerWidth <= 767) { 
                    productCard.classList.add('product-list-item');
                }

                const favoriteClass = product.isFavorite ? 'fas fa-heart is-favorite' : 'far fa-heart';

                const MAX_VISIBLE_TAGS = 4;
                let tagsHtml = '';
                if (product.tags.length > MAX_VISIBLE_TAGS) {
                    tagsHtml = product.tags.slice(0, MAX_VISIBLE_TAGS).map(tag => `<span class="product-card__tag">${tag}</span>`).join('');
                    const remainingCount = product.tags.length - MAX_VISIBLE_TAGS;
                    tagsHtml += `<span class="product-card__tag product-card__tag-more" data-product-id="${product.id}">+${remainingCount}</span>`;
                } else {
                    tagsHtml = product.tags.map(tag => `<span class="product-card__tag">${tag}</span>`).join('');
                }

                productCard.innerHTML = `
                    <img src="${product.image}" alt="${product.name}" class="product-card__image">
                    <i class="${favoriteClass} product-card__favorite" data-product-id="${product.id}"></i>
                    <div class="product-card__details">
                        <h3 class="product-card__title">${product.name}</h3>
                        <p class="product-card__volume">${product.volume}</p>
                        <p class="product-card__price">¥ ${product.price.toLocaleString()} <span>~ 【税込】</span></p>
                        <div class="product-card__tags-container">
                            ${tagsHtml}
                        </div>
                    </div>
                `;
                productList.appendChild(productCard);
            });
        }
        renderPagination(totalProducts);
    }

    // イベントリスナーの修正
    productList.addEventListener('click', function(e) {
        // お気に入り（ハート）アイコンのクリック処理
        if (e.target.classList.contains('product-card__favorite')) {
            // ★★★ ここから修正 ★★★
            e.preventDefault(); // カード全体のリンク遷移をキャンセル
            // ★★★ ここまで修正 ★★★
            e.target.classList.toggle('far');
            e.target.classList.toggle('fas');
            e.target.classList.toggle('is-favorite');
            
            const productId = parseInt(e.target.dataset.productId);
            const product = products.find(p => p.id === productId);
            if (product) {
                product.isFavorite = !product.isFavorite;
            }
        }

        // 「+〇」タグのクリック処理
        if (e.target.classList.contains('product-card__tag-more')) {
            // ★★★ ここから修正 ★★★
            e.preventDefault(); // カード全体のリンク遷移をキャンセル
            // ★★★ ここまで修正 ★★★
            const productId = parseInt(e.target.dataset.productId);
            const product = products.find(p => p.id == productId);
            if (product) {
                const tagsContainer = e.target.parentElement;
                const allTagsHtml = product.tags.map(tag => `<span class="product-card__tag">${tag}</span>`).join('');
                tagsContainer.innerHTML = allTagsHtml;
            }
        }
    });


    function renderPagination(totalProducts) {
        paginationContainer.innerHTML = '';
        const itemsPerPage = getItemsPerPage();
        const totalPages = Math.ceil(totalProducts / itemsPerPage);

        if (totalPages <= 1) return;

        const prevButton = document.createElement('button');
        prevButton.innerHTML = '&laquo;';
        prevButton.classList.add('page-btn');
        prevButton.disabled = currentPage === 1;
        prevButton.addEventListener('click', () => {
            if (currentPage > 1) {
                currentPage--;
                applyFiltersAndSort(false);
            }
        });
        paginationContainer.appendChild(prevButton);

        for (let i = 1; i <= totalPages; i++) {
            const pageButton = document.createElement('button');
            pageButton.textContent = i;
            pageButton.classList.add('page-btn');
            if (i === currentPage) {
                pageButton.classList.add('active');
            }
            pageButton.addEventListener('click', () => {
                currentPage = i;
                applyFiltersAndSort(false);
            });
            paginationContainer.appendChild(pageButton);
        }

        const nextButton = document.createElement('button');
        nextButton.innerHTML = '&raquo;';
        nextButton.classList.add('page-btn');
        nextButton.disabled = currentPage === totalPages;
        nextButton.addEventListener('click', () => {
            if (currentPage < totalPages) {
                currentPage++;
                applyFiltersAndSort(false);
            }
        });
        paginationContainer.appendChild(nextButton);
    }

    function getItemsPerPage() {
        const isGrid = (window.innerWidth > 767) || (currentDisplayMode === 'grid');
        return isGrid ? 20 : 10;
    }

    function applyFiltersAndSort(resetPage = true) {
        if (resetPage) {
            currentPage = 1;
        }

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

        const itemsPerPage = getItemsPerPage();
        const startIndex = (currentPage - 1) * itemsPerPage;
        const endIndex = startIndex + itemsPerPage;
        const paginatedProducts = filteredProducts.slice(startIndex, endIndex);

        const modeToRender = (window.innerWidth > 767) ? 'grid' : currentDisplayMode; 

        renderProducts(paginatedProducts, modeToRender, filteredProducts.length);
    }    
    
    function updatePageTitle() {
        let enTitle = "PRODUCTS LIST";
        let jaTitle = "( 商品一覧 )";
        let guideLink = "#"; 
        let description = ""; 

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
                case 'ウイスキー': 
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
                    description = "アルコール<ruby>度数<rt>どすう</rt></ruby>20%<ruby>以上<rt>いじょう</rt></ruby>の<ruby>本格的<rt>ほんかくてき</rt></ruby>なお<ruby>酒<rt>さけ</rt></ruby>をお<ruby>探<rt>さが</rt></ruby>しの<ruby>方<rt>かた</rt></ruby>に。しっかりとした<ruby>飲<rt>の</rt></ruby>みごたえと<ruby>深<rt>ふか</rt></ruby>い<ruby>味<rt>あじ</rt></ruby>わいを<ruby>楽<rt>たの</rt></ruby>しめます。ストレートやロックで、お<ruby>酒<rt>さけ</rt></ruby><ruby>本来<rt>ほんらい</rt></ruby>の<ruby>味<rt>あじ</rt></ruby>わいを<ruby>楽<rt>たの</rt></ruby>しみたい<ruby>方<rt>かた</rt></ruby>におすすめです。";
                    break;
                case '新発売':
                    enTitle = "NEW ARRIVALS";
                    jaTitle = "( 新発売商品 )";
                    description = "<ruby>話題<rt>わだい</rt></ruby>の<ruby>新商品<rt>しんしょうひん</rt></ruby>をいち<ruby>早<rt>はや</rt></ruby>くお<ruby>試<rt>ため</rt></ruby>しください。<ruby>限定品<rt>げんていひん</rt></ruby>や<ruby>季節<rt>きせつ</rt></ruby><ruby>商品<rt>しょうひん</rt></ruby>も<ruby>含<rt>ふく</rt></ruby>まれており、<ruby>新<rt>あたら</rt></ruby>しい<ruby>味<rt>あじ</rt></ruby>わいとの<ruby>出会<rt>であ</rt></ruby>いをお<ruby>楽<rt>たの</rt></ruby>しみいただけます。トレンドを<ruby>抑<rt>おさ</rt></ruby>えたい<ruby>方<rt>かた</rt></ruby>におすすめです。";
                    break;
                default:
                    enTitle = `TAG: ${selectedTag.toUpperCase()}`;
                    jaTitle = `( ${selectedTag}タグ一覧 )`;
                    description = "";
                    break;
            }
            guideLink = "#"; 
        }        
        else {
            enTitle = "PRODUCTS LIST";
            jaTitle = "( 商品一覧 )";
            guideLink = "guide.php"; 
            description = "";
        }

        pageTitleEn.textContent = enTitle;
        pageTitleJa.textContent = jaTitle;        
        const categoryDescription = document.getElementById('category-description');
        const descriptionText = document.getElementById('description-text');
        if (description) {
            descriptionText.innerHTML = description.replace(/。/g, '。<br>'); 
            categoryDescription.style.display = 'block';
        } else {
            categoryDescription.style.display = 'none';
        }

        const guideButton = document.getElementById('guide-button');
        if (guideButton) {
            guideButton.href = guideLink;

            if (guideLink === "#") {
                guideButton.style.display = "none";
            } else {
                guideButton.style.display = "inline-block";
            }
        }
    }

    filterButton.addEventListener('click', () => {
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
                    document.querySelectorAll('#filter-overlay input[name="tag"]').forEach(tagCheckbox => { // 「すべて」選択時はタグも解除
                        tagCheckbox.checked = false;
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

    // タグチェックボックスの変更イベントリスナー
    document.querySelectorAll('#filter-overlay input[name="tag"]').forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const allCategoryCheckbox = document.querySelector('#filter-overlay input[name="category"][value="すべて"]');
            if (this.checked && allCategoryCheckbox) { // タグが選択されたら「すべて」カテゴリを解除
                allCategoryCheckbox.checked = false;
            }
            const checkedTags = Array.from(document.querySelectorAll('#filter-overlay input[name="tag"]:checked'));
            const checkedCategories = Array.from(document.querySelectorAll('#filter-overlay input[name="category"]:checked'))
                                                     .map(cb => cb.value)
                                                     .filter(value => value !== 'すべて');
            
            if (checkedTags.length === 0 && checkedCategories.length === 0 && allCategoryCheckbox) {
                allCategoryCheckbox.checked = true;
            }
        });
    });

    sortButton.addEventListener('click', () => {
        document.querySelector(`#sort-overlay input[name="sort_order"]:checked`).value;
        sortOverlay.classList.add('is-active');
        document.body.classList.add('no-scroll');
    });

    filterCloseButton.addEventListener('click', () => {
        filterOverlay.classList.remove('is-active');
        document.body.classList.remove('no-scroll');
    });

    sortCloseButton.addEventListener('click', () => {
        sortOverlay.classList.remove('is-active');
        document.body.classList.remove('no-scroll');
    });

    applyFilterButton.addEventListener('click', () => {
        currentFilters.categories = [];
        currentFilters.tags = [];

        document.querySelectorAll('#filter-overlay input[name="category"]:checked').forEach(checkbox => {
            currentFilters.categories.push(checkbox.value);
        });
        document.querySelectorAll('#filter-overlay input[name="tag"]').forEach(checkbox => {
            currentFilters.tags.push(checkbox.value);
        });

        if (currentFilters.categories.length === 0 && currentFilters.tags.length === 0) {
            currentFilters.categories = ['すべて'];
            document.querySelector('#filter-overlay input[name="category"][value="すべて"]').checked = true;
        } else if (currentFilters.categories.length > 0 && currentFilters.categories.includes('すべて')) {
            currentFilters.categories = ['すべて'];
            currentFilters.tags = [];
        }
        
        filterOverlay.classList.remove('is-active');
        document.body.classList.remove('no-scroll');
        applyFiltersAndSort();
    });

    applySortButton.addEventListener('click', () => {
        currentSortOrder = document.querySelector('#sort-overlay input[name="sort_order"]:checked').value;
        sortOverlay.classList.remove('is-active');
        document.body.classList.remove('no-scroll');
        applyFiltersAndSort();
    });

    displayGridButton.addEventListener('click', () => {
        if (currentDisplayMode === 'grid') return;
        const firstVisibleProductId = productList.querySelector('.product-card')?.dataset.productId;

        displayGridButton.classList.add('active');
        displayListButton.classList.remove('active');
        currentDisplayMode = 'grid'; 
        
        updateCurrentPage(firstVisibleProductId);
        applyFiltersAndSort(false);
    });

    displayListButton.addEventListener('click', () => {
        if (currentDisplayMode === 'list') return;
        const firstVisibleProductId = productList.querySelector('.product-card')?.dataset.productId;

        displayListButton.classList.add('active');
        displayGridButton.classList.remove('active');
        currentDisplayMode = 'list'; 

        updateCurrentPage(firstVisibleProductId);
        applyFiltersAndSort(false);
    });

    function updateCurrentPage(productId) {
        if (!productId) return;

        let filteredProducts = [...products];
        if (currentFilters.categories.length > 0 && !currentFilters.categories.includes('すべて')) {
            filteredProducts = filteredProducts.filter(product => currentFilters.categories.includes(product.category));
        }
        if (currentFilters.tags.length > 0) {
            filteredProducts = filteredProducts.filter(product => product.tags.some(tag => currentFilters.tags.includes(tag)));
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

        const productIndex = filteredProducts.findIndex(p => p.id == productId);
        if (productIndex === -1) return;

        const itemsPerPage = getItemsPerPage();
        currentPage = Math.floor(productIndex / itemsPerPage) + 1;
    }


    // 初期表示モードを決定
    if (window.innerWidth > 767) { 
        displayGridButton.classList.add('active');
        displayListButton.classList.remove('active');
        currentDisplayMode = 'grid'; 
    } else { 
        displayListButton.classList.add('active'); 
        displayGridButton.classList.remove('active');
        currentDisplayMode = 'list'; 
    }

    // URLパラメータに基づく初期フィルター/ソートのUI同期
    if (queryParams.category) {
        const categoryCheckbox = document.querySelector(`#filter-overlay input[name="category"][value="${queryParams.category}"]`);
        if (categoryCheckbox) {
            categoryCheckbox.checked = true;
            const allCategoryCheckbox = document.querySelector('#filter-overlay input[name="category"][value="すべて"]');
            if (allCategoryCheckbox) allCategoryCheckbox.checked = false;
        }
    } else if (queryParams.tag) {
        // URLパラメータにタグがある場合、対応するタグのチェックボックスを探してチェックする
        const tagCheckbox = document.querySelector(`#filter-overlay input[name="tag"][value="${queryParams.tag}"]`);
        if (tagCheckbox) {
            tagCheckbox.checked = true;
            // 該当するタグの<details>要素を開く (この時点では<details>はproducts_list.phpのHTMLに存在しません)
            // このロジックは、products_list.phpが動的なタグ表示を行うまで影響しません
            // const detailsElement = tagCheckbox.closest('details');
            // if (detailsElement) {
            //     detailsElement.open = true;
            // }
        }
    } else {
        const allCategoryCheckbox = document.querySelector('#filter-overlay input[name="category"][value="すべて"]');
        if (allCategoryCheckbox) {
            allCategoryCheckbox.checked = true;
        }
    }

    if (queryParams.sort) {
        const sortRadio = document.querySelector(`#sort-overlay input[name="sort_order"]:checked`); // valueでなく直接選択
        if (sortRadio) {
            sortRadio.checked = true;
        }
    } else {
        const defaultSortRadio = document.querySelector(`#sort-overlay input[name="sort_order"][value="ranking"]`);
        if (defaultSortRadio) {
            defaultSortRadio.checked = true;
        }
    }

    window.addEventListener('resize', () => {
        applyFiltersAndSort(false);
        if (window.innerWidth > 767) {
            displayGridButton.classList.add('active');
            displayListButton.classList.remove('active');
            currentDisplayMode = 'grid'; 
        } else {
            if (currentDisplayMode === 'grid') {
                displayGridButton.classList.add('active');
                displayListButton.classList.remove('active');
            } else {
                displayListButton.classList.add('active');
                displayGridButton.classList.remove('active');
            }
        }
        applyFiltersAndSort(false); 
    });

    applyFiltersAndSort(); // 初期表示
});
