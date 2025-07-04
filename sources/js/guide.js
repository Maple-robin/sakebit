document.addEventListener('DOMContentLoaded', function () {
    console.log('ガイドページが読み込まれました');

    // Swiper要素が存在する場合のみ初期化
    const swipers = document.querySelectorAll('.swiper');
    swipers.forEach(function(swiperElement) {
        new Swiper(swiperElement, {
            loop: true,
            slidesPerView: 1,
            spaceBetween: 20,
            pagination: {
                el: swiperElement.querySelector('.swiper-pagination'),
                clickable: true,
            },
            navigation: {
                nextEl: swiperElement.querySelector('.swiper-button-next'),
                prevEl: swiperElement.querySelector('.swiper-button-prev'),
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
    });

    const cautionHeaders = document.querySelectorAll('.caution-item__header');

    cautionHeaders.forEach(header => {
        header.addEventListener('click', () => {
            const item = header.parentElement;

            // openクラスをトグル
            item.classList.toggle('open');
        });
    });
});