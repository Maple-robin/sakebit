// DOMContentLoadedは、HTMLの読み込みが完了したときに実行されます。
// これがないと、HTML要素がまだ存在しない状態でJavaScriptがそれらを操作しようとしてエラーになる可能性があります。
document.addEventListener('DOMContentLoaded', function () {
    const hamburgerMenu = document.querySelector('.hamburger-menu');
    const spMenu = document.querySelector('.sp-menu');

    // ハンバーガーメニューの開閉
    if (hamburgerMenu && spMenu) {
        hamburgerMenu.addEventListener('click', function () {
            hamburgerMenu.classList.toggle('is-active');
            spMenu.classList.toggle('is-active');
            document.body.classList.toggle('no-scroll', spMenu.classList.contains('is-active'));
        });
    }

    // 複数カテゴリトグル対応
    const spCategoryToggles = document.querySelectorAll('.sp-menu__category-toggle');
    spCategoryToggles.forEach(toggle => {
        toggle.addEventListener('click', function () {
            this.classList.toggle('is-open');
            const subList = this.querySelector('.sp-menu__sub-list');
            if (subList) {
                subList.classList.toggle('is-open');
                // サブリストを閉じるときはスクロール位置をリセット
                if (!subList.classList.contains('is-open')) {
                    subList.scrollTop = 0;
                }
            }
        });
    });

    // Swiperが読み込まれている場合のみ初期化
    if (typeof Swiper !== 'undefined') {
        if (document.querySelector('.mySwiperHero')) {
            new Swiper('.mySwiperHero', {
                loop: true,
                autoplay: {
                    delay: 5000,
                    disableOnInteraction: false,
                },
                pagination: {
                    el: '.swiper-pagination',
                    clickable: true,
                },
            });
        }

        if (document.querySelector('.mySwiperProducts')) {
            new Swiper('.mySwiperProducts', {
                slidesPerView: 'auto',
                spaceBetween: 20,
                freeMode: true,
                pagination: {
                    el: '.swiper-pagination',
                    clickable: true,
                },
                breakpoints: {
                    768: {
                        slidesPerView: 3,
                        spaceBetween: 30,
                        freeMode: false,
                    }
                }
            });
        }

        if (document.querySelector('.mySwiperBeginners')) {
            new Swiper('.mySwiperBeginners', {
                slidesPerView: 'auto',
                spaceBetween: 20,
                freeMode: true,
                pagination: {
                    el: '.swiper-pagination',
                    clickable: true,
                },
                breakpoints: {
                    768: {
                        slidesPerView: 3,
                        spaceBetween: 30,
                        freeMode: false,
                    }
                }
            });
        }
    }

    // 全ページ共通：カスタムメッセージボックスを表示する関数
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

    // URLパラメータに応じてメッセージを表示
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.get('registered') === 'true') {
        displayMessage('新規登録が完了しました！', 'success');
        history.replaceState(null, '', window.location.pathname); // URLパラメータを削除
    }
    if (urlParams.get('loggedin') === 'true') {
        displayMessage('ログインしました！', 'success');
        history.replaceState(null, '', window.location.pathname); // URLパラメータを削除
    }
    if (urlParams.get('loggedout') === 'true') {
        displayMessage('ログアウトしました！', 'success');
        history.replaceState(null, '', window.location.pathname); // URLパラメータを削除
    }

    // 全ページ共通：ログインボタンでlogin.phpに遷移 (PHPで動的にhrefが切り替わるため、ここは固定で良い)
    // ハンバーガーメニューのPHPロジックにより、ログイン/ログアウトどちらかのリンクが設定されるため、
    // ここで'js-login-btn'にclickイベントを追加する必要はありません。
    // 元のHTMLに onclick="location.href='login.php'" が直接記述されているため、このJavaScriptは不要です。
    // document.querySelectorAll('.js-login-btn').forEach(btn => {
    //     btn.addEventListener('click', function () {
    //         window.location.href = 'login.php';
    //     });
    // });
});
