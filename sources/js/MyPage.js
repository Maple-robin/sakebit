// DOMContentLoadedは、HTMLの読み込みが完了したときに実行されます。
document.addEventListener('DOMContentLoaded', () => {
    // PHPからのメッセージング (ページロード時に表示)
    // MyPage.phpでphpMessageとphpMessageTypeが定義されていることを前提とする
    if (typeof phpMessage !== 'undefined' && phpMessage !== '') {
        displayMessage(phpMessage, phpMessageType);
        // URLパラメータをクリアして、リロード時にメッセージが再表示されないようにする
        const url = new URL(window.location.href);
        url.searchParams.delete('profile_updated');
        url.searchParams.delete('profile_update_error');
        history.replaceState({}, document.title, url.toString());
    }

    // カスタムメッセージボックスを表示する関数 (script.jsと共通化されている場合がある)
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
        }, 3000); // 3秒後に消える
    }

    // 投稿カードを生成して表示する関数 (posts.jsから移植・調整)
    function renderPosts(postsData, targetElementId) {
        const postsListContainer = document.querySelector(`#${targetElementId} .posts-list`);
        if (!postsListContainer) {
            console.error(`Error: posts-list container for #${targetElementId} not found.`);
            return;
        }

        postsListContainer.innerHTML = ''; // 既存の投稿をクリア

        if (postsData && postsData.length > 0) {
            postsData.forEach(post => {
                let imagesHtml = '';
                const imgs = post.images || []; // 画像がない場合は空配列

                // 画像の枚数に応じたHTML構造を生成
                if (imgs.length === 1) {
                    imagesHtml = `<div class="post-images one"><img src="${post.images[0]}" alt="投稿画像" onerror="this.onerror=null;this.src='https://placehold.co/600x320/87CEFA/000000?text=No+Image';"></div>`;
                } else if (imgs.length === 2) {
                    imagesHtml = `
                        <div class="post-images two">
                            <img src="${post.images[0]}" alt="投稿画像" onerror="this.onerror=null;this.src='https://placehold.co/300x200/F08080/000000?text=No+Image';">
                            <img src="${post.images[1]}" alt="投稿画像" onerror="this.onerror=null;this.src='https://placehold.co/300x200/FFDAB9/000000?text=No+Image';">
                        </div>`;
                } else if (imgs.length === 3) {
                    imagesHtml = `
                        <div class="post-images three">
                            <div><img src="${post.images[0]}" alt="投稿画像" onerror="this.onerror=null;this.src='https://placehold.co/300x200/8B4513/FFFFFF?text=No+Image';"></div>
                            <div>
                                <img src="${post.images[1]}" alt="投稿画像" onerror="this.onerror=null;this.src='https://placehold.co/150x98/6A5ACD/FFFFFF?text=No+Image';">
                                <img src="${post.images[2]}" alt="投稿画像" onerror="this.onerror=null;this.src='https://placehold.co/150x98/F5DEB3/000000?text=No+Image';">
                            </div>
                        </div>`;
                } else if (imgs.length === 4) {
                    imagesHtml = `
                        <div class="post-images four">
                            <img src="${post.images[0]}" alt="投稿画像" onerror="this.onerror=null;this.src='https://placehold.co/150x98/8B0000/FFFFFF?text=No+Image';">
                            <img src="${post.images[1]}" alt="投稿画像" onerror="this.onerror=null;this.src='https://placehold.co/150x98/87CEFA/000000?text=No+Image';">
                            <img src="${post.images[2]}" alt="投稿画像" onerror="this.onerror=null;this.src='https://placehold.co/150x98/FFDAB9/000000?text=No+Image';">
                            <img src="${post.images[3]}" alt="投稿画像" onerror="this.onerror=null;this.src='https://placehold.co/150x98/F08080/000000?text=No+Image';">
                        </div>`;
                }

                // いいねとハートボタンのアクティブ状態を初期化
                const goodActiveClass = post.isLiked ? ' active' : '';
                const heartActiveClass = post.isHearted ? ' active' : '';

                // 自分の投稿の場合のみ削除ボタンを表示
                const deleteButtonHtml = post.isMine ?
                    `<li><a href="#" class="delete-action" data-post-id="${post.id}">削除する</a></li>` : '';

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
            attachEventListeners(); // イベントリスナーをアタッチ
        } else {
            postsListContainer.innerHTML = '<p style="text-align: center; margin-top: 50px; font-size: 1.8rem; color: #555;">まだ投稿がありません。</p>';
        }
    }

    // イベントリスナーをアタッチする関数 (posts.jsから移植・調整)
    function attachEventListeners() {
        // メニューボタンの処理
        document.querySelectorAll('.menu-button').forEach(button => {
            button.addEventListener('click', function (event) {
                event.stopPropagation(); // クリックイベントの伝播を停止
                const dropdown = this.nextElementSibling; // 次の兄弟要素がドロップダウン
                // 他の開いているドロップダウンを閉じる
                document.querySelectorAll('.menu-dropdown.is-active').forEach(openDropdown => {
                    if (openDropdown !== dropdown) {
                        openDropdown.classList.remove('is-active');
                    }
                });
                dropdown.classList.toggle('is-active'); // ドロップダウンの表示/非表示を切り替え
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
                event.preventDefault(); // リンクのデフォルト動作を防ぐ
                const postId = this.dataset.postId;
                // report.phpに遷移（投稿IDをクエリパラメータで渡す）
                window.location.href = `report.php?postId=${encodeURIComponent(postId)}`;
            });
        });

        // 「削除する」アクションの処理 (MyPage専用)
        document.querySelectorAll('.delete-action').forEach(item => {
            item.addEventListener('click', function (event) {
                event.preventDefault(); // リンクのデフォルト動作を防ぐ
                const postId = this.dataset.postId;
                showCustomConfirm('本当にこの投稿を削除しますか？', () => {
                    // ユーザーが「はい」を選択した場合の処理
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
                                // ページをリロードして変更を反映
                                location.reload();
                            } else {
                                console.error('削除失敗:', data.message);
                                displayMessage('投稿の削除に失敗しました: ' + data.message, 'error');
                            }
                        })
                        .catch(error => {
                            console.error('Fetchエラー:', error);
                            displayMessage('通信エラーが発生しました。しばらくしてから再度お試しください。', 'error');
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
                            // ★★★ ここからが修正箇所 ★★★

                            // 1. 操作対象の投稿オブジェクトを見つける
                            let sourcePost = myPostsData.find(p => p.id === postId) ||
                                likedPostsData.find(p => p.id === postId) ||
                                bookmarkedPostsData.find(p => p.id === postId);

                            if (sourcePost) {
                                // 2. 投稿オブジェクトの情報を更新
                                sourcePost.likes = data.newLikes;
                                sourcePost.hearts = data.newHearts;
                                sourcePost.isLiked = data.isLiked;
                                sourcePost.isHearted = data.isHearted;

                                // 3. いいねリストを更新
                                if (data.isLiked) {
                                    if (!likedPostsData.some(p => p.id === postId)) {
                                        likedPostsData.unshift(sourcePost); // リストの先頭に追加
                                    }
                                } else {
                                    likedPostsData = likedPostsData.filter(p => p.id !== postId);
                                }

                                // 4. ブックマークリストを更新
                                if (data.isHearted) {
                                    if (!bookmarkedPostsData.some(p => p.id === postId)) {
                                        bookmarkedPostsData.unshift(sourcePost); // リストの先頭に追加
                                    }
                                } else {
                                    bookmarkedPostsData = bookmarkedPostsData.filter(p => p.id !== postId);
                                }
                            }

                            // 5. 現在表示中のタブを再描画して、変更を即時反映
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
                            // ★★★ ここまで修正 ★★★

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