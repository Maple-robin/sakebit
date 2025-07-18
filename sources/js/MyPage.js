// DOMContentLoadedは、HTMLの読み込みが完了したときに実行されます。
document.addEventListener('DOMContentLoaded', () => {
    // PHPからのメッセージング
    if (typeof phpMessage !== 'undefined' && phpMessage !== '') {
        displayMessage(phpMessage, phpMessageType);
        const url = new URL(window.location.href);
        url.searchParams.delete('profile_updated');
        url.searchParams.delete('profile_update_error');
        history.replaceState({}, document.title, url.toString());
    }

    // 画像拡大モーダルの要素を取得
    const imageModal = document.getElementById('image-viewer-modal');
    const modalImage = document.getElementById('modal-image');
    const closeViewerBtn = document.querySelector('.close-viewer-btn');
    const postsSection = document.querySelector('.posts-section'); // ★監視対象の親要素

    // モーダルを閉じる関数
    function closeImageModal() {
        if (imageModal) {
            imageModal.style.display = 'none';
            document.body.style.overflow = 'auto';
        }
    }

    // 閉じるボタンのイベントリスナー
    if (closeViewerBtn) {
        closeViewerBtn.addEventListener('click', closeImageModal);
    }
    // 背景クリックでモーダルを閉じる
    if (imageModal) {
        imageModal.addEventListener('click', function (event) {
            if (event.target === imageModal) {
                closeImageModal();
            }
        });
    }

    // ★★★ ここから修正 ★★★
    // イベント委任を使って、親要素で画像のクリックを監視する
    if (postsSection) {
        postsSection.addEventListener('click', function (event) {
            // クリックされた要素が投稿画像(.post-image-thumbnail)かチェック
            if (event.target.matches('.post-image-thumbnail')) {
                if (imageModal && modalImage) {
                    modalImage.src = event.target.src;
                    imageModal.style.display = 'flex';
                    document.body.style.overflow = 'hidden';
                }
            }
        });
    }
    // ★★★ ここまで修正 ★★★

    // カスタムメッセージボックスを表示する関数
    function displayMessage(message, type) {
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
        }, 3000);
    }

    // 投稿カードを生成して表示する関数
    function renderPosts(postsData, targetElementId) {
        const postsListContainer = document.querySelector(`#${targetElementId} .posts-list`);
        if (!postsListContainer) {
            console.error(`Error: posts-list container for #${targetElementId} not found.`);
            return;
        }

        postsListContainer.innerHTML = '';

        if (postsData && postsData.length > 0) {
            postsData.forEach(post => {
                let imagesHtml = '';
                const imgs = post.images || [];

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

                const goodActiveClass = post.isLiked ? ' active' : '';
                const heartActiveClass = post.isHearted ? ' active' : '';
                const deleteButtonHtml = post.isMine ? `<li><a href="#" class="delete-action" data-post-id="${post.id}">削除する</a></li>` : '';

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
                                    ${deleteButtonHtml}
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
                postsListContainer.insertAdjacentHTML('beforeend', postCardHtml);
            });
            attachEventListeners();
        } else {
            postsListContainer.innerHTML = '<p style="text-align: center; margin-top: 50px; font-size: 1.8rem; color: #555;">まだ投稿がありません。</p>';
        }
    }

    function attachEventListeners() {
        // ★★★ 画像クリックのリスナーはイベント委任に移行したため、ここからは削除 ★★★

        // メニューボタンの処理
        document.querySelectorAll('.menu-button').forEach(button => {
            button.addEventListener('click', function (event) {
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
        document.addEventListener('click', function (event) {
            document.querySelectorAll('.menu-dropdown.is-active').forEach(openDropdown => {
                if (!openDropdown.contains(event.target) && !openDropdown.previousElementSibling.contains(event.target)) {
                    openDropdown.classList.remove('is-active');
                }
            });
        });

        // 「通報する」アクションの処理
        document.querySelectorAll('.report-action').forEach(item => {
            item.addEventListener('click', function (event) {
                event.preventDefault();
                const postId = this.dataset.postId;
                window.location.href = `report.php?postId=${encodeURIComponent(postId)}`;
            });
        });

        // 「削除する」アクションの処理
        document.querySelectorAll('.delete-action').forEach(item => {
            item.addEventListener('click', function (event) {
                event.preventDefault();
                const postId = this.dataset.postId;
                showCustomConfirm('本当にこの投稿を削除しますか？', () => {
                    fetch('api/delete_post.php', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json'
                            },
                            body: JSON.stringify({
                                postId: postId
                            })
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                displayMessage('投稿が削除されました。', 'success');
                                location.reload();
                            } else {
                                displayMessage('投稿の削除に失敗しました: ' + data.message, 'error');
                            }
                        })
                        .catch(error => {
                            displayMessage('通信エラーが発生しました。', 'error');
                        });
                });
            });
        });

        // リアクションボタンの処理
        document.querySelectorAll('.reaction-button').forEach(button => {
            button.addEventListener('click', function () {
                if (currentUserId === null || currentUserId === undefined) {
                    displayMessage('リアクションするにはログインが必要です。', 'error');
                    return;
                }

                const postId = parseInt(this.dataset.postId);
                const reactionType = this.dataset.reaction;

                fetch('api/reaction_process.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({
                            postId: postId,
                            reactionType: reactionType,
                            userId: currentUserId
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            let sourcePost = myPostsData.find(p => p.id === postId) ||
                                likedPostsData.find(p => p.id === postId) ||
                                bookmarkedPostsData.find(p => p.id === postId);

                            if (sourcePost) {
                                sourcePost.likes = data.newLikes;
                                sourcePost.hearts = data.newHearts;
                                sourcePost.isLiked = data.isLiked;
                                sourcePost.isHearted = data.isHearted;

                                if (data.isLiked) {
                                    if (!likedPostsData.some(p => p.id === postId)) {
                                        likedPostsData.unshift(sourcePost);
                                    }
                                } else {
                                    likedPostsData = likedPostsData.filter(p => p.id !== postId);
                                }

                                if (data.isHearted) {
                                    if (!bookmarkedPostsData.some(p => p.id === postId)) {
                                        bookmarkedPostsData.unshift(sourcePost);
                                    }
                                } else {
                                    bookmarkedPostsData = bookmarkedPostsData.filter(p => p.id !== postId);
                                }
                            }

                            const activeTab = document.querySelector('.tab-content.active');
                            if (activeTab) {
                                const activeTabId = activeTab.id;
                                if (activeTabId === 'my-posts-content') {
                                    renderPosts(myPostsData, activeTabId);
                                } else if (activeTabId === 'liked-posts-content') {
                                    renderPosts(likedPostsData, activeTabId);
                                } else if (activeTabId === 'bookmarked-posts-content') {
                                    renderPosts(bookmarkedPostsData, activeTabId);
                                }
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

    // タブ切り替えロジック
    document.querySelectorAll('.tab-button').forEach(button => {
        button.addEventListener('click', function () {
            document.querySelectorAll('.tab-button').forEach(btn => btn.classList.remove('active'));
            this.classList.add('active');

            document.querySelectorAll('.tab-content').forEach(content => content.classList.remove('active'));
            const targetTabId = this.dataset.tab;
            const targetTabContent = document.getElementById(targetTabId);
            if (targetTabContent) {
                targetTabContent.classList.add('active');
                if (targetTabId === 'my-posts-content') {
                    renderPosts(myPostsData, targetTabId);
                } else if (targetTabId === 'liked-posts-content') {
                    renderPosts(likedPostsData, targetTabId);
                } else if (targetTabId === 'bookmarked-posts-content') {
                    renderPosts(bookmarkedPostsData, targetTabId);
                }
            }
        });
    });

    // 初回レンダリング
    renderPosts(myPostsData, 'my-posts-content');

    // カスタム確認モーダルを表示する関数
    function showCustomConfirm(message, onConfirm) {
        const modalOverlay = document.getElementById('custom-confirm-modal');
        const confirmMessage = document.getElementById('confirm-message');
        const confirmYesButton = document.getElementById('confirm-yes');
        const confirmNoButton = document.getElementById('confirm-no');

        confirmMessage.textContent = message;
        modalOverlay.style.display = 'flex';

        confirmYesButton.onclick = () => {
            modalOverlay.style.display = 'none';
            onConfirm();
        };

        confirmNoButton.onclick = () => {
            modalOverlay.style.display = 'none';
        };
    }

    // プロフィール編集ボタンのイベントリスナー
    const editProfileButton = document.querySelector('.edit-profile-button');
    if (editProfileButton) {
        editProfileButton.addEventListener('click', () => {
            window.location.href = 'profile_edit.php';
        });
    }
});