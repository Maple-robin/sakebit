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
    // ここに重複して定義されている場合は、どちらかを削除することを検討してください。
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

    // タブ切り替え機能
    const tabButtons = document.querySelectorAll('.tabs .tab-button');
    const tabContents = document.querySelectorAll('.mypage-container .tab-content');

    tabButtons.forEach(button => {
        button.addEventListener('click', () => {
            // すべてのタブボタンとコンテンツからactiveクラスを削除
            tabButtons.forEach(btn => btn.classList.remove('active'));
            tabContents.forEach(content => content.classList.remove('active'));

            // クリックされたボタンにactiveクラスを追加
            button.classList.add('active');

            // 対応するコンテンツにactiveクラスを追加
            const targetTabId = button.dataset.tab;
            document.getElementById(targetTabId).classList.add('active');
        });
    });

    // 「プロフィールを編集」ボタンのクリックイベント
    const editProfileButton = document.querySelector('.edit-profile-button');
    if (editProfileButton) {
        editProfileButton.addEventListener('click', () => {
            window.location.href = 'profile_edit.php';
        });
    }

    // ダミーの投稿データと「いいね」状態（今回はPHPからデータが渡されないため、JSで定義）
    // const myPostsData = []; // MyPage.phpで定義済み
    // const likedPostsData = []; // MyPage.phpで定義済み

    // 投稿リストをレンダリングする関数
    function renderPosts(posts, containerId) {
        const container = document.querySelector(`#${containerId} .posts-list`);
        if (!container) return; // コンテナが見つからない場合は処理を終了

        container.innerHTML = ''; // 既存の投稿をクリア

        if (posts.length === 0) {
            container.innerHTML = '<p class="no-posts-message">まだ投稿がありません。</p>';
            return;
        }

        posts.forEach(post => {
            const postCard = document.createElement('div');
            postCard.classList.add('post-card');
            postCard.dataset.postId = post.id; // 投稿IDをデータ属性として保持

            const goodsClass = post.liked ? 'active' : '';
            const badsClass = post.baddened ? 'active' : '';

            postCard.innerHTML = `
                <h3>${post.title}</h3>
                <p>${post.content}</p>
                <div class="post-reactions">
                    <span class="reaction-count good-count ${goodsClass}">
                        <i class="fas fa-thumbs-up"></i> ${post.goods}
                    </span>
                    <span class="reaction-count bad-count ${badsClass}">
                        <i class="fas fa-thumbs-down"></i> ${post.bads}
                    </span>
                </div>
                ${post.is_mine ? `
                    <div class="post-actions">
                        <button class="edit-post-button" data-post-id="${post.id}">編集</button>
                        <button class="delete-post-button" data-post-id="${post.id}">削除</button>
                    </div>
                ` : ''}
            `;
            container.appendChild(postCard);
        });

        // 削除ボタンと編集ボタンにイベントリスナーを設定 (自分の投稿タブのみ)
        if (containerId === 'my-posts-content') {
            container.querySelectorAll('.delete-post-button').forEach(button => {
                button.addEventListener('click', (event) => {
                    const postId = event.target.dataset.postId;
                    // カスタム確認モーダルを表示
                    showCustomConfirm(`本当にこの投稿を削除しますか？`, () => {
                        // ユーザーが「はい」を選択した場合の処理
                        // ここでサーバーに削除リクエストを送信する (未実装)
                        alert(`投稿ID: ${postId} を削除します (機能未実装)。`);
                        // 実際に削除されたら、リストから削除して再レンダリング
                        // const index = myPostsData.findIndex(p => p.id == postId);
                        // if (index > -1) {
                        //     myPostsData.splice(index, 1);
                        //     renderPosts(myPostsData, 'my-posts-content');
                        // }
                    });
                });
            });

            container.querySelectorAll('.edit-post-button').forEach(button => {
                button.addEventListener('click', (event) => {
                    const postId = event.target.dataset.postId;
                    alert(`投稿ID: ${postId} を編集します (機能未実装)。`);
                    // 編集ページへ遷移するロジックなど
                    // window.location.href = `edit_post.php?id=${postId}`;
                });
            });
        }
    }

    // 初回レンダリング
    renderPosts(myPostsData, 'my-posts-content');
    renderPosts(likedPostsData, 'liked-posts-content'); // 初期状態では非表示だが、データはロードしておく

    // カスタム確認モーダルを表示する関数
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
            onConfirm(); // 確定時のコールバックを実行
        };

        confirmNoButton.onclick = () => {
            modalOverlay.style.display = 'none'; // モーダルを非表示
            // 何もしない
        };
    }
});
