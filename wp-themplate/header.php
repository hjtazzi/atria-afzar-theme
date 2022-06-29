<!DOCTYPE html>
<html lang="fa_IR" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#fdfdff">

    <title><?php
            if (is_home()) { echo get_bloginfo('name') . " - صفحه اصلی"; }
            else { wp_title(); }
    ?></title>

    <meta name="description" content="<?php
                                        if (is_home()) { echo get_bloginfo('description'); }
                                        else { echo get_the_excerpt(); }
    ?>">

    <?php
    $keywords = array();

    if (is_home()) {
        $keywords[] = get_bloginfo('name');
    } else {
        $tags = get_the_tags();
        if ($tags) { foreach($tags as $tag) { $keywords[] = $tag->name; } }
    }
    ?>
    <meta name="keywords" content="<?php
                                    for ($i=0; $i<count($keywords); $i++) { 
                                        echo $keywords[$i];
                                        if ( $i+1 != count($keywords) ) { echo ","; }
                                    }
    ?>">

    <script>localStorage.setItem("site_url", "<?php echo site_url(); ?>");</script>
    <script defer="defer" src="<?php echo get_template_directory_uri(); ?>/js/main.9cb8e1fb.js"></script>
    <?php wp_head(); ?>
</head>

<body>
    <noscript id="noscript" class="error">
        <div>
            <i class="icons-warning_amber"></i>
            <p class="title">این وبسایت قابل نمایش روی دستگاه شما نیست!</p>
        </div>
    </noscript>

    <div id="loading" class="loading">
        <div class="loader"></div>
    </div>

    <!-- Header -->
    <header id="header" class="header">
        <div class="main-header">
            <div class="first-header">
                <button id="show-nev-menu" class="btn"><i class="icons-menu"></i></button>
                <a href="<?php echo site_url(); ?>">
                    <img src="./img/logo.png"
                        alt="<?php echo get_bloginfo('name'); ?>"
                        title="<?php echo "صفحه اصلی " . get_bloginfo('name'); ?>">
                </a>
            </div>
            <div class="nav-header">
                <nav id="nav-header-menu" class="nav-hidden">
                    <?php
                    wp_nav_menu(array(
                        'theme_location' => "header-menu",
                        'menu_id'        => "",
                        'container'      => "ul",
                    ));
                    ?>
                </nav>
            </div>
            <div class="profile-header">
                <a href="<?php echo site_url() . "/account" ?>" id="profile-btn" class="btn">
                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" preserveAspectRatio="xMidYMid" viewBox="0 0 50 50">
                        <linearGradient id="profile-btn-gra" x1="0%" x2="100%" y1="0%" y2="100%">
                            <stop stop-color="#60f" offset="0%">
                                <animate attributeName="stop-color" values="#60f; #f3c; #60f" dur="8s" repeatCount="indefinite"></animate>
                            </stop>
                            <stop stop-color="#f3c" offset="100%">
                                <animate attributeName="stop-color" values="#f3c; #60f; #f3c" dur="8s" repeatCount="indefinite"></animate>
                            </stop>
                        </linearGradient>
                        <g>
                            <circle stroke="url(#profile-btn-gra)" r="23" cx="25" cy="25" />
                            <circle stroke="url(#profile-btn-gra)" r="9" cx="25" cy="18" />
                            <path stroke="url(#profile-btn-gra)" d="M 12 36 Q 25 22 38 36" />
                        </g>
                    </svg>
                </a>
                <div class="profile-menu">
                    <ul>
                    <?php
                        if (is_user_logged_in()) {
                            ?><li><a href="<?php echo site_url() . "/account?logout=true" ?>" id="btn-logout">خروج</a></li><?php
                        }
                        if (!is_user_logged_in()) {
                            ?><li><a href="<?php echo site_url() . "/account" ?>">ورود</a></li><?php
                        }
                    ?>
                    </ul>
                </div>
            </div>
        </div>
        <div class="after-header">
            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="280" height="50" preserveAspectRatio="xMidYMid" viewBox="0 0 280 50">
                <use x="0" y="0" xlink:href="#svg-live-bg"></use>
            </svg>
        </div>
    </header>
    <div class="clearfix"></div>