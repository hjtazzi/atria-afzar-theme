<?php
$args = array(
    'post_type'      => 'post',
    'posts_per_page' => 11,
);
$clsHavePosts = new aa_havePosts();
$homePostsSwiper = $clsHavePosts->aa_have_posts($args);

if ($homePostsSwiper["have_posts"]) :
?>

    <div id="home-posts" class="home-posts container">
        <div id="home-posts-swiper" class="swiper home-posts-swiper">
            <div class="title">
                <i></i>
                <h2><a href="<?php echo site_url()."/blog"; ?>">بلاگ</a></h2>
                <i class="after-h"></i>
                <div class="swiper-navigation">
                    <button class="swiper-button-prev icons"></button>
                    <button class="swiper-button-next icons"></button>
                </div>
            </div>

            <div class="swiper-wrapper">
                <?php $clsHavePosts->aa_swiperPostsLoop($homePostsSwiper["query_posts"]) ?>
                
                <div class="swiper-slide">
                    <a href="./blog">show more</a>
                </div>
            </div>
            <div class="swiper-pagination"></div>
        </div>
    </div>

<?php
endif;
