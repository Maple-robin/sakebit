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
                // report.phpに遷移（投稿IDをクエリパラメータで渡す）
                window.location.href = `report.php?postId=${encodeURIComponent(postId)}`;
            });
        });

        // 「削除する」アクションの処理 (MyPage専用)
        document.querySelectorAll('.delete-action').forEach(item => {
            item.addEventListener('click', function(event) {
                event.preventDefault(); // リンクのデフォルト動作を防ぐ
                const postId = this.dataset.postId;
                showCustomConfirm('本当にこの投稿を削除しますか？', () => {
                    // ユーザーが「はい」を選択した場合の処理
                    fetch('api/delete_post.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({ postId: postId })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            displayMessage('投稿が削除されました。', 'success');
                            // 投稿をDOMから削除
                            document.getElementById(`post-${postId}`).remove();
                            // データからも削除して再レンダリング (もし必要なら)
                            // myPostsData = myPostsData.filter(post => post.id !== postId);
                            // renderPosts(myPostsData, 'my-posts-content');
                            // ページ全体のリロードを推奨する場合もあるが、ここではSPA風に削除
                            location.reload(); // 簡単な方法としてページリロード
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

    // タブ切り替えロジック
    document.querySelectorAll('.tab-button').forEach(button => {
        button.addEventListener('click', function() {
            // すべてのタブボタンからactiveクラスを削除
            document.querySelectorAll('.tab-button').forEach(btn => btn.classList.remove('active'));
            // クリックされたボタンにactiveクラスを追加
            this.classList.add('active');

            // すべてのタブコンテンツからactiveクラスを削除
            document.querySelectorAll('.tab-content').forEach(content => content.classList.remove('active'));
            // 対応するタブコンテンツにactiveクラスを追加
            const targetTabId = this.dataset.tab;
            const targetTabContent = document.getElementById(targetTabId);
            if (targetTabContent) {
                targetTabContent.classList.add('active');
                // タブが切り替わったときに該当の投稿をレンダリング
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

    // 初回レンダリング（デフォルトで「自分の投稿」タブを表示）
    // renderPosts には、PHPで定義された myPostsData 変数を渡す
    renderPosts(myPostsData, 'my-posts-content');

    // カスタム確認モーダルを表示する関数 (MyPage.js 既存ロジック)
    function showCustomConfirm(message, onConfirm) {
        const modalOverlay = document.getElementById('custom-confirm-modal');
        const confirmMessage = document.getElementById('confirm-message');
        const confirmYesButton = document.getElementById('confirm-yes');
        const confirmNoButton = document.getElementById('confirm-no');

        confirmMessage.textContent = message;
        modalOverlay.style.display = 'flex'; // モーダルを表示

        // イベントリスナーを一度だけ設定するために、既存のリスナーを削除
        confirmYesButton.onclick = null;
        confirmNoButton.onclick = null;

        confirmYesButton.onclick = () => {
            modalOverlay.style.display = 'none'; // モーダルを非表示
            onConfirm(); // 確定時の処理を実行
        };

        confirmNoButton.onclick = () => {
            modalOverlay.style.display = 'none'; // モーダルを非表示
            // 何もしない
        };
    }

    // プロフィール編集ボタンのイベントリスナー
    const editProfileButton = document.querySelector('.edit-profile-button');
    if (editProfileButton) {
        editProfileButton.addEventListener('click', () => {
            window.location.href = 'profile_edit.php'; // モーダルではなくページ遷移
        });
    }

});
