<?php
if (!isset($_GET["s"])) {
    wp_redirect(site_url());
    exit();
} else {
    $search = sanitize_text_field($_GET["s"]);
    if (empty($search)) {
        wp_redirect(site_url());
        exit();
    }
}
?>
<?php get_header() ?>

<!-- Main -->
<main id="content">
    <div id="search" class="search container">
        <section id="search-content" class="search-content">
            <h1 class="search-content-title"><?php wp_title(); ?></h1>

            <div id="search-content_root" class="search-content_root" data-search='<?php echo $search; ?>'></div>

            <div id="search-section-loading" class="section-loading">
                <div class="loader"></div>
            </div>
        </section>
    </div>
</main>
<?php get_footer() ?>