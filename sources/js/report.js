document.addEventListener('DOMContentLoaded', function () {
    const reportForm = document.getElementById('report-form');
    const otherRadio = document.getElementById('category-other-radio');
    const otherRequiredBadge = document.getElementById('other-required-badge');
    const optionalBadge = document.getElementById('optional-badge');
    const reportContent = document.getElementById('report-content');
    const contentError = document.getElementById('content-error');
    const messageContainer = document.getElementById('form-message-container');

    // 「その他」が選択されたら、「通報内容」を必須にする
    document.querySelectorAll('input[name="report_category"]').forEach(radio => {
        radio.addEventListener('change', function () {
            if (otherRadio.checked) {
                otherRequiredBadge.classList.remove('hidden');
                optionalBadge.classList.add('hidden');
            } else {
                otherRequiredBadge.classList.add('hidden');
                optionalBadge.classList.remove('hidden');
            }
        });
    });

    // フォーム送信時の処理
    reportForm.addEventListener('submit', function (event) {
        event.preventDefault(); // デフォルトのフォーム送信をキャンセル

        // エラーメッセージをリセット
        contentError.textContent = '';
        if (messageContainer) {
            messageContainer.innerHTML = '';
        }

        // 入力値を取得
        const postId = document.getElementById('post-id').value;
        const selectedCategory = document.querySelector('input[name="report_category"]:checked').value;
        const content = reportContent.value.trim();

        // バリデーション
        if (selectedCategory === 'その他' && content === '') {
            contentError.textContent = '「その他」を選択した場合は、通報内容を入力してください。';
            return;
        }

        const submitButton = reportForm.querySelector('.submit-button');
        submitButton.disabled = true;
        submitButton.textContent = '送信中...';

        // APIにデータを送信
        fetch('api/submit_report.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    postId: postId,
                    category: selectedCategory,
                    content: content
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // ★★★ ここから修正 ★★★
                    // 成功したら、メッセージ表示用のパラメータを付けてすぐに投稿一覧ページへ遷移
                    window.location.href = 'posts.php?reported=true';
                    // ★★★ ここまで修正 ★★★
                } else {
                    // 失敗メッセージを表示
                    const messageElement = document.createElement('p');
                    messageElement.textContent = data.message;
                    messageElement.className = 'error-message';

                    if (messageContainer) {
                        messageContainer.appendChild(messageElement);
                    } else {
                        reportForm.insertAdjacentElement('afterend', messageElement);
                    }
                }
            })
            .catch(error => {
                console.error('Error:', error);
                const messageElement = document.createElement('p');
                messageElement.className = 'error-message';
                messageElement.textContent = '通信エラーが発生しました。';
                if (messageContainer) {
                    messageContainer.appendChild(messageElement);
                }
            })
            .finally(() => {
                // 成功時はリダイレクトするので、この部分は失敗時のみ実行される
                submitButton.disabled = false;
                submitButton.textContent = '通報する';
            });
    });
});