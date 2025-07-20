// DOMContentLoadedは、HTMLの読み込みが完了したときに実行されます。
document.addEventListener('DOMContentLoaded', function () {
    const hamburgerMenu = document.querySelector('.hamburger-menu');
    const spMenu = document.querySelector('.sp-menu');

    // ハンバーガーメニューの開閉
    if (hamburgerMenu && spMenu) {
        hamburgerMenu.addEventListener('click', function () {
            hamburgerMenu.classList.toggle('is-active');
            spMenu.classList.toggle('is-active');
            document.body.classList.toggle('no-scroll', spMenu.classList.contains('is-active'));

            if (spMenu.classList.contains('is-active')) {
                spMenu.querySelectorAll('.sp-menu__sub-list.is-open').forEach(subList => {
                    subList.classList.remove('is-open');
                    const icon = subList.previousElementSibling.querySelector('.category-icon');
                    if (icon) icon.classList.remove('is-open');
                });
                spMenu.querySelectorAll('.sp-menu__sub-sub-list.is-open').forEach(subSubList => {
                    subSubList.classList.remove('is-open');
                    const icon = subSubList.previousElementSibling.querySelector('.category-icon');
                    if (icon) icon.classList.remove('is-open');
                });
            }
        });
    }

    // 複数カテゴリトグル対応
    const spCategoryToggles = document.querySelectorAll('.sp-menu__category-toggle');
    spCategoryToggles.forEach(toggle => {
        toggle.addEventListener('click', function (e) {
            if (e.target.closest('.sp-menu__sub-list')) {
                return;
            }
            const icon = this.querySelector('.category-icon');
            if (icon) {
                icon.classList.toggle('is-open');
            }
            const subList = this.querySelector('.sp-menu__sub-list');
            if (subList) {
                subList.classList.toggle('is-open');
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
                    disableOnInteraction: false
                },
                pagination: {
                    el: '.swiper-pagination',
                    clickable: true
                },
            });
        }
        if (document.querySelector('.mySwiperProducts')) {
            new Swiper('.mySwiperProducts', {
                slidesPerView: 'auto',
                spaceBetween: 20,
                freeMode: false,
                pagination: {
                    el: '.swiper-pagination',
                    clickable: true
                },
                breakpoints: {
                    768: {
                        slidesPerView: 3,
                        spaceBetween: 30
                    }
                }
            });
        }
        if (document.querySelector('.mySwiperBeginners')) {
            new Swiper('.mySwiperBeginners', {
                slidesPerView: 'auto',
                spaceBetween: 20,
                freeMode: false,
                pagination: {
                    el: '.swiper-pagination',
                    clickable: true
                },
                breakpoints: {
                    768: {
                        slidesPerView: 3,
                        spaceBetween: 30
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
    const path = window.location.pathname;

    if (urlParams.get('registered') === 'true') {
        displayMessage('新規登録が完了しました！', 'success');
        history.replaceState(null, '', path);
    }
    if (urlParams.get('loggedin') === 'true') {
        displayMessage('ログインしました！', 'success');
        history.replaceState(null, '', path);
    }
    if (urlParams.get('loggedout') === 'true') {
        displayMessage('ログアウトしました！', 'success');
        history.replaceState(null, '', path);
    }

    // ★★★ ここから追加 ★★★
    if (urlParams.get('contact_success') === 'true') {
        displayMessage('お問い合わせありがとうございます。', 'success');
        history.replaceState(null, '', path);
    }
    // ★★★ ここまで追加 ★★★
});