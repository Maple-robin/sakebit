// DOMContentLoadedは、HTMLの読み込みが完了したときに実行されます。
document.addEventListener('DOMContentLoaded', function() {
    // PHPから渡された投稿データと現在のユーザーIDを使用
    // postsData: [{id, userIcon, userName, title, content, images, likes, hearts, isLiked, isHearted}, ...]
    // currentUserId: 現在ログインしているユーザーのID (nullの場合もあり)

    // ▼▼▼ ここから追加 ▼▼▼
    // 画像拡大モーダルの要素を取得
    const imageModal = document.getElementById('image-viewer-modal');
    const modalImage = document.getElementById('modal-image');
    const closeViewerBtn = document.querySelector('.close-viewer-btn');

    // モーダルを閉じる関数
    function closeImageModal() {
        if (imageModal) {
            imageModal.style.display = 'none';
            document.body.style.overflow = 'auto'; // 背景のスクロールを再度有効に
        }
    }

    // 閉じるボタンのイベントリスナー
    if (closeViewerBtn) {
        closeViewerBtn.addEventListener('click', closeImageModal);
    }
    // 背景クリックでモーダルを閉じる
    if (imageModal) {
        imageModal.addEventListener('click', function(event) {
            if (event.target === imageModal) {
                closeImageModal();
            }
        });
    }
    // ▲▲▲ ここまで追加 ▲▲▲


    // カスタムメッセージボックスを表示する関数
    function displayMessage(message, type = 'info') {
        const messageBox = document.createElement('div');
        messageBox.classList.add('custom-message-box');
        if (type === 'success') {
            messageBox.classList.add('success');
        } else if (type === 'error') {
            messageBox.classList.add('error');
        }
        messageBox.textContent = message;

        const existingMessageBox = document.querySelector('.custom-message-box');
        if (existingMessageBox) {
            existingMessageBox.remove();
        }

        document.body.appendChild(messageBox);

        setTimeout(() => {
            messageBox.remove();
        }, 3000); // 3秒後に消える
    }

    // 投稿カードを生成して表示する関数
    function renderPosts() {
        const postsContainer = document.getElementById('posts-container');
        if (!postsContainer) {
            console.error('Error: posts-container element not found.');
            return;
        }
        
        postsContainer.innerHTML = ''; // 既存の投稿をクリア

        if (postsData && postsData.length > 0) {
            postsData.forEach(post => {
                let imagesHtml = '';
                const imgs = post.images || [];
                
                // ▼▼▼ ここから修正 ▼▼▼
                // 各imgタグに post-image-thumbnail クラスを追加
                if (imgs.length === 1) {
                    imagesHtml = `<div class="post-images one"><img class="post-image-thumbnail" src="${post.images[0]}" alt="投稿画像" onerror="this.onerror=null;this.src='https://placehold.co/600x320/87CEFA/000000?text=No+Image';"></div>`;
                } else if (imgs.length === 2) {
                    imagesHtml = `
                        <div class="post-images two">
                            <img class="post-image-thumbnail" src="${post.images[0]}" alt="投稿画像" onerror="this.onerror=null;this.src='https://placehold.co/300x200/F08080/000000?text=No+Image';">
                            <img class="post-image-thumbnail" src="${post.images[1]}" alt="投稿画像" onerror="this.onerror=null;this.src='https://placehold.co/300x200/FFDAB9/000000?text=No+Image';">
                        </div>`;
                } else if (imgs.length === 3) {
                    imagesHtml = `
                        <div class="post-images three">
                            <div><img class="post-image-thumbnail" src="${post.images[0]}" alt="投稿画像" onerror="this.onerror=null;this.src='https://placehold.co/300x200/8B4513/FFFFFF?text=No+Image';"></div>
                            <div>
                                <img class="post-image-thumbnail" src="${post.images[1]}" alt="投稿画像" onerror="this.onerror=null;this.src='https://placehold.co/150x98/6A5ACD/FFFFFF?text=No+Image';">
                                <img class="post-image-thumbnail" src="${post.images[2]}" alt="投稿画像" onerror="this.onerror=null;this.src='https://placehold.co/150x98/F5DEB3/000000?text=No+Image';">
                            </div>
                        </div>`;
                } else if (imgs.length >= 4) {
                    imagesHtml = `
                        <div class="post-images four">
                            <img class="post-image-thumbnail" src="${post.images[0]}" alt="投稿画像" onerror="this.onerror=null;this.src='https://placehold.co/150x98/8B0000/FFFFFF?text=No+Image';">
                            <img class="post-image-thumbnail" src="${post.images[1]}" alt="投稿画像" onerror="this.onerror=null;this.src='https://placehold.co/150x98/87CEFA/000000?text=No+Image';">
                            <img class="post-image-thumbnail" src="${post.images[2]}" alt="投稿画像" onerror="this.onerror=null;this.src='https://placehold.co/150x98/FFDAB9/000000?text=No+Image';">
                            <img class="post-image-thumbnail" src="${post.images[3]}" alt="投稿画像" onerror="this.onerror=null;this.src='https://placehold.co/150x98/F08080/000000?text=No+Image';">
                        </div>`;
                }
                // ▲▲▲ ここまで修正 ▲▲▲

                const goodActiveClass = post.isLiked ? ' active' : '';
                const heartActiveClass = post.isHearted ? ' active' : '';

                const postCardHtml = `
                    <div class="post-card" id="post-${post.id}">
                        <div class="post-header">
                            <img src="${post.userIcon}" alt="${post.userName}のアイコン" class="post-user-icon">
                            <div class="post-info">
                                <span class="post-user-name">${post.userName}</span>
                                <h4 class="post-title">${post.title}</h4>
                            </div>
                            <button class="menu-button" data-post-id="${post.id}">⋮</button>
                            <div class="menu-dropdown">
                                <ul>
                                    <li><a href="#" class="report-action" data-post-id="${post.id}">通報する</a></li>
                                    <li><a href="#">シェア</a></li>
                                </ul>
                            </div>
                        </div>
                        <div class="post-content">${post.content}</div>
                        ${imagesHtml}
                        <div class="post-actions">
                            <button class="reaction-button good${goodActiveClass}" data-reaction="good" data-post-id="${post.id}">
                                👍 <span class="like-count">${post.likes}</span>
                            </button>
                            <button class="reaction-button heart${heartActiveClass}" data-reaction="heart" data-post-id="${post.id}">
                                ❤️ <span class="heart-count">${post.hearts}</span>
                            </button>
                        </div>
                    </div>
                `;
                postsContainer.insertAdjacentHTML('beforeend', postCardHtml);
            });
            attachEventListeners();
        } else {
            postsContainer.innerHTML = '<p style="text-align: center; margin-top: 50px; font-size: 1.8rem; color: #555;">まだ投稿がありません。</p>';
        }
    }

    function attachEventListeners() {
        // ▼▼▼ ここから追加 ▼▼▼
        // 画像クリックでモーダルを開く
        document.querySelectorAll('.post-image-thumbnail').forEach(img => {
            img.addEventListener('click', function() {
                if (imageModal && modalImage) {
                    modalImage.src = this.src;
                    imageModal.style.display = 'flex';
                    document.body.style.overflow = 'hidden'; // 背景のスクロールを禁止
                }
            });
        });
        // ▲▲▲ ここまで追加 ▲▲▲

        // メニューボタンの処理
        document.querySelectorAll('.menu-button').forEach(button => {
            button.addEventListener('click', function(event) {
                event.stopPropagation();
                const dropdown = this.nextElementSibling;
                document.querySelectorAll('.menu-dropdown.is-active').forEach(openDropdown => {
                    if (openDropdown !== dropdown) {
                        openDropdown.classList.remove('is-active');
                    }
                });
                dropdown.classList.toggle('is-active');
            });
        });

        // ドロップダウン外をクリックしたら閉じる
        document.addEventListener('click', function(event) {
            document.querySelectorAll('.menu-dropdown.is-active').forEach(openDropdown => {
                if (!openDropdown.contains(event.target) && !openDropdown.previousElementSibling.contains(event.target)) {
                    openDropdown.classList.remove('is-active');
                }
            });
        });

        // 「通報する」アクションの処理
        document.querySelectorAll('.report-action').forEach(item => {
            item.addEventListener('click', function(event) {
                event.preventDefault();
                const postId = this.dataset.postId;
                window.location.href = `report.php?postId=${encodeURIComponent(postId)}`;
            });
        });

        // リアクションボタンの処理
        document.querySelectorAll('.reaction-button').forEach(button => {
            button.addEventListener('click', function() {
                if (currentUserId === null || currentUserId === undefined) {
                    displayMessage('リアクションするにはログインが必要です。', 'error');
                    return;
                }

                const postId = parseInt(this.dataset.postId);
                const reactionType = this.dataset.reaction;
                const likeCountSpan = this.closest('.post-actions').querySelector('.like-count');
                const heartCountSpan = this.closest('.post-actions').querySelector('.heart-count');

                fetch('api/reaction_process.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ 
                        postId: postId, 
                        reactionType: reactionType,
                        userId: currentUserId
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        likeCountSpan.textContent = data.newLikes;
                        heartCountSpan.textContent = data.newHearts;
                        if (reactionType === 'good') {
                            this.classList.toggle('active', data.isLiked);
                        } else if (reactionType === 'heart') {
                            this.classList.toggle('active', data.isHearted);
                        }
                    } else {
                        displayMessage('リアクション処理中にエラーが発生しました: ' + data.message, 'error');
                    }
                })
                .catch(error => {
                    console.error('Fetchエラー:', error);
                    displayMessage('通信エラーが発生しました。', 'error');
                });
            });
        });
    }

    // ページ読み込み時に投稿をレンダリング
    renderPosts();
});
