document.addEventListener('DOMContentLoaded', function () {
    const contactForm = document.getElementById('contactForm');
    const cancelButton = document.querySelector('.cancel-btn');
    const messageContainer = document.getElementById('form-message-container');

    if (cancelButton) {
        cancelButton.addEventListener('click', function () {
            if (confirm('入力中の内容は破棄されます。本当にキャンセルしますか？')) {
                history.back();
            }
        });
    }

    if (contactForm) {
        contactForm.addEventListener('submit', function (event) {
            event.preventDefault(); // デフォルトの送信をキャンセル

            if (messageContainer) messageContainer.innerHTML = '';

            const title = document.getElementById('contact-title').value.trim();
            const content = document.getElementById('contact-content').value.trim();

            if (title === '' || content === '') {
                displayMessage('件名と内容は必須です。', 'error');
                return;
            }

            const submitButton = contactForm.querySelector('.submit-btn');
            submitButton.disabled = true;
            submitButton.textContent = '送信中...';

            const formData = {
                title: title,
                content: content
            };

            fetch('api/submit_contact.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(formData)
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // ★★★ ここから修正 ★★★
                        // 成功したら、メッセージ表示用のパラメータを付けてトップページへ遷移
                        window.location.href = 'index.php?contact_success=true';
                        // ★★★ ここまで修正 ★★★
                    } else {
                        displayMessage(data.message, 'error');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    displayMessage('通信エラーが発生しました。', 'error');
                })
                .finally(() => {
                    // 成功時はリダイレクトするので、この部分は失敗時のみ実行される
                    submitButton.disabled = false;
                    submitButton.textContent = '送信する';
                });
        });
    }

    // メッセージ表示用の関数
    function displayMessage(message, type) {
        if (!messageContainer) return;
        const messageElement = document.createElement('p');
        messageElement.textContent = message;
        messageElement.className = type === 'success' ? 'success-message' : 'error-message';
        messageContainer.appendChild(messageElement);
    }
});