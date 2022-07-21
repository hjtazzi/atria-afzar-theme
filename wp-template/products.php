<?php
/* Template Name: products */

$clsInit = new aa_init(null);
$clsHavePosts = new aa_havePosts();
$categories = $clsInit->aa_getCategories();

get_header();
?>

<!-- Main -->
<main id="content">
    <div id="products" class="products container">
        <section id="products-content" class="products-content">
            <h1 class="-products-content-title">products-content</h1>

            <?php
            for ($i = 0; $i < count($categories); $i++) :
                $args = array(
                    'post_type' => 'products',
                    'category_name' => $categories[$i]['slug'],
                    'posts_per_page' => 11,
                );
                $catPosts = $clsHavePosts->aa_have_posts($args);

                if ($catPosts["have_posts"]) :
                    ?>
                    <div id="<?php echo 'products-' . $categories[$i]['slug']; ?>" class="swiper products-swiper">
                        <div class="title">
                            <i></i>
                            <h2>
                                <a href="<?php echo site_url() . "/category/" . $categories[$i]['slug']; ?>"
                                    title="<?php echo $categories[$i]['name']; ?>" ><?php echo $categories[$i]['name']; ?></a>
                            </h2>
                            <i class="after-h"></i>
                            <div class="swiper-navigation">
                                <button class="swiper-button-prev icons"></button>
                                <button class="swiper-button-next icons"></button>
                            </div>
                        </div>
                        <div class="swiper-wrapper">
                            <?php $clsHavePosts->aa_swiperProductsLoop($catPosts['query_posts']); ?>

                            <div class="swiper-slide">
                                <a href="./blog">show more</a>
                            </div>
                        </div>
                        <div class="swiper-pagination"></div>
                    </div>
                    <div class="clearfix space"></div>
                    <?php
                endif;
            endfor;
            ?>
        </section>
    </div>
</main>

<?php
get_footer();
?>