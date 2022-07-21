<?php
$args = array(
    'post_type'      => 'products',
    'posts_per_page' => 11,
);
$clsHavePosts = new aa_havePosts();
$homeProductsSwiper = $clsHavePosts->aa_have_posts($args);

if ($homeProductsSwiper["have_posts"]) :
?>

    <div id="home-product" class="home-product container">
        <div id="home-product-swiper" class="swiper home-product-swiper">
            <div class="title">
                <i></i>
                <h2><a href="<?php echo site_url()."/products"; ?>">قالب‌های آتریا افزار</a></h2>
                <i class="after-h"></i>
                <div class="swiper-navigation">
                    <button class="swiper-button-prev icons"></button>
                    <button class="swiper-button-next icons"></button>
                </div>
            </div>
            <div class="swiper-wrapper">
                <?php $clsHavePosts->aa_swiperProductsLoop($homeProductsSwiper["query_posts"]) ?>

                <div class="swiper-slide">
                    <a href="./blog">show more</a>
                </div>
            </div>
            <div class="swiper-pagination"></div>
        </div>
    </div>
    <div class="clearfix space"></div>

<?php
endif;
