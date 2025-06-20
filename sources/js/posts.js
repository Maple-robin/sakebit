// DOMContentLoadedは、HTMLの読み込みが完了したときに実行されます。
document.addEventListener('DOMContentLoaded', function() {
    // PHPから渡された投稿データと現在のユーザーIDを使用
    // postsData: [{id, userIcon, userName, title, content, images, likes, hearts, isLiked, isHearted}, ...]
    // currentUserId: 現在ログインしているユーザーのID (nullの場合もあり)

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
            attachEventListeners(); // イベントリスナーをアタッチ
        } else {
            postsContainer.innerHTML = '<p style="text-align: center; margin-top: 50px; font-size: 1.8rem; color: #555;">まだ投稿がありません。</p>';
        }
    }

    // イベントリスナーをアタッチする関数
    function attachEventListeners() {
        // メニューボタンの処理
        document.querySelectorAll('.menu-button').forEach(button => {
            button.addEventListener('click', function(event) {
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
                event.preventDefault(); // リンクのデフォルト動作を防ぐ
                const postId = this.dataset.postId;
                // report.htmlに遷移（投稿IDをクエリパラメータで渡す）
                window.location.href = `report.html?postId=${encodeURIComponent(postId)}`;
            });
        });

        // リアクションボタンの処理 (likes, hearts はDBから取得して初期表示、更新はJS+DB連携)
        document.querySelectorAll('.reaction-button').forEach(button => {
            button.addEventListener('click', function() {
                // ログインしているかチェック
                if (currentUserId === null || currentUserId === undefined) {
                    displayMessage('リアクションするにはログインが必要です。', 'error');
                    return;
                }

                const postId = parseInt(this.dataset.postId);
                const reactionType = this.dataset.reaction; // 'good' または 'heart'
                const likeCountSpan = this.closest('.post-actions').querySelector('.like-count');
                const heartCountSpan = this.closest('.post-actions').querySelector('.heart-count');

                // AJAXリクエストを送信
                fetch('api/reaction_process.php', { // 新しく作成するPHPエンドポイント
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ 
                        postId: postId, 
                        reactionType: reactionType,
                        userId: currentUserId // ログインユーザーIDを送信
                    })
                })
                .then(response => {
                    if (!response.ok) {
                        // HTTPエラーレスポンス (例: 404, 500)
                        return response.text().then(text => { // エラーレスポンスの本文を読み込む
                            console.error('HTTPエラー本文:', text);
                            throw new Error(`HTTP error! status: ${response.status}. Server response: ${text.substring(0, 100)}...`);
                        });
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        likeCountSpan.textContent = data.newLikes;
                        heartCountSpan.textContent = data.newHearts;
                        // クラスのトグルもサーバーからのis_reacted情報に基づいて行う
                        if (reactionType === 'good') {
                            if (data.isLiked) {
                                this.classList.add('active');
                            } else {
                                this.classList.remove('active');
                            }
                            // ハートボタンがもしアクティブなら非アクティブにする（一方しか押せない場合）
                            if (data.isHearted === false && heartCountSpan.closest('.reaction-button.heart').classList.contains('active')) {
                                heartCountSpan.closest('.reaction-button.heart').classList.remove('active');
                                heartCountSpan.textContent = data.newHearts; // ハート数も更新
                            }
                        } else if (reactionType === 'heart') {
                            if (data.isHearted) {
                                this.classList.add('active');
                            } else {
                                this.classList.remove('active');
                            }
                            // いいねボタンがもしアクティブなら非アクティブにする（一方しか押せない場合）
                            if (data.isLiked === false && likeCountSpan.closest('.reaction-button.good').classList.contains('active')) {
                                likeCountSpan.closest('.reaction-button.good').classList.remove('active');
                                likeCountSpan.textContent = data.newLikes; // いいね数も更新
                            }
                        }
                        displayMessage('リアクションが更新されました！', 'success'); // 成功メッセージ
                    } else {
                        console.error('リアクション処理に失敗しました:', data.message);
                        displayMessage('リアクション処理中にエラーが発生しました: ' + data.message, 'error');
                    }
                })
                .catch(error => {
                    console.error('Fetchエラー:', error);
                    displayMessage('通信エラーが発生しました。しばらくしてから再度お試しください。', 'error');
                });
            });
        });
    }

    // ページ読み込み時に投稿をレンダリング
    renderPosts();

    // カスタムメッセージボックスのスタイルはposts.phpに直接記述されているため不要
});
