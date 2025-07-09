// checkout.js

document.addEventListener('DOMContentLoaded', function() {

    // PHPから渡された serverCartData を cartProducts として利用します
    const cartProducts = typeof serverCartData !== 'undefined' ? serverCartData : [];

    const TAX_RATE = 0.10; // 10% の税率
    const FREE_SHIPPING_THRESHOLD = 5200; // 送料無料になる金額のスレッショルド

    // --- 要素の取得 ---
    const orderSummaryProductsContainer = document.getElementById('order-summary-products');
    const orderSummaryProductsPcContainer = document.getElementById('order-summary-products-pc');
    const shippingOptionsContainer = document.getElementById('shipping-options-container');
    const placeOrderButton = document.getElementById('place-order-button');
    const zipCodeInput = document.getElementById('zip-code');
    const prefectureSelect = document.getElementById('prefecture');
    const cityInput = document.getElementById('city');
    const addressInput = document.getElementById('address');
    const billingAddressRadios = document.querySelectorAll('input[name="billing-address-option"]');
    const billingAddressFields = document.querySelector('.billing-address-fields');
    const addressInputFields = [zipCodeInput, prefectureSelect, cityInput, addressInput];

    // --- 関数定義 ---

    /**
     * 注文概要に商品リストをレンダリングする
     */
    function renderOrderSummaryProducts(container) {
        if (!container) return;
        container.innerHTML = ''; 

        if (cartProducts.length === 0) {
            container.innerHTML = '<p>カートに商品がありません。</p>';
            if(placeOrderButton) placeOrderButton.disabled = true;
            return;
        }

        cartProducts.forEach(product => {
            const productItem = document.createElement('div');
            productItem.classList.add('order-product-item');
            productItem.innerHTML = `
                <div class="order-product-item__image">
                    <img src="${product.imageUrl}" alt="${product.name}">
                    <span class="order-product-item__quantity-badge">${product.quantity}</span>
                </div>
                <div class="order-product-item__details">
                    <p class="order-product-item__name">${product.name}</p>
                    <p class="order-product-item__volume">${product.volume || ''}</p>
                </div>
                <div class="order-product-item__price">¥ ${(product.price * product.quantity).toLocaleString()}</div>
            `;
            container.appendChild(productItem);
        });
    }

    /**
     * ★★★ 修正箇所 ★★★
     * 価格の合計を計算し、表示を更新する
     * - 送料無料のロジックを反映
     * - 消費税の計算を修正
     */
    function updatePriceSummary() {
        // 商品の小計（税抜）を計算
        let subtotal = cartProducts.reduce((sum, product) => sum + (product.price * product.quantity), 0);
        
        let shippingCost = 0;
        const selectedShippingMethod = document.querySelector('input[name="shipping-method"]:checked');
        if (selectedShippingMethod) {
            // data-price属性から送料を取得。updateShippingMethodsで0円に更新されている場合がある。
            shippingCost = parseFloat(selectedShippingMethod.dataset.price || 0);
        }

        // 税込合計を計算
        let total = subtotal + shippingCost;
        // 税込合計から消費税額を計算
        let tax = total - (total / (1 + TAX_RATE));

        // --- モバイル版のサマリー要素を更新 ---
        const summaryTotalMobile = document.getElementById('summary-total-mobile');
        const summarySubtotalMobileContent = document.getElementById('summary-subtotal-mobile-content');
        const summaryShippingMobileContent = document.getElementById('summary-shipping-mobile-content');
        const summaryTotalMobileContentFinal = document.getElementById('summary-total-mobile-content-final');
        const summaryTaxInfoMobile = document.getElementById('summary-tax-info');

        const shippingCostText = shippingCost === 0 ? '無料' : `¥ ${shippingCost.toLocaleString()}`;

        if (summaryTotalMobile) summaryTotalMobile.textContent = `¥ ${Math.round(total).toLocaleString()}`;
        if (summarySubtotalMobileContent) summarySubtotalMobileContent.textContent = `¥ ${subtotal.toLocaleString()}`;
        if (summaryShippingMobileContent) {
            summaryShippingMobileContent.textContent = selectedShippingMethod ? shippingCostText : '住所入力待ち';
        }
        if (summaryTotalMobileContentFinal) summaryTotalMobileContentFinal.textContent = `¥ ${Math.round(total).toLocaleString()} JPY`;
        if (summaryTaxInfoMobile) summaryTaxInfoMobile.textContent = `(内、消費税 ¥ ${Math.round(tax).toLocaleString()})`;

        // --- PC版のサマリー要素を更新 ---
        const summarySubtotalPc = document.getElementById('summary-subtotal-pc');
        const summaryShippingPc = document.getElementById('summary-shipping-pc');
        const summaryTotalPc = document.getElementById('summary-total-pc');
        const summaryTaxInfoPc = document.getElementById('summary-tax-info-pc');

        if (summarySubtotalPc) summarySubtotalPc.textContent = `¥ ${subtotal.toLocaleString()}`;
        if (summaryShippingPc) {
             summaryShippingPc.textContent = selectedShippingMethod ? shippingCostText : '住所入力待ち';
        }
        if (summaryTotalPc) summaryTotalPc.textContent = `¥ ${Math.round(total).toLocaleString()} JPY`;
        if (summaryTaxInfoPc) summaryTaxInfoPc.textContent = `(内、消費税 ¥ ${Math.round(tax).toLocaleString()})`;
    }

    /**
     * ★★★ 修正箇所 ★★★
     * 配送方法を表示/更新する
     * - 商品小計に応じて送料無料を適用
     */
    function updateShippingMethods() {
        if (!shippingOptionsContainer) return;
        
        const subtotal = cartProducts.reduce((sum, product) => sum + (product.price * product.quantity), 0);

        // 基本の配送方法
        let shippingMethods = [
            { id: 'standard', name: '通常配送', price: 500, note: '（3-5営業日）' },
            { id: 'express', name: 'お急ぎ便', price: 800, note: '（1-2営業日）' }
        ];

        // 送料無料の条件を満たしているかチェック
        if (subtotal >= FREE_SHIPPING_THRESHOLD) {
            const standardMethod = shippingMethods.find(m => m.id === 'standard');
            if (standardMethod) {
                standardMethod.price = 0; // 通常配送の価格を0円にする
            }
        }

        let html = '';
        shippingMethods.forEach((method, index) => {
            const checked = index === 0 ? 'checked' : '';
            const priceText = method.price === 0 ? '無料' : `¥ ${method.price.toLocaleString()}`;
            // data-price属性に現在の価格を反映させる
            html += `
                <div class="shipping-option">
                    <input type="radio" id="shipping-${method.id}" name="shipping-method" value="${method.id}" data-price="${method.price}" ${checked}>
                    <label for="shipping-${method.id}">
                        <span class="shipping-method-name">${method.name} ${method.note}</span>
                        <span class="shipping-method-price">${priceText}</span>
                    </label>
                </div>
            `;
        });
        shippingOptionsContainer.innerHTML = html;

        // ラジオボタンにイベントリスナーを再設定
        document.querySelectorAll('input[name="shipping-method"]').forEach(radio => {
            radio.addEventListener('change', updatePriceSummary);
        });
    }

    /**
     * モバイル版注文概要の展開/折りたたみ機能
     */
    function setupMobileOrderSummaryToggle() {
        const toggleButton = document.querySelector('.order-summary-toggle');
        const summaryContent = document.querySelector('.order-summary-content');

        if (toggleButton && summaryContent) {
            toggleButton.addEventListener('click', function() {
                summaryContent.classList.toggle('hidden');
                toggleButton.classList.toggle('expanded');
                const icon = toggleButton.querySelector('.toggle-icon');
                if (icon) {
                    icon.classList.toggle('fa-chevron-down');
                    icon.classList.toggle('fa-chevron-up');
                }
            });
        }
    }

    /**
     * 都道府県のプルダウンメニューを生成する
     */
    function populatePrefectures(selectElementId) {
        const selectElement = document.getElementById(selectElementId);
        if (!selectElement || selectElement.options.length > 1) return;

        const prefectures = [
            "北海道", "青森県", "岩手県", "宮城県", "秋田県", "山形県", "福島県",
            "茨城県", "栃木県", "群馬県", "埼玉県", "千葉県", "東京都", "神奈川県",
            "新潟県", "富山県", "石川県", "福井県", "山梨県", "長野県", "岐阜県",
            "静岡県", "愛知県", "三重県", "滋賀県", "京都府", "大阪府", "兵庫県",
            "奈良県", "和歌山県", "鳥取県", "島根県", "岡山県", "広島県", "山口県",
            "徳島県", "香川県", "愛媛県", "高知県", "福岡県", "佐賀県", "長崎県",
            "熊本県", "大分県", "宮崎県", "鹿児島県", "沖縄県"
        ];
        
        prefectures.forEach(pref => {
            const option = document.createElement('option');
            option.value = pref;
            option.textContent = pref;
            selectElement.appendChild(option);
        });
    }

    /**
     * 郵便番号から住所を自動入力する (zipcloud API)
     */
    function fetchAddressByZip(zip, callback) {
        const cleanZip = zip.replace(/-/g, '');
        if (!/^\d{7}$/.test(cleanZip)) {
            callback(null);
            return;
        }
        fetch(`https://zipcloud.ibsnet.co.jp/api/search?zipcode=${cleanZip}`)
            .then(res => res.json())
            .then(data => {
                if (data && data.results && data.results[0]) {
                    callback({
                        prefecture: data.results[0].address1 || '',
                        city: data.results[0].address2 || '',
                        address: data.results[0].address3 || ''
                    });
                } else {
                    callback(null);
                }
            })
            .catch(() => callback(null));
    }
    
    /**
     * 請求先住所フィールドの表示/非表示を切り替える
     */
    function toggleBillingAddressFields() {
        const selectedOption = document.querySelector('input[name="billing-address-option"]:checked');
        if (!selectedOption || !billingAddressFields) return;

        if (selectedOption.value === 'different') {
            billingAddressFields.classList.remove('hidden');
        } else {
            billingAddressFields.classList.add('hidden');
        }
    }

    // --- 初期化処理 & イベントリスナー設定 ---

    renderOrderSummaryProducts(orderSummaryProductsContainer);
    renderOrderSummaryProducts(orderSummaryProductsPcContainer);
    
    setupMobileOrderSummaryToggle();
    populatePrefectures('prefecture');
    populatePrefectures('billing-prefecture');

    addressInputFields.forEach(field => {
        if(field) {
            field.addEventListener('change', () => {
                const allFilled = addressInputFields.every(f => f && f.value.trim() !== '');
                if (allFilled) {
                    updateShippingMethods();
                    updatePriceSummary();
                }
            });
        }
    });

    if (zipCodeInput) {
        zipCodeInput.addEventListener('blur', function() {
            fetchAddressByZip(this.value, (addr) => {
                if(addr) {
                    if(prefectureSelect) prefectureSelect.value = addr.prefecture;
                    if(cityInput) cityInput.value = addr.city;
                    if(addressInput) addressInput.value = addr.address;
                    // 住所自動入力後、配送方法と価格を更新
                    updateShippingMethods();
                    updatePriceSummary();
                }
            });
        });
    }
    
    const billingZipInput = document.getElementById('billing-zip-code');
    if (billingZipInput) {
        billingZipInput.addEventListener('blur', function() {
            fetchAddressByZip(this.value, (addr) => {
                const billingPrefectureSelect = document.getElementById('billing-prefecture');
                const billingCityInput = document.getElementById('billing-city');
                const billingAddressInput = document.getElementById('billing-address');
                if(addr) {
                    if(billingPrefectureSelect) billingPrefectureSelect.value = addr.prefecture;
                    if(billingCityInput) billingCityInput.value = addr.city;
                    if(billingAddressInput) billingAddressInput.value = addr.address;
                }
            });
        });
    }

    if (billingAddressRadios) {
        billingAddressRadios.forEach(radio => {
            radio.addEventListener('change', toggleBillingAddressFields);
        });
        toggleBillingAddressFields();
    }
    
    updatePriceSummary(); // ページ読み込み時に一度価格を計算

    if (placeOrderButton) {
        placeOrderButton.addEventListener('click', function(event) {
            event.preventDefault(); 
            console.log('注文処理に進みます。');
            // window.location.href = 'thanks.php';
        });
    }
});
