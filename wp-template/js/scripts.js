const ww = window.innerWidth;
const loading = document.getElementById("loading");
const btnLogout = document.getElementById("btn-logout");
const showNevMenuBtn = document.getElementById("show-nev-menu");
const navHeaderMenu = document.getElementById("nav-header-menu");
const homeProductSwiper = document.getElementById("home-product");
const homePostsSwiper = document.getElementById("home-posts");
const blogContent = document.getElementById("blog-content");
const productsContent = document.getElementById("products-content");
const authorContentPosts = document.getElementById("author-content-posts");
const authorContentProducts = document.getElementById("author-content-products");
const previewMain = document.getElementById("preview-main");
const previewFooter = document.getElementById("preview-footer");
const previewIframe = document.querySelector("#preview-main iframe");
const productSlider = document.getElementById("product-slider");

/* Window */
window.onresize = () => {
    if (window.innerWidth != ww && window.innerWidth >= 768) {
        navHeaderMenu ? navHeaderMenu.classList.remove("nav-hidden") : "";
    } else if (window.innerWidth != ww && window.innerWidth < 768) {

    }
}

showNevMenuBtn ? showNevMenuBtn.onclick = () => navHeaderMenu.classList.toggle("nav-hidden") : "";

/* Swipers */
const swiperInit = {
    preloadImages: false,
    lazy: true,
    slidesPerView: 1,
    slidesPerGroup: 1,
    spaceBetween: 4,
    loop: false,
    keyboard: false,
    pagination: {
        el: ".swiper-pagination",
        clickable: true,
        dynamicBullets: false,
    },
    navigation: {
        nextEl: ".swiper-button-next",
        prevEl: ".swiper-button-prev",
    },
};
const productSwiperBreakpoints = {
    breakpoints: {
        768: {
            slidesPerView: 2,
            slidesPerGroup: 1,
            spaceBetween: 6,
        },
        960: {
            slidesPerView: 3,
            slidesPerGroup: 1,
            spaceBetween: 8,
        },
        1200: {
            slidesPerView: 4,
            slidesPerGroup: 1,
            spaceBetween: 12,
        },
    },
};
const postsSwiperBreakpoints = {
    breakpoints: {
        768: {
            slidesPerView: 2,
            slidesPerGroup: 2,
            spaceBetween: 6,
        },
        960: {
            slidesPerView: 3,
            slidesPerGroup: 3,
            spaceBetween: 8,
        },
        1200: {
            slidesPerView: 4,
            slidesPerGroup: 4,
            spaceBetween: 12,
        },
    },
};
const blogPostsSwiperBreakpoints = {
    breakpoints: {
        768: {
            slidesPerView: 1,
            slidesPerGroup: 1,
            spaceBetween: 6,
        },
        960: {
            slidesPerView: 2,
            slidesPerGroup: 2,
            spaceBetween: 8,
        },
        1200: {
            slidesPerView: 3,
            slidesPerGroup: 3,
            spaceBetween: 12,
        },
    },
};

const homeProductSwiperInit = homeProductSwiper ? new Swiper("#home-product-swiper", { ...swiperInit, ...productSwiperBreakpoints }) : "";
const homePostsSwiperInit = homePostsSwiper ? new Swiper("#home-posts-swiper", { ...swiperInit, ...postsSwiperBreakpoints }) : "";
const blogContentSwiperInit = blogContent ? new Swiper(".blog-posts-swiper", { ...swiperInit, ...blogPostsSwiperBreakpoints }) : "";
const productsContentSwiperInit = productsContent ? new Swiper(".products-swiper", { ...swiperInit, ...productSwiperBreakpoints }) : "";
const authorContentPostsInit = authorContentPosts ? new Swiper(".author-posts-swiper", { ...swiperInit, ...postsSwiperBreakpoints }) : "";
const authorContentProductsInit = authorContentProducts ? new Swiper(".author-products-swiper", { ...swiperInit, ...productSwiperBreakpoints }) : "";
const productSwiperInit = productSlider ? new Swiper("#product-slider-swiper", { ...swiperInit, spaceBetween: 40 }) : "";

/* Preview */
if (previewMain && previewFooter && previewIframe) {
    const btnChangeSizes = document.getElementsByClassName("ch-size");
    for (let i = 0; i < btnChangeSizes.length; i++) {
        const element = btnChangeSizes[i];
        element.onclick = (e) => {
            const sizeW = element.getAttribute("data-size-w");
            const sizeH = element.getAttribute("data-size-h");
            previewIframe.style.width = sizeW;
            previewIframe.style.height = sizeH;
        }
    }
}

(btnLogout) ? btnLogout.onclick = () => localStorage.clear() : "";

document.body.onload = () => loading ? loading.classList.add("loading-hidden") : "";