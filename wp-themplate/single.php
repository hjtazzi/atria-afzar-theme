<?php get_header(); ?>
<?php
while (have_posts()) :
    the_post();

    $postID   = (int)get_the_ID();
    $postTags = get_the_tags();
    $keywords = array();
    if ($postTags) {
        foreach ($postTags as $tag) {
            $keywords[] = array(
                'tag_name' => $tag->name,
                'tag_slug' => $tag->slug,
            );
        }
    }
    $getPost = get_post($postID, "ARRAY_A");
    $getPostVar = array(
        'post_view' => (int)get_post_meta($postID, "post_views", true),
        'post_thumbnail_url' => get_the_post_thumbnail_url(),
        'post_keywords' => $keywords,
        'post_short_link' => site_url() . "?p=" . $postID,
        'post_date' => the_date('Y-m-d H:i:s', '', '', false),
        'author_display_name' => get_the_author_meta("display_name", $getPost["post_author"]),
        'author_page_url' => site_url() . "/author/" . get_the_author_meta("user_login", $getPost["post_author"]),
        'author_avatar' => get_user_meta($getPost["post_author"], "avatar_url", true),
    );
    $postVar = array_merge($getPost, $getPostVar);

    if ($postVar["post_status"] == "publish") update_post_meta($postID, 'post_views', $postVar["post_view"] + 1);

endwhile;
wp_reset_postdata();

$dataID = array(
    'post' => (int)$postID,
    'user' => (int)get_current_user_id(),
    'login' => (bool)is_user_logged_in(),
    'depth' => (int)get_option("thread_comments_depth"),
);
?>
<!-- Main -->
<main id="content">
    <div id="single-page" class="single-page container">
        <?php get_sidebar(); ?>
        <section id="single-content" class="single-content">
            <article>
                <div class="post-title">
                    <h1><?php echo $postVar["post_title"]; ?></h1>
                </div>
                <div class="clearfix"></div>

                <div class="post-info">
                    <nav class="breadcrumb">
                        <ul>
                            <li class="item">
                                <a href="<?php echo site_url(); ?>">خانه</a><i class="icons-keyboard_arrow_left"></i>
                            </li>
                            <li class="item">
                                <a href="<?php echo site_url() . "/blog"; ?>">بلاگ</a><i class="icons-keyboard_arrow_left"></i>
                            </li>
                            <li class="item">
                                <?php
                                $postCats = $getPost["post_category"];
                                $postCat = get_term($postCats[0]);
                                ?>
                                <a href="<?php echo site_url() . "/category/" . $postCat->slug; ?>"><?php echo $postCat->name; ?></a><i class="icons-keyboard_arrow_left"></i>
                            </li>
                            <li class="item">
                                <a href="<?php echo $postVar["guid"]; ?>"><?php echo $postVar["post_title"]; ?></a>
                            </li>
                        </ul>
                    </nav>
                    <p class="date">
                        <i class="icons-schedule"></i>
                        <span><?php echo $clsInit->aa_shamsiDate($postVar["post_date"], ""); ?></span>
                    </p>
                    <p class="view">
                        <i class="icons-visibility"></i>
                        <span><?php echo $postVar["post_view"]; ?></span>
                    </p>
                </div>
                <div class="clearfix"></div>

                <figure class="post-attachment">
                    <img src="<?php echo $postVar["post_thumbnail_url"]; ?>" alt="<?php echo $postVar["post_title"]; ?>">
                </figure>
                <div class="clearfix"></div>

                <div class="post-content">
                    <?php the_content(); ?>
                </div>
                <div class="clearfix"></div>

                <div class="post-short-link">
                    <p>
                        <i class="icons-link"></i>
                        <a href="<?php echo $postVar["post_short_link"]; ?>"
                            title="<?php echo "لینک کوتاه برای " . $postVar["post_title"]; ?>"><?php echo $postVar["post_short_link"]; ?></a>
                    </p>
                </div>
                <div class="clearfix"></div>

                <div class="post-author">
                    <?php $authorDNTitle = "ارسال شده توسط " . $postVar["author_display_name"]; ?>
                    <figure>
                        <a href="<?php echo $postVar["author_page_url"] ?>" title="<?php echo $authorDNTitle; ?>">
                            <img src="<?php echo $postVar["author_avatar"] ?>" />
                        </a>
                    </figure>
                    <p>
                        <a href="<?php echo $postVar["author_page_url"] ?>" title="<?php echo $authorDNTitle; ?>">
                            <?php echo $postVar["author_display_name"]; ?>
                        </a>
                    </p>
                </div>
                <div class="clearfix"></div>

                <div class="post-tags">
                    <p>
                        <b>برچسب‌ها:</b>
                        <?php foreach ($postVar["post_keywords"] as $tag) { ?>
                            <span>
                                <a href="<?php echo site_url() . "/tag/" . $tag["tag_slug"]; ?>" title="<?php echo $tag["tag_name"] ?>"><?php echo $tag["tag_name"] ?></a>
                            </span>
                        <?php } ?>
                    </p>
                </div>
                <div class="clearfix space"></div>

                <div id="post-comments" class="post-comments">
                    <div class="title">
                        <i></i>
                        <h6>دیدگاه‌ها</h6>
                        <i class="after-h"></i>
                        <span><?php echo get_comments_number($postID) . " دیدگاه" ?></span>
                    </div>

                    <div id="post-comments_root" class="post-comments_root" data-options='<?php echo json_encode($dataID); ?>'></div>

                    <div id="comments-section-loading" class="section-loading loading-hidden">
                        <div class="loader"></div>
                    </div>
                </div>
                <div class="clearfix"></div>
            </article>
        </section>
    </div>
</main>
<div class="clearfix"></div>

<?php get_footer(); ?>