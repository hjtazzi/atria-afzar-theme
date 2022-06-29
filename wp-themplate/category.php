<?php
$term_name = single_term_title("", false);
$term = get_term_by('name', $term_name, 'category', 'ARRAY_A');
$dataQuery = array(
    "taxonomy" => "cat",
    "term_id" => $term["term_id"]
);
?>
<?php get_header(); ?>

<!-- Main -->
<main id="content">
    <div id="category" class="category container">
        <?php get_sidebar(); ?>
        <div id="category-content" class="category-content">
            <section>
                <div class="title">
                    <i></i>
                    <h1>
                        <a href="<?php echo site_url() . "/category/" . $term['slug']; ?>" title="<?php echo $term['name']; ?>"><?php echo $term['name']; ?></a>
                    </h1>
                    <i class="after-h"></i>
                </div>

                <div id="term-content_root" class="term-content_root row c-1 c-lg-2 c-xl-3" data-query='<?php echo json_encode($dataQuery); ?>'></div>

                <div id="term-section-loading" class="section-loading"><div class="loader"></div></div>
            </section>
        </div>
    </div>
</main>
<div class="clearfix"></div>

<?php get_footer(); ?>