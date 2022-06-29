<?php

if (!class_exists('aa_havePosts')) {

class aa_havePosts
{

    public function __construct() {}

    public function aa_have_posts($args)
    {
        $query = new WP_Query($args);
        return array(
            'query_posts' => $query,
            'have_posts'  => $query->have_posts(),
            'found_posts' => $query->found_posts
        );
    }

    public function aa_swiperPostsLoop($query)
    {
        while ($query->have_posts()) :
            $query->the_post();
            ?>
            <div class="swiper-slide">
                <div class="post-card">
                    <div class="post-attachment">
                        <a href="<?php echo the_permalink(); ?>" title="<?php echo the_title(); ?>">
                            <img class="swiper-lazy" data-src="<?php echo get_the_post_thumbnail_url(); ?>" alt="<?php echo the_title(); ?>">
                        </a>
                    </div>
                    <div class="post-title">
                        <a href="<?php echo the_permalink(); ?>" title="<?php echo the_title(); ?>">
                            <h3><?php echo the_title(); ?></h3>
                        </a>
                    </div>
                    <div class="post-excerpt">
                        <?php echo the_excerpt(); ?>
                    </div>
                    <div class="post-more">
                        <a href="<?php echo the_permalink(); ?>" title="<?php echo the_title(); ?>">ادامه مطلب</a>
                    </div>
                    <div class="swiper-lazy-preloader">
                        <div class="swiper-lazy-loader"></div>
                    </div>
                </div>
            </div>
        <?php
        endwhile;
        wp_reset_postdata();
    }

    public function aa_swiperProductsLoop($query)
    {
        while ($query->have_posts()) :
            $query->the_post();
            $postID = (int)get_the_ID();

            $price    = (int)get_post_meta($postID, "pr_price", true);
            $discount = (int)get_post_meta($postID, "pr_discount", true);
            $selling  = (int)get_post_meta($postID, "pr_selling", true);

            if ($price > 0 && $discount > 0) {
                $d = 100 - $discount;
                $tPrice = ( $price * $d ) / 100;
                $tPrice = number_format($tPrice) . " <small>تومان</small>";
            } elseif ($price > 0 && $discount == 0) {
                $tPrice = $price;
                $tPrice = number_format($tPrice) . " <small>تومان</small>";
            } elseif ($price == 0) {
                $tPrice = "رایگان";
            }
            ?>
            <div class="swiper-slide">
                <div class="product-card">
                    <div class="product-attachment">
                        <a href="<?php echo the_permalink(); ?>" title="<?php echo the_title(); ?>">
                            <img class="swiper-lazy"
                                data-src="<?php echo get_the_post_thumbnail_url(); ?>"
                                alt="<?php echo the_title(); ?>">
                        </a>
                    </div>
                    <div class="product-title">
                        <a href="<?php echo the_permalink(); ?>" title="<?php echo the_title(); ?>">
                            <h3><?php echo the_title(); ?></h3>
                        </a>
                        <i></i>
                    </div>
                    <div class="product-excerpt">
                        <?php echo the_excerpt(); ?>
                    </div>
                    <div class="product-price">
                        <span><?php echo $tPrice; ?></span>
                    </div>
                    <div class="product-info">
                        <span><i class="icons-star"></i>4.5</span>
                        <span><i class="icons-local_mall"></i><?php echo $selling; ?></span>
                    </div>
                    <?php
                    if ($price > 0 && $discount > 0) :
                        ?><span class="product-discount"><?php echo $discount; ?>%<span>تخفیف</span></span><?php
                    endif
                    ?>
                    <div class="swiper-lazy-preloader">
                        <div class="swiper-lazy-loader"></div>
                    </div>
                </div>
            </div>
            <?php
        endwhile;
        wp_reset_postdata();
    }
}

}