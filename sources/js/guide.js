document.addEventListener('DOMContentLoaded', function () {
    console.log('日本酒ガイドページが読み込まれました');

    // Swiper要素が存在する場合のみ初期化
    const swiperContainer = document.querySelector('.recommended-sake-swiper');
    if (swiperContainer) {
        new Swiper(swiperContainer, {
            loop: true,
            slidesPerView: 1,
            spaceBetween: 20,
            pagination: {
                el: '.swiper-pagination',
                clickable: true,
            },
            breakpoints: {
                768: {
                    slidesPerView: 2,
                    spaceBetween: 30,
                },
                1024: {
                    slidesPerView: 3,
                    spaceBetween: 40,
                },
            },
        });
    }

    const cautionHeaders = document.querySelectorAll('.caution-item__header');

    cautionHeaders.forEach(header => {
        header.addEventListener('click', () => {
            const item = header.parentElement;

            // openクラスをトグル
            item.classList.toggle('open');
        });
    });
});