document.addEventListener('DOMContentLoaded', () => {
    // アイコン変更処理
    const userIconInput = document.getElementById('user-icon');
    const profileIconPreview = document.querySelector('.profile-icon-preview');
    const btnChangeIcon = document.querySelector('.btn-change-icon');

    if (userIconInput && profileIconPreview && btnChangeIcon) {
        // ボタンクリックでファイル選択をトリガー
        btnChangeIcon.addEventListener('click', (e) => {
            e.preventDefault(); // デフォルトのフォーム送信を防ぐ
            userIconInput.click();
        });

        userIconInput.addEventListener('change', (event) => {
            const file = event.target.files ? event.target.files.item(0) : null;
            if (file) {
                const reader = new FileReader();
                reader.onload = (e) => {
                    profileIconPreview.src = e.target.result;
                };
                reader.readAsDataURL(file);
            }
        });
    }

    // 自己紹介文字数カウント
    const bioTextarea = document.getElementById('bio');
    const bioCurrentChar = document.getElementById('bio-current-char');
    const MAX_BIO_LENGTH = 200; // 自己紹介の最大文字数

    if (bioTextarea && bioCurrentChar) {
        function updateCharCount() {
            const currentLength = bioTextarea.value.length;
            bioCurrentChar.textContent = currentLength;

            if (currentLength > MAX_BIO_LENGTH) {
                bioCurrentChar.style.color = 'red';
                // 保存ボタンを無効にするなどの処理を追加する場合はここに記述
                // 例: document.querySelector('.btn-save-profile').disabled = true;
            } else {
                bioCurrentChar.style.color = '#888';
                // 無効にしたボタンを有効に戻すなどの処理を追加する場合はここに記述
                // 例: document.querySelector('.btn-save-profile').disabled = false;
            }
        }

        bioTextarea.addEventListener('input', updateCharCount);
        // ページ読み込み時にも一度カウントを更新
        updateCharCount();
    }

    // カスタムメッセージボックスを表示する関数 (MyPage.jsと共通)
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

    // URLパラメータからメッセージを取得して表示
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.has('profile_updated') && urlParams.get('profile_updated') === 'true') {
        displayMessage('プロフィールを更新しました！', 'success');
        history.replaceState(null, '', window.location.pathname); // URLからパラメータを削除
    } else if (urlParams.has('profile_update_error')) {
        const errorMessage = urlParams.get('profile_update_error');
        displayMessage(`更新エラー: ${decodeURIComponent(errorMessage)}`, 'error');
        history.replaceState(null, '', window.location.pathname); // URLからパラメータを削除
    }

    // 複数カテゴリトグル対応（スマホメニュー用）
    const spCategoryToggles = document.querySelectorAll('.sp-menu__category-toggle');
    spCategoryToggles.forEach(toggle => {
        toggle.addEventListener('click', function () {
            this.classList.toggle('is-open');
            const subList = this.querySelector('.sp-menu__sub-list');
            if (subList) {
                subList.classList.toggle('is-open');
                if (!subList.classList.contains('is-open')) {
                    subList.scrollTop = 0;
                }
            }
        });
    });
});
