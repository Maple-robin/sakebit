document.addEventListener('DOMContentLoaded', function () {
    // フォームと関連要素を取得
    const postForm = document.getElementById('postForm');
    const cancelButton = document.querySelector('.cancel-btn');
    const imageInput = document.getElementById('post-images');
    const previewGrid = document.getElementById('image-preview-grid');

    const maxFiles = 4; // 最大アップロード数

    // カスタムメッセージボックスを表示する関数
    function displayMessage(message, type) {
        if (!message) return;
        const messageBox = document.createElement('div');
        messageBox.classList.add('custom-message-box', type);
        messageBox.textContent = message;
        document.body.appendChild(messageBox);
        setTimeout(() => messageBox.remove(), 3000);
    }

    // --- 画像アップロード関連の処理 ---

    // ファイルが選択された時の処理
    imageInput.addEventListener('change', () => {
        renderPreviews();
    });

    // プレビューを描画する関数
    function renderPreviews() {
        previewGrid.innerHTML = ''; // プレビューをクリア

        const files = Array.from(imageInput.files);

        if (files.length > maxFiles) {
            displayMessage(`画像は最大${maxFiles}枚までです。`, 'error');
            // ファイルリストを4つに制限（この操作は直接はできないため、ユーザーに再選択を促す）
        }

        const filesToProcess = files.slice(0, maxFiles);

        filesToProcess.forEach(file => {
            const reader = new FileReader();
            reader.onload = function (e) {
                const previewItem = document.createElement('div');
                previewItem.classList.add('preview-item');

                const img = document.createElement('img');
                img.src = e.target.result;

                previewItem.appendChild(img);
                previewGrid.appendChild(previewItem);
            };
            reader.readAsDataURL(file);
        });
    }

    // --- フォーム送信・キャンセルの処理 ---

    // キャンセルボタン
    if (cancelButton) {
        cancelButton.addEventListener('click', function () {
            if (confirm('入力中の内容は破棄されます。本当にキャンセルしますか？')) {
                history.back();
            }
        });
    }

    // フォーム送信時
    if (postForm) {
        postForm.addEventListener('submit', function (event) {
            const title = document.getElementById('post-title').value.trim();
            const content = document.getElementById('post-content').value.trim();

            if (title === '' || content === '') {
                displayMessage('タイトルと内容の両方を入力してください。', 'error');
                event.preventDefault(); // 送信を中止
                return;
            }

            if (imageInput.files.length > maxFiles) {
                displayMessage(`画像は最大${maxFiles}枚までです。選択し直してください。`, 'error');
                event.preventDefault(); // 送信を中止
                return;
            }
        });
    }
});