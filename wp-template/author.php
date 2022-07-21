<?php $curauth = (isset($_GET['author_name'])) ? get_user_by('slug', $author_name) : get_userdata(intval($author)); ?>
<?php get_header(); ?>
<?php
$clsHavePosts = new aa_havePosts();
$authorID = (int)$curauth->ID;
$authorVar = array(
    'author_display_name' => $curauth->display_name,
    'author_description' => get_user_meta($authorID, 'description', true),
    'author_avatar' => get_user_meta($authorID, "avatar_url", true)
);
?>
<!-- Main -->
<main id="content">
    <div id="author" class="author container">
        <div id="author-content" class="author-content">
            <section>
                <div class="author-profile">
                    <div class="author-img">
                        <figure>
                            <img src="<?php echo $authorVar["author_avatar"]; ?>" />
                        </figure>
                    </div>
                    <div class="author-description">
                        <h1><?php echo $authorVar["author_display_name"]; ?></h1>
                        <p><?php echo $authorVar["author_description"]; ?></p>
                    </div>
                </div>
                <div class="clearfix"></div>
                <hr>

                <?php
                $args = array(
                    'post_type' => "post",
                    'author' => $authorID,
                    'posts_per_page' => -1,
                );
                $authorPosts = $clsHavePosts->aa_have_posts($args);
                if ($authorPosts["have_posts"]) :
                    ?>
                    <div id="author-content-posts" class="author-posts">
                        <div id="author-posts-swiper" class="swiper author-posts-swiper">
                            <div class="title">
                                <i></i>
                                <h2><a href="#"><?php echo "نوشته‌های " . $authorVar["author_display_name"]; ?></a></h2>
                                <i class="after-h"></i>
                                <div class="swiper-navigation">
                                    <button class="swiper-button-prev icons"></button>
                                    <button class="swiper-button-next icons"></button>
                                </div>
                            </div>
                            <div class="swiper-wrapper">
                                <?php $clsHavePosts->aa_swiperPostsLoop($authorPosts["query_posts"]); ?>
                            </div>
                            <div class="swiper-pagination"></div>
                        </div>
                    </div>
                    <div class="clearfix space"></div>
                    <?php
                endif;
                ?>

                <?php
                $args = array(
                    'post_type' => "products",
                    'author' => $authorID,
                    'posts_per_page' => -1,
                );
                $authorProducts = $clsHavePosts->aa_have_posts($args);
                if ($authorProducts["have_posts"]) :
                    ?>
                    <div id="author-content-products" class="author-products">
                        <div id="author-products-swiper" class="swiper author-products-swiper">
                            <div class="title">
                                <i></i>
                                <h2><a href="#"><?php echo "چیزهای " . $authorVar["author_display_name"]; ?></a></h2>
                                <i class="after-h"></i>
                                <div class="swiper-navigation">
                                    <button class="swiper-button-prev icons"></button>
                                    <button class="swiper-button-next icons"></button>
                                </div>
                            </div>
                            <div class="swiper-wrapper">
                                <?php $clsHavePosts->aa_swiperProductsLoop($authorProducts["query_posts"]) ?>
                            </div>
                            <div class="swiper-pagination"></div>
                        </div>
                    </div>
                    <div class="clearfix space"></div>
                    <?php
                endif;
                ?>
            </section>
        </div>
    </div>
</main>
<div class="clearfix"></div>
<?php get_footer(); ?>