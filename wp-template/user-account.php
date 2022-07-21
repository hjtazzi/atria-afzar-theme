<?php
/* Template Name: user-account */

if (isset($_GET['logout'])) {
    wp_logout();
}

if (is_user_logged_in()) {
    if (isset($_GET["reset-pass"]) || isset($_GET["register"]) || isset($_GET["login"])) {
        wp_redirect(site_url() . "/account");
        exit();
    }
    get_header();
    get_template_part('inc/account/account', 'profile');
    get_template_part('inc/account/account', 'footer');
} else {
    get_header();
    get_template_part('inc/account/account', 'authentication');
    get_footer();
}
