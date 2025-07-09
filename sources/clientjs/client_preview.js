document.addEventListener('DOMContentLoaded', function () {
    const productSelect = document.getElementById('product-select');
    const previewContentArea = document.getElementById('preview-content-area');
    let swiperInstance = null;

    // 最初にクライアントの商品リストを取得してプルダウンを作成
    function populateProductSelect() {
        fetch('../api/client_preview_data.php?list=true')
            .then(response => response.json())
            .then(data => {
                if (data.success && data.products.length > 0) {
                    data.products.forEach(product => {
                        const option = document.createElement('option');
                        option.value = product.product_id;
                        option.textContent = product.product_name;
                        productSelect.appendChild(option);
                    });
                } else if (!data.success) {
                    console.error('Error fetching product list:', data.message);
                }
            })
            .catch(error => console.error('Fetch error:', error));
    }

    // 商品IDに基づいてプレビューをレンダリング
    function fetchAndRenderPreview(productId) {
        if (!productId) {
            previewContentArea.innerHTML = `
                <div class="preview-placeholder-message">
                    <p><i class="fas fa-info-circle"></i> 上のプルダウンから商品を選択してください。</p>
                </div>`;
            return;
        }

        previewContentArea.innerHTML = `<div class="loading-spinner"></div>`; // ローディング表示

        fetch(`../api/client_preview_data.php?id=${productId}`)
            .then(response => response.json())
            .then(data => {
                if (data.success && data.details) {
                    renderProductPreview(data.details);
                } else {
                    previewContentArea.innerHTML = `<div class="preview-error-message"><p>${data.message || 'プレビューの生成に失敗しました。'}</p></div>`;
                }
            })
            .catch(error => {
                console.error('Fetch error:', error);
                previewContentArea.innerHTML = `<div class="preview-error-message"><p>通信エラーが発生しました。</p></div>`;
            });
    }

    // HTMLを生成して表示するメイン関数
    function renderProductPreview(product) {
        const mainImage = product.images.find(img => img.image_type === 'main') || product.images[0];
        const mainImageUrl = mainImage ? `../${mainImage.image_path}` : 'https://placehold.co/600x400?text=No+Image';

        let thumbnailsHtml = '';
        product.images.forEach((img, index) => {
            thumbnailsHtml += `<img src="../${img.image_path}" alt="サムネイル${index + 1}" class="${(mainImage && img.image_id === mainImage.image_id) ? 'is-active' : ''}">`;
        });

        let tagsHtml = '';
        if (product.tags) {
            product.tags.split(', ').forEach(tag => {
                tagsHtml += `<li>#${tag}</li>`;
            });
        }
        
        previewContentArea.innerHTML = `
            <div class="product-detail-section__inner preview-mock-inner">
                <div class="product-detail-content">
                    <div class="product-gallery">
                        <div class="product-gallery__main">
                            <img src="${mainImageUrl}" alt="${product.product_name}">
                        </div>
                        <div class="product-gallery__thumbnails">${thumbnailsHtml}</div>
                    </div>

                    <div class="product-info">
                        <h2 class="product-info__name">${product.product_name}</h2>
                        <p class="product-info__type">${product.category_name || 'カテゴリ未分類'}</p>
                        <p class="product-info__company">${product.company_name || '製造元不明'}</p>
                        <p class="product-info__catchcopy">${product.product_description?.replace(/\n/g, '<br>') || ''}</p>
                        <p class="product-info__price">¥ ${Number(product.product_price).toLocaleString()}<span>(税込)</span></p>
                        <p class="product-info__tax-note">※送料別途</p>

                        <div class="product-info__buttons">
                            <div class="product-quantity-add-to-cart">
                                <div class="product-quantity-controls">
                                    <button class="quantity-minus">-</button>
                                    <input type="number" class="quantity-input" value="1" min="1" readonly>
                                    <button class="quantity-plus">+</button>
                                </div>
                                <button class="btn-to-ec-site">カートに入れる</button>
                            </div>
                            <div class="product-info__favorite">
                                <button class="btn-favorite"><i class="far fa-heart"></i> <span class="favorite-text">お気に入りに追加</span></button>
                            </div>
                        </div>
                        <ul class="product-info__tags">${tagsHtml}</ul>
                    </div>
                </div>

                <div class="product-accordion-item is-closed">
                    <h3 class="product-accordion-item__title product-description__title">商品の特徴<span class="accordion-icon"></span></h3>
                    <div class="product-accordion-item__content"><p>${product.product_discription?.replace(/\n/g, '<br>') || ''}</p></div>
                </div>
                <div class="product-accordion-item is-closed">
                    <h3 class="product-accordion-item__title product-description__title">おすすめの飲み方<span class="accordion-icon"></span></h3>
                    <div class="product-accordion-item__content"><p>${product.product_How?.replace(/\n/g, '<br>') || ''}</p></div>
                </div>
                
                <div class="paired-snacks">
                    <h3 class="paired-snacks__title">相性のいいおつまみ (プレビュー表示)</h3>
                    <div class="swiper paired-snacks-swiper">
                        <div class="swiper-wrapper">
                            <!-- ダミーのおつまみ -->
                            <div class="swiper-slide"><div class="product-item"><a href="#"><div class="product-item__img-wrap"><img src="../img/otsumami1.jpg" alt="おつまみ1"></div><h3 class="product-item__name">プレビュー用おつまみ1</h3></a></div></div>
                            <div class="swiper-slide"><div class="product-item"><a href="#"><div class="product-item__img-wrap"><img src="../img/otsumami2.jpg" alt="おつまみ2"></div><h3 class="product-item__name">プレビュー用おつまみ2</h3></a></div></div>
                            <div class="swiper-slide"><div class="product-item"><a href="#"><div class="product-item__img-wrap"><img src="../img/otsumami3.jpg" alt="おつまみ3"></div><h3 class="product-item__name">プレビュー用おつまみ3</h3></a></div></div>
                        </div>
                        <div class="swiper-pagination"></div>
                    </div>
                </div>
            </div>`;
        
        // HTML挿入後にJSコンポーネントを初期化
        initializeComponents();
    }

    // プレビュー内のJSコンポーネントを初期化する関数
    function initializeComponents() {
        // 画像ギャラリー
        const mainImg = document.querySelector('.product-gallery__main img');
        const thumbsContainer = document.querySelector('.product-gallery__thumbnails');
        if(mainImg && thumbsContainer) {
            thumbsContainer.addEventListener('click', (e) => {
                if (e.target.tagName === 'IMG') {
                    mainImg.src = e.target.src;
                    thumbsContainer.querySelectorAll('img').forEach(t => t.classList.remove('is-active'));
                    e.target.classList.add('is-active');
                }
            });
        }

        // アコーディオン
        document.querySelectorAll('.product-accordion-item').forEach(item => {
            const title = item.querySelector('.product-accordion-item__title');
            const content = item.querySelector('.product-accordion-item__content');
            content.style.height = '0';
            title.addEventListener('click', () => {
                item.classList.toggle('is-closed');
                content.style.height = item.classList.contains('is-closed') ? '0' : `${content.scrollHeight}px`;
            });
        });

        // Swiper
        if (swiperInstance) {
            swiperInstance.destroy(true, true);
        }
        swiperInstance = new Swiper('.paired-snacks-swiper', { slidesPerView: 1.5, spaceBetween: 20, centeredSlides: true, loop: true, pagination: { el: '.swiper-pagination', clickable: true } });
    }

    // --- イベントリスナーの設定 ---
    productSelect.addEventListener('change', function () {
        fetchAndRenderPreview(this.value);
    });

    // --- 初期化処理の実行 ---
    populateProductSelect();
    fetchAndRenderPreview(null); // 初期状態はプレースホルダーを表示
});
