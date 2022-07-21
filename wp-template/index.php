<?php get_header(); ?>

<!-- Main -->
<main id="content">
    <div id="home-about" class="home-about container">
        <?php
        get_template_part('inc/home/home', 'first-about');
        get_template_part('inc/home/home', 'services');
        ?>
    </div>
    <div class="clearfix space"></div>

    <?php
    get_template_part('inc/home/home', 'products');
    get_template_part('inc/home/home', 'posts');
    ?>

</main>

<?php get_footer(); ?>