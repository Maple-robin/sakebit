// ズーム倍率が125%以上ならヘッダーにクラスを付与
function updateHeaderLayoutByZoom() {
    const zoomRatio = window.devicePixelRatio || 1;
    const header = document.querySelector('.admin-header');
    if (!header) return;
    if (zoomRatio >= 1.25) {
        header.classList.add('header-2col');
    } else {
        header.classList.remove('header-2col');
    }
}

window.addEventListener('resize', updateHeaderLayoutByZoom);
window.addEventListener('DOMContentLoaded', updateHeaderLayoutByZoom);
