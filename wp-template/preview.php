<?php
/* Template Name: preview */

if (isset($_GET["pid"])) {
    $previewID = (int)$_GET["pid"];

    $postTitle = get_the_title($previewID);
    $postType  = get_post_type($previewID);

    if (strlen($postTitle) == 0 || $postType == false || $postType != "products") {
        wp_redirect(site_url() . "?p=" . $previewID);
        exit();
    }
} else {
    wp_redirect(site_url());
    exit();
}

$previewArgs = array(
    "previewID" => $previewID,
    "title" => $postTitle,
    "iframeUrl" => get_post_meta($previewID, "demo_url", true),
);
?>
<!DOCTYPE html>
<html lang="fa_IR" dir="ltr">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#fdfdff">
    <title><?php echo $previewArgs["title"] . " - پیش نمایش - " . get_bloginfo('name'); ?></title>
    <style type="text/css">
        html,
        body,
        iframe {
            width: 100%;
            height: 100%;
            border: none;
            overflow: auto;
        }
    </style>

    <?php wp_head(); ?>
</head>

<body>
    <noscript id="noscript">
        <div>
            <i class="icons-warning_amber"></i>
            <p class="title">این وبسایت قابل نمایش روی دستگاه شما نیست!</p>
        </div>
    </noscript>

    <main id="preview-main" class="preview-main">
        <iframe src="<?php echo $previewArgs["iframeUrl"]; ?>" frameborder="0" marginheight="0" marginwidth="0" width="100%" height="100%" scrolling="auto">
        </iframe>
    </main>

    <footer id="preview-footer" class="preview-footer">
        <a href="<?php echo site_url() . "?p=" . $previewID; ?>" class="btn-primary"><i class="icons-arrow_back"></i></a>
        <button class="btn-primary ch-size" data-size-w="100%" data-size-h="100%"><i class="icons-laptop_windows"></i></button>
        <button class="btn-primary ch-size" data-size-w="690px" data-size-h="920px"><i class="icons-tablet_android"></i></button>
        <button class="btn-primary ch-size" data-size-w="380px" data-size-h="780px"><i class="icons-smartphone"></i></button>
        <a href="<?php echo site_url(); ?>" class="btn-primary"><i class="icons-home"></i></a>
    </footer>

    <div id="app_root" class="root"></div>

    <?php wp_footer(); ?>
</body>

</html>