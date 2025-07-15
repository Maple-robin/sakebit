document.addEventListener('DOMContentLoaded', function () {
    const deliveryDateInput = document.getElementById('delivery-date');
    const deliveryTimeSelect = document.getElementById('delivery-time');
    const checkoutBtn = document.querySelector('.btn-checkout');
    
    // --- ▼▼▼【ここから追記】配送日時をチェックアウトリンクに反映させる処理 ▼▼▼ ---
    function updateCheckoutLink() {
        if (!checkoutBtn) return;

        let baseUrl = 'checkout.php';
        const params = new URLSearchParams();

        const deliveryDate = deliveryDateInput.value;
        const deliveryTime = deliveryTimeSelect.value;

        if (deliveryDate) {
            params.append('delivery_date', deliveryDate);
        }
        if (deliveryTime && deliveryTime !== 'none') {
            params.append('delivery_time', deliveryTime);
        }

        const queryString = params.toString();
        checkoutBtn.href = queryString ? `${baseUrl}?${queryString}` : baseUrl;
    }

    if (deliveryDateInput) {
        // 今日の日付を YYYY-MM-DD 形式で取得し、min属性に設定
        const today = new Date();
        today.setDate(today.getDate() + 1); // 最短お届け日を明日に設定する例
        const minDate = today.toISOString().split('T')[0];
        deliveryDateInput.setAttribute('min', minDate);
        
        // イベントリスナーを追加
        deliveryDateInput.addEventListener('change', updateCheckoutLink);
    }
    if (deliveryTimeSelect) {
        deliveryTimeSelect.addEventListener('change', updateCheckoutLink);
    }
    // 初期読み込み時にもリンクを更新
    updateCheckoutLink();
    // --- ▲▲▲【ここまで追記】---

    const cartItemsContainer = document.getElementById('cart-items-container');
    const totalPriceEl = document.getElementById('total-price');

    // 合計金額を更新する関数
    const updateTotalPrice = () => {
        let total = 0;
        document.querySelectorAll('.cart-item').forEach(item => {
            const priceText = item.querySelector('.cart-item__price').textContent;
            const price = parseFloat(priceText.replace(/[^0-9.-]+/g, ""));
            const quantityInput = item.querySelector('.quantity-input');
            const quantity = quantityInput ? parseInt(quantityInput.value) : 0;
            if (!isNaN(price) && !isNaN(quantity)) {
                total += price * quantity;
            }
        });
        if (totalPriceEl) {
            totalPriceEl.innerHTML = `¥ ${total.toLocaleString()}<span> JPY</span>`;
        }
    };

    // カート全体のクリックイベントを監視（イベント委任）
    if (cartItemsContainer) {
        cartItemsContainer.addEventListener('click', function (e) {
            const target = e.target;
            const removeButton = target.closest('.cart-item__remove');
            if (removeButton) {
                const itemDiv = removeButton.closest('.cart-item');
                const cartItemId = removeButton.dataset.id;
                if (cartItemId) {
                    showCustomConfirm('この商品をカートから削除しますか？', () => {
                        deleteCartItem(cartItemId, itemDiv);
                    });
                }
                return;
            }
            const quantityButton = target.closest('.quantity-plus, .quantity-minus');
            if (quantityButton) {
                const itemDiv = quantityButton.closest('.cart-item');
                const input = itemDiv.querySelector('.quantity-input');
                const cartItemId = quantityButton.dataset.id;
                let currentQuantity = parseInt(input.value);
                if (quantityButton.classList.contains('quantity-plus')) {
                    currentQuantity++;
                } else {
                    if (currentQuantity > 1) {
                        currentQuantity--;
                    }
                }
                input.value = currentQuantity;
                updateCartItem(cartItemId, currentQuantity);
            }
        });

        cartItemsContainer.addEventListener('change', function (e) {
            if (e.target.matches('.quantity-input')) {
                const input = e.target;
                const cartItemId = input.dataset.id;
                let newQuantity = parseInt(input.value);
                if (isNaN(newQuantity) || newQuantity < 1) {
                    newQuantity = 1;
                    input.value = 1;
                }
                updateCartItem(cartItemId, newQuantity);
            }
        });
    }

    const updateCartItem = (itemId, quantity) => {
        fetch('api/api_cart_manager.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ action: 'update', cart_item_id: itemId, quantity: quantity })
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    updateTotalPrice();
                } else {
                    alert(data.message || '数量の更新に失敗しました。');
                    location.reload();
                }
            })
            .catch(err => {
                console.error('Error:', err);
                alert('通信エラーが発生しました。');
            });
    };

    const deleteCartItem = (itemId, itemElement) => {
        fetch('api/api_cart_manager.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ action: 'delete', cart_item_id: itemId })
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    itemElement.style.transition = 'opacity 0.3s ease, transform 0.3s ease';
                    itemElement.style.opacity = '0';
                    itemElement.style.transform = 'translateX(-20px)';
                    setTimeout(() => {
                        itemElement.remove();
                        updateTotalPrice();
                        if (cartItemsContainer.children.length === 0) {
                            cartItemsContainer.innerHTML = '<p class="cart-empty-message">カートに商品がありません。</p>';
                        }
                    }, 300);
                } else {
                    alert(data.message || '商品の削除に失敗しました。');
                }
            })
            .catch(err => {
                console.error('Error:', err);
                alert('通信エラーが発生しました。');
            });
    };

    function showCustomConfirm(message, callback) {
        if (!document.getElementById('custom-confirm-modal')) {
            const modalHtml = `
                <div id="custom-confirm-modal" class="custom-modal-overlay">
                    <div class="custom-modal-content">
                        <p id="confirm-message"></p>
                        <div class="custom-modal-buttons">
                            <button id="confirm-yes" class="btn-danger">はい</button>
                            <button id="confirm-no" class="btn-secondary">いいえ</button>
                        </div>
                    </div>
                </div>`;
            document.body.insertAdjacentHTML('beforeend', modalHtml);
        }
        const modal = document.getElementById('custom-confirm-modal');
        const msgEl = document.getElementById('confirm-message');
        const yesBtn = document.getElementById('confirm-yes');
        const noBtn = document.getElementById('confirm-no');
        msgEl.textContent = message;
        modal.classList.add('is-active');
        const newYesBtn = yesBtn.cloneNode(true);
        yesBtn.parentNode.replaceChild(newYesBtn, yesBtn);
        const newNoBtn = noBtn.cloneNode(true);
        noBtn.parentNode.replaceChild(newNoBtn, noBtn);
        newYesBtn.addEventListener('click', () => {
            modal.classList.remove('is-active');
            callback();
        });
        newNoBtn.addEventListener('click', () => {
            modal.classList.remove('is-active');
        });
    }
});
