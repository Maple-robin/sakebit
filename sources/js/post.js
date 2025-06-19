// js/post.js

document.addEventListener('DOMContentLoaded', function () {
    // カスタムメッセージボックスを表示する関数
    function displayMessage(message, type) {
        // post.php から渡されるメッセージがある場合、ここでは既に表示済みなので、再度表示しない
        // URLパラメータからのメッセージも同様に、post.php で処理済み
        // この関数は、フォーム送信時のクライアントサイドバリデーションエラー用として利用

        if (!message) return; // メッセージがない場合は何もしない

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

    const postForm = document.getElementById('postForm');
    const cancelButton = document.querySelector('.cancel-btn');
    const imageInput = document.getElementById('post-images');
    const previewArea = document.getElementById('image-preview');
    const maxFiles = 4; // 最大4枚

    // 「キャンセル」ボタンがクリックされた時の処理
    if (cancelButton) {
        cancelButton.addEventListener('click', function () {
            const confirmAction = window.confirm || function(msg) { return prompt(msg, "はい") === "はい"; }; 
            if (confirmAction('入力中の内容は破棄されます。本当にキャンセルしますか？')) {
                history.back(); 
            }
        });
    }

    // 画像選択時のプレビュー表示
    if (imageInput && previewArea) {
        imageInput.addEventListener('change', function() {
            previewArea.innerHTML = ''; // 既存のプレビューをクリア

            const selectedFiles = Array.from(this.files); // 選択された全てのファイルを取得
            let filesToProcess = selectedFiles; // まず全てのファイルを処理対象とする

            if (selectedFiles.length > maxFiles) {
                // ファイル枚数制限の警告をカスタムメッセージボックスで表示
                displayMessage('画像は最大' + maxFiles + '枚まで選択できます。超過分は無視されます。', 'error');
                filesToProcess = selectedFiles.slice(0, maxFiles); // 超過している場合は、最初のmaxFiles枚のみを処理対象とする
            }

            filesToProcess.forEach(file => { // 処理対象のファイルのみをループ
                if (!file.type.startsWith('image/')) return; // 画像ファイルのみ処理

                const reader = new FileReader();
                reader.onload = function(e) {
                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.classList.add('post-image-preview'); // スタイル用のクラスを追加
                    previewArea.appendChild(img);
                };
                reader.readAsDataURL(file); // ファイルをData URLとして読み込む
            });
        });
    }

    // フォーム送信時の処理 (JavaScriptではバリデーションのみ行い、サーバー送信はHTMLのactionに任せる)
    if (postForm) {
        postForm.addEventListener('submit', function (event) {
            // クライアントサイドでの簡易バリデーション
            const title = document.getElementById('post-title').value.trim();
            const content = document.getElementById('post-content').value.trim();

            if (title === '' || content === '') {
                // エラーメッセージをカスタムメッセージボックスで表示
                displayMessage('タイトルと内容の両方を入力してください。', 'error');
                event.preventDefault(); // フォームのデフォルト送信をキャンセル
                return;
            }
        });
    }

    // post.phpから渡されたメッセージを処理 (ページロード時のみ)
    // この部分はpost.phpの script ブロックに残すか、こちらで受け取るようにする
    // 今回はpost.phpに残す形を取るため、ここではコメントアウト
    /*
    const postPhpMessage = '<?php echo json_encode($post_message); ?>';
    const postPhpMessageType = '<?php echo json_encode($message_type); ?>';
    if (postPhpMessage) {
        displayMessage(postPhpMessage, postPhpMessageType);
    }
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.get('admin_registered') === 'true') {
        displayMessage('管理者ユーザーが登録されました！', 'success');
        history.replaceState(null, '', window.location.pathname); 
    }
    */
});
