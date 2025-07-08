document.addEventListener('DOMContentLoaded', function() {

    const productList = document.getElementById('product-list');
    
    // PHPから渡された実際のお気に入り商品データを使用
    let favoriteProducts = initialFavoriteProducts;

    // 商品をレンダリングする関数
    function renderProducts(productsToRender) {
        productList.innerHTML = ''; // 一旦リストを空にする
        productList.className = 'product-grid'; // PC/スマホ共通でグリッド表示

        const existingMsg = document.querySelector('.no-favorites-message');
        if (existingMsg) {
            existingMsg.remove();
        }

        if (!productsToRender || productsToRender.length === 0) {
            const msg = document.createElement('p');
            msg.className = 'no-favorites-message';
            msg.textContent = 'お気に入りの商品はありません。';
            // productList自体が空になるので、その親要素の後ろにメッセージを挿入
            if(productList.parentNode) {
                 productList.parentNode.insertBefore(msg, productList.nextSibling);
            }
            return;
        }

        productsToRender.forEach(product => {
            const productCardLink = document.createElement('a');
            productCardLink.href = `product.php?id=${product.id}`;
            productCardLink.classList.add('product-card');
            productCardLink.dataset.productId = product.id;

            const favoriteClass = 'fas fa-heart is-favorite';

            // タグ表示制限のロジックを追加
            const MAX_VISIBLE_TAGS = 4;
            let tagsHtml = '';
            if (product.tags && product.tags.length > MAX_VISIBLE_TAGS) {
                tagsHtml = product.tags.slice(0, MAX_VISIBLE_TAGS).map(tag => `<span class="product-card__tag">${tag}</span>`).join('');
                const remainingCount = product.tags.length - MAX_VISIBLE_TAGS;
                tagsHtml += `<span class="product-card__tag product-card__tag-more" data-product-id="${product.id}">+${remainingCount}</span>`;
            } else if (product.tags) {
                tagsHtml = product.tags.map(tag => `<span class="product-card__tag">${tag}</span>`).join('');
            }

            productCardLink.innerHTML = `
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
            productList.appendChild(productCardLink);
        });
    }

    // ★★★ イベントリスナーを修正 ★★★
    productList.addEventListener('click', function(e) {
        
        // 「+〇」タグのクリック処理
        if (e.target.classList.contains('product-card__tag-more')) {
            e.preventDefault(); // ★重要：リンク遷移をキャンセル
            
            const productId = parseInt(e.target.dataset.productId);
            const product = favoriteProducts.find(p => p.id == productId);
            
            if (product) {
                const tagsContainer = e.target.parentElement;
                const allTagsHtml = product.tags.map(tag => `<span class="product-card__tag">${tag}</span>`).join('');
                tagsContainer.innerHTML = allTagsHtml;
            }
        }
        
        // お気に入り（ハート）アイコンのクリック処理
        else if (e.target.classList.contains('product-card__favorite')) {
            e.preventDefault(); // ★重要：リンク遷移をキャンセル

            const card = e.target.closest('.product-card');
            const productId = parseInt(card.dataset.productId);

            // APIを呼び出してサーバー上のお気に入り情報を削除
            fetch('api/api_toggle_favorite.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    product_id: productId,
                    is_favorited: true // お気に入りページでは常に解除操作
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    card.style.transition = 'opacity 0.3s ease, transform 0.3s ease';
                    card.style.opacity = '0';
                    card.style.transform = 'scale(0.95)';
                    setTimeout(() => {
                        card.remove();
                        favoriteProducts = favoriteProducts.filter(p => p.id !== productId);
                        if (productList.children.length === 0) {
                           renderProducts([]);
                        }
                    }, 300);
                } else {
                    alert(data.message || 'お気に入りの解除に失敗しました。');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('通信エラーが発生しました。');
            });
        }
    });

    // 初期表示
    renderProducts(favoriteProducts);
});
