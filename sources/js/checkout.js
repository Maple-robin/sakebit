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
    const apartmentInput = document.getElementById('apartment');
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
            if (placeOrderButton) placeOrderButton.disabled = true;
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
     * 価格の合計を計算し、表示を更新する
     */
    function updatePriceSummary() {
        let subtotal = cartProducts.reduce((sum, product) => sum + (product.price * product.quantity), 0);
        let shippingCost = 0;
        const selectedShippingMethod = document.querySelector('input[name="shipping-method"]:checked');
        const deliveryDate = document.getElementById('delivery-date-hidden')?.value;
        const deliveryTime = document.getElementById('delivery-time-hidden')?.value;
        let shippingText = '';
        let shippingMethodText = '';
        if (deliveryDate) {
            // 配送指定時は送料・配送方法を明示
            const today = new Date();
            const target = new Date(deliveryDate);
            const diffDays = Math.ceil((target - today) / (1000 * 60 * 60 * 24));
            let method = '';
            let price = 0;
            if (diffDays <= 2) {
                method = 'お急ぎ便（1-2営業日）';
                price = 800;
            } else if (diffDays >= 3 && diffDays <= 5) {
                method = '通常配送（3-5営業日）';
                price = 500;
            }
            if (subtotal >= 5200) price = 0;
            shippingCost = price;
            shippingText = `${price === 0 ? '無料' : `¥ ${price.toLocaleString()}`}`;
            shippingMethodText = method;
        } else {
            if (selectedShippingMethod) {
                shippingCost = parseFloat(selectedShippingMethod.dataset.price || 0);
                shippingText = shippingCost === 0 ? '無料' : `¥ ${shippingCost.toLocaleString()}`;
                shippingMethodText = selectedShippingMethod.parentElement.querySelector('.shipping-method-name')?.textContent || '';
            } else {
                shippingText = '住所入力待ち';
            }
        }

        let total = subtotal + shippingCost;
        let tax = total - (total / (1 + TAX_RATE));

        // モバイル版
        document.getElementById('summary-total-mobile').textContent = `¥ ${Math.round(total).toLocaleString()}`;
        document.getElementById('summary-subtotal-mobile-content').textContent = `¥ ${subtotal.toLocaleString()}`;
        document.getElementById('summary-shipping-mobile-content').innerHTML = shippingMethodText ? `${shippingText} <span style='font-size:0.9em;'>(${shippingMethodText})</span>` : shippingText;
        document.getElementById('summary-total-mobile-content-final').textContent = `¥ ${Math.round(total).toLocaleString()} JPY`;
        document.getElementById('summary-tax-info').textContent = `(内、消費税 ¥ ${Math.round(tax).toLocaleString()})`;

        // PC版
        document.getElementById('summary-subtotal-pc').textContent = `¥ ${subtotal.toLocaleString()}`;
        document.getElementById('summary-shipping-pc').innerHTML = shippingMethodText ? `${shippingText} <span style='font-size:0.9em;'>(${shippingMethodText})</span>` : shippingText;
        document.getElementById('summary-total-pc').textContent = `¥ ${Math.round(total).toLocaleString()} JPY`;
        document.getElementById('summary-tax-info-pc').textContent = `(内、消費税 ¥ ${Math.round(tax).toLocaleString()})`;
    }

    /**
     * 配送方法を表示/更新する
     */
    function updateShippingMethods() {
        if (!shippingOptionsContainer) return;
        const subtotal = cartProducts.reduce((sum, product) => sum + (product.price * product.quantity), 0);

        // 配送指定日・時間の取得
        const deliveryDate = document.getElementById('delivery-date-hidden')?.value;
        const deliveryTime = document.getElementById('delivery-time-hidden')?.value;
        let shippingMethods = [
            { id: 'standard', name: '通常配送', price: 500, note: '（3-5営業日）' },
            { id: 'express', name: 'お急ぎ便', price: 800, note: '（1-2営業日）' }
        ];

        let autoSelected = false;
        let selectedShipping = null;
        let shippingInfoHtml = '';
        if (deliveryDate) {
            const today = new Date();
            const target = new Date(deliveryDate);
            const diffDays = Math.ceil((target - today) / (1000 * 60 * 60 * 24));
            if (diffDays <= 2) {
                // 1-2営業日
                selectedShipping = { name: 'お急ぎ便', price: 800, note: '（1-2営業日）' };
            } else if (diffDays >= 3 && diffDays <= 5) {
                // 3-5営業日
                selectedShipping = { name: '通常配送', price: 500, note: '（3-5営業日）' };
            }
            if (subtotal >= 5200) {
                selectedShipping.price = 0;
            }
            // 配送日・時間・送料を表示
            shippingInfoHtml = `<div class="shipping-info-confirm">
                <div><strong>配送指定日:</strong> ${deliveryDate}</div>
                ${deliveryTime ? `<div><strong>配送時間:</strong> ${deliveryTime}</div>` : ''}
                <div><strong>配送料:</strong> ${selectedShipping.price === 0 ? '無料' : `¥ ${selectedShipping.price.toLocaleString()}`}</div>
                <div><strong>配送方法:</strong> ${selectedShipping.name} ${selectedShipping.note}</div>
            </div>`;
            shippingOptionsContainer.innerHTML = shippingInfoHtml;
            // 配送方法選択肢は表示しない
            return;
        }

        // 送料無料判定
        if (subtotal >= 5200) {
            shippingMethods = shippingMethods.map(m => ({ ...m, price: 0 }));
        }

        let html = '';
        shippingMethods.forEach((method, index) => {
            const checked = (!autoSelected && index === 0) ? 'checked' : '';
            const priceText = method.price === 0 ? '無料' : `¥ ${method.price.toLocaleString()}`;
            html += `
                <div class="shipping-option">
                    <input type="radio" id="shipping-${method.id}" name="shipping-method" value="${method.id}" data-price="${method.price}" ${checked}>
                    <label for="shipping-${method.id}">
                        <span class="shipping-method-name">${method.name} ${method.note}</span>
                        <span class="shipping-method-price">${priceText}</span>
                    </label>
                </div>`;
        });
        shippingOptionsContainer.innerHTML = html;
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

    // --- イベントリスナー設定 & 初期化 ---

    renderOrderSummaryProducts(orderSummaryProductsContainer);
    renderOrderSummaryProducts(orderSummaryProductsPcContainer);
    setupMobileOrderSummaryToggle();
    
    populatePrefectures('prefecture');
    populatePrefectures('billing-prefecture');

    addressInputFields.forEach(field => {
        if (field) {
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
    
    updatePriceSummary();

    /**
     * 「今すぐ支払う」ボタンのクリックイベント
     */
    if (placeOrderButton) {
        placeOrderButton.addEventListener('click', function(event) {
            event.preventDefault();

            const address_parts = [
                prefectureSelect.value,
                cityInput.value,
                addressInput.value,
                apartmentInput.value
            ];
            const shipping_address = address_parts.filter(p => p && p.trim()).join(' ');

            let subtotal = cartProducts.reduce((sum, product) => sum + (product.price * product.quantity), 0);
            let shippingCost = 0;
            const selectedShippingMethod = document.querySelector('input[name="shipping-method"]:checked');
            if (selectedShippingMethod) {
                shippingCost = parseFloat(selectedShippingMethod.dataset.price || 0);
            }
            const total_amount = subtotal + shippingCost;

            if (!prefectureSelect.value || !cityInput.value.trim() || !addressInput.value.trim()) {
                alert('お届け先の必須項目（都道府県、市区町村、住所）を入力してください。');
                return;
            }
            if (total_amount <= 0) {
                alert('合計金額が正しくありません。');
                return;
            }

            const orderData = {
                shipping_address: shipping_address,
                total_amount: total_amount,
                delivery_date: document.getElementById('delivery-date-hidden')?.value || '',
                delivery_time: document.getElementById('delivery-time-hidden')?.value || ''
            };

            placeOrderButton.disabled = true;
            placeOrderButton.textContent = '処理中...';

            // ★★★★★★★★★★★★★★★★★★★★★★★★★★★★★★★★★
            // ★★★ パス指定を 'api/...' に変更しました ★★★
            // ★★★★★★★★★★★★★★★★★★★★★★★★★★★★★★★★★
            fetch('api/process_order.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify(orderData)
            })
            .then(response => {
                if (!response.ok) {
                    return response.text().then(text => {
                        throw new Error(`サーバーエラー: ${response.status} ${response.statusText}\n${text}`);
                    });
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    window.location.href = 'thanks.php?order_id=' + data.order_id;
                } else {
                    alert('注文処理エラー:\n' + (data.message || '不明なエラーが発生しました。'));
                    placeOrderButton.disabled = false;
                    placeOrderButton.textContent = '今すぐ支払う';
                }
            })
            .catch(error => {
                console.error('Fetch Error:', error);
                alert('通信エラーが発生しました。しばらくしてからもう一度お試しください。\n\n詳細: ' + error.message);
                placeOrderButton.disabled = false;
                placeOrderButton.textContent = '今すぐ支払う';
            });
        });
    }
});
