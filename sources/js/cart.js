document.addEventListener('DOMContentLoaded', function() {
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
        cartItemsContainer.addEventListener('click', function(e) {
            const target = e.target;
            
            // --- 削除ボタンの処理 ---
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

            // --- 数量変更ボタンの処理 ---
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

        // 数量入力欄の直接変更を監視
        cartItemsContainer.addEventListener('change', function(e) {
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


    // API呼び出し：数量更新
    const updateCartItem = (itemId, quantity) => {
        fetch('api/api_cart_manager.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                action: 'update',
                cart_item_id: itemId,
                quantity: quantity
            })
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
    
    // API呼び出し：商品削除
    const deleteCartItem = (itemId, itemElement) => {
        fetch('api/api_cart_manager.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                action: 'delete',
                cart_item_id: itemId
            })
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

    // カスタム確認モーダル表示関数
    function showCustomConfirm(message, callback) {
        // bodyにモーダル用のHTMLがなければ追加
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
        modal.classList.add('is-active'); // ★ is-active クラスを追加して表示

        const newYesBtn = yesBtn.cloneNode(true);
        yesBtn.parentNode.replaceChild(newYesBtn, yesBtn);
        
        const newNoBtn = noBtn.cloneNode(true);
        noBtn.parentNode.replaceChild(newNoBtn, noBtn);

        newYesBtn.addEventListener('click', () => {
            modal.classList.remove('is-active'); // ★ is-active クラスを削除して非表示
            callback();
        });
        newNoBtn.addEventListener('click', () => {
            modal.classList.remove('is-active'); // ★ is-active クラスを削除して非表示
        });
    }

});
