document.addEventListener('DOMContentLoaded', function() {

    const productList = document.getElementById('product-list');
    
    // PHPから渡されたお気に入りデータ（お酒・おつまみ混合）
    let favoriteItems = initialFavoriteItems;

    // 商品をレンダリングする関数
    function renderProducts(itemsToRender) {
        productList.innerHTML = ''; 
        productList.className = 'product-grid';

        const existingMsg = document.querySelector('.no-favorites-message');
        if (existingMsg) {
            existingMsg.remove();
        }

        if (!itemsToRender || itemsToRender.length === 0) {
            const msg = document.createElement('p');
            msg.className = 'no-favorites-message';
            msg.textContent = 'お気に入りの商品はありません。';
            if(productList.parentNode) {
                 productList.parentNode.insertBefore(msg, productList.nextSibling);
            }
            return;
        }

        itemsToRender.forEach(item => {
            const itemCardLink = document.createElement('a');
            
            // ★★★ 修正点: item.typeに応じてリンク先を切り替える ★★★
            itemCardLink.href = item.type === 'product' 
                ? `product.php?id=${item.id}` 
                : `otsumami.php?id=${item.id}`;

            itemCardLink.classList.add('product-card');
            itemCardLink.dataset.itemId = item.id;
            itemCardLink.dataset.itemType = item.type; // typeをdata属性として保持

            const favoriteClass = 'fas fa-heart is-favorite';

            const MAX_VISIBLE_TAGS = 4;
            let tagsHtml = '';
            if (item.tags && item.tags.length > MAX_VISIBLE_TAGS) {
                tagsHtml = item.tags.slice(0, MAX_VISIBLE_TAGS).map(tag => `<span class="product-card__tag">${tag}</span>`).join('');
                const remainingCount = item.tags.length - MAX_VISIBLE_TAGS;
                tagsHtml += `<span class="product-card__tag product-card__tag-more" data-item-id="${item.id}" data-item-type="${item.type}">+${remainingCount}</span>`;
            } else if (item.tags) {
                tagsHtml = item.tags.map(tag => `<span class="product-card__tag">${tag}</span>`).join('');
            }

            // volumeキーの有無をチェック
            const volumeHtml = item.volume ? `<p class="product-card__volume">${item.volume}</p>` : '';

            itemCardLink.innerHTML = `
                <img src="${item.image}" alt="${item.name}" class="product-card__image">
                <i class="${favoriteClass} product-card__favorite" data-item-id="${item.id}" data-item-type="${item.type}"></i>
                <div class="product-card__details">
                    <h3 class="product-card__title">${item.name}</h3>
                    ${volumeHtml}
                    <p class="product-card__price">¥ ${item.price.toLocaleString()} <span>(税込)</span></p>
                    <div class="product-card__tags-container">
                        ${tagsHtml}
                    </div>
                </div>
            `;
            productList.appendChild(itemCardLink);
        });
    }

    productList.addEventListener('click', function(e) {
        
        if (e.target.classList.contains('product-card__tag-more')) {
            e.preventDefault(); 
            
            const itemId = parseInt(e.target.dataset.itemId);
            const itemType = e.target.dataset.itemType;
            const item = favoriteItems.find(p => p.id == itemId && p.type == itemType);
            
            if (item) {
                const tagsContainer = e.target.parentElement;
                const allTagsHtml = item.tags.map(tag => `<span class="product-card__tag">${tag}</span>`).join('');
                tagsContainer.innerHTML = allTagsHtml;
            }
        }
        
        else if (e.target.classList.contains('product-card__favorite')) {
            e.preventDefault(); 

            const card = e.target.closest('.product-card');
            const itemId = parseInt(card.dataset.itemId);
            const itemType = card.dataset.itemType;

            // ★★★ 修正点: item.typeに応じてAPIのURLと送信データを切り替える ★★★
            const apiUrl = itemType === 'product' 
                ? 'api/api_toggle_favorite.php' 
                : 'api/api_toggle_otumami_favorite.php';
            
            const requestBody = { is_favorited: true }; // お気に入りページでは常に解除操作
            if (itemType === 'product') {
                requestBody.product_id = itemId;
            } else {
                requestBody.otumami_id = itemId;
            }

            fetch(apiUrl, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(requestBody)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    card.style.transition = 'opacity 0.3s ease, transform 0.3s ease';
                    card.style.opacity = '0';
                    card.style.transform = 'scale(0.95)';
                    setTimeout(() => {
                        card.remove();
                        // ローカルのデータからも削除
                        favoriteItems = favoriteItems.filter(p => !(p.id === itemId && p.type === itemType));
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
    renderProducts(favoriteItems);
});
