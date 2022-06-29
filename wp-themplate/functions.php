<?php

function loggoutUser()
{
    wp_redirect(site_url());
    exit();
}
add_action('wp_logout', 'loggoutUser');

add_filter('login_errors', function () {
    return null;
});

function enqueue_styles_and_scripts()
{
    wp_enqueue_style('style', get_stylesheet_uri());
    wp_dequeue_style('wp-block-library');
    wp_dequeue_style('global-styles');

    wp_enqueue_script('swiper',    get_template_directory_uri() . '/js/swiper-bundle.min.js', array(), '', true);
    wp_enqueue_script('scripts',   get_template_directory_uri() . '/js/scripts.js', array(), '', true);
}
add_action('wp_enqueue_scripts', 'enqueue_styles_and_scripts');
remove_action('wp_head', 'print_emoji_detection_script', 7);
remove_action('wp_print_styles', 'print_emoji_styles');

function setup_theme()
{
    register_nav_menus(
        array(
            'header-menu' => 'هدر',
            'quick-access-menu' => 'دسترسی سریع',
            'about-menu' => 'درباره ما',
        )
    );

    add_theme_support('post-thumbnails');

    add_post_type_support('page', array(
        'excerpt',
    ));

    register_post_type(
        'products',
        [
            'label'  => 'محصولات',
            'labels' => [
                'all_items'    => 'همه محصولات',
                'add_new'      => 'افزودن محصول',
                'add_new_item' => 'افزودن محصول جدید',
                'edit_item'    => 'ویرایش محصولات',
            ],
            'supports' => [
                'title',
                'editor',
                'author',
                'excerpt',
                'thumbnail',
                'comments'
            ],
            'public'       => true,
            'show_in_rest' => true,
            'menu_icon'    => 'dashicons-store'
        ]
    );
}
add_action('after_setup_theme', 'setup_theme');

function init_theme()
{
    register_taxonomy_for_object_type('post_tag', 'page');
    register_taxonomy_for_object_type('category', 'products');
    register_taxonomy_for_object_type('post_tag', 'products');
}
add_action('init', 'init_theme');

function email_filter($args)
{
    add_filter('wp_mail_content_type', function () {
        return "text/html";
    });
}
add_filter('wp_mail', 'email_filter', 10, 1);

/** Require Classes **/
require_once(__DIR__ . '/classes/jdf.php');
require_once(__DIR__ . '/classes/class-aa-init.php');
require_once(__DIR__ . '/classes/class-aa-meta-boxes.php');
require_once(__DIR__ . '/classes/class-aa-have-posts.php');
require_once(__DIR__ . '/api/aa-api-permission.php');
require_once(__DIR__ . '/api/aa-action-term.php');
require_once(__DIR__ . '/api/aa-action-sidebar.php');
require_once(__DIR__ . '/api/aa-action-search.php');
require_once(__DIR__ . '/api/aa-action-comments.php');
require_once(__DIR__ . '/api/aa-action-auth.php');
require_once(__DIR__ . '/api/aa-action-admin.php');
require_once(__DIR__ . '/api/aa-action-payments.php');


$clsInit = new aa_init(null);
/* Create Tables */
add_action('after_setup_theme', [$clsInit, 'aa_createTables']);

/* Insert Pages */
$clsInit->aa_insertPages(array(
    ['post_name' => "blog",     'post_title' => "بلاگ",          'post_template' => "blog.php"],
    ['post_name' => "products", 'post_title' => "محصولات",       'post_template' => "products.php"],
    ['post_name' => "preview",  'post_title' => "پیش نمایش",    'post_template' => "preview.php"],
    ['post_name' => "account",  'post_title' => "حساب کاربری",  'post_template' => "user-account.php"],
    ['post_name' => "payments", 'post_title' => "تأیید پرداخت", 'post_template' => "payments.php"],
));

/* Meta Boxes */
$themeMetaBoxes = array(
    array(
        'id'     => "pr_price",
        'title'  => "قیمت",
        'screen' => "products",
        'type'   => "number",
        'params' => [
            'description' => "",
            'label' => "تومان",
            'min'   => 0,
            'max'   => 50000000,
            'step'  => 1000
        ]
    ),
    array(
        'id'     => "pr_discount",
        'title'  => "تخفیف",
        'screen' => "products",
        'type'   => "number",
        'params' => [
            'description' => "",
            'label' => "%",
            'min'   => 0,
            'max'   => 99,
            'step'  => 1
        ]
    ),
    array(
        'id'     => "dl_url",
        'title'  => "آدرس صفحه دانلود",
        'screen' => "products",
        'type'   => "url",
        'params' => [
            'description' => "",
            'placeholder' => "dl_url"
        ],
    ),
    array(
        'id'     => "demo_url",
        'title'  => "آدرس صفحه دمو",
        'screen' => "products",
        'type'   => "url",
        'params' => [
            'description' => "",
            'placeholder' => "demo_url"
        ],
    ),
    array(
        'id'     => "demo_video",
        'title'  => "معرفی محصول",
        'screen' => "products",
        'type'   => "url",
        'params' => [
            'description' => "آدرس ویدیو معرفی محصول",
            'placeholder' => "demo_video"
        ],
    ),
    array(
        'id'     => "isPreview",
        'title'  => "پیش نمایش",
        'screen' => "products",
        'type'   => "check",
        'params' => [
            'description' => "",
            'label' => "دارد"
        ],
    ),
);
$themeMetaBoxesDisabled = array(
    array(
        'id'     => "pr_selling",
        'title'  => "فروش",
        'screen' => "products",
        'type'   => "number",
        'params' => [
            'description' => "تعداد فروش این محصول",
            'label' => "",
            'min'   => "",
            'max'   => "",
            'step'  => "",
            'disabled' => true,
        ]
    ),
    array(
        'id'     => "pr_views",
        'title'  => "بازدید",
        'screen' => "products",
        'type'   => "number",
        'params' => [
            'description' => "تعداد نمایش این محصول",
            'label' => "",
            'min'   => "",
            'max'   => "",
            'step'  => "",
            'disabled' => true,
        ]
    ),
    array(
        'id'     => "post_views",
        'title'  => "بازدید",
        'screen' => "post",
        'type'   => "number",
        'params' => [
            'description' => "تعداد نمایش این نوشته",
            'label' => "",
            'min'   => "",
            'max'   => "",
            'step'  => "",
            'disabled' => true,
        ]
    ),
);
$productsSliderMetaBoxes = array();
for ($i = 0; $i <= 5; $i++) {
    $productsSliderMetaBoxes[] = array(
        'id'     => "demo_slider_" . $i,
        'title'  => "اسلاید" . $i,
        'screen' => "products",
        'type'   => "url",
        'params' => [
            'description' => "",
            'placeholder' => "demo_slider_" . $i
        ],
    );
}
$clsMetaBoxes = new aa_meta_boxes(array_merge($themeMetaBoxes, $productsSliderMetaBoxes), $themeMetaBoxesDisabled);

/* Products Custom Column */
function manage_pr_columns($columns)
{
    $columns['pr_price']    = 'قیمت';
    $columns['pr_discount'] = 'تخفیف';
    $columns['pr_selling']  = 'فروش';
    $columns['pr_views']    = 'بازدید';
    return $columns;
}
function manage_pr_custom_columns($columns, $post_id)
{
    switch ($columns) {
        case "pr_price":
            $column = (int)get_post_meta($post_id, 'pr_price', true);
            if (empty($column)) {
                $column = 0;
            }
            echo "<p>" . number_format($column) . " <small>تومان</small></p>";
            break;
        case "pr_discount":
            $column = (int)get_post_meta($post_id, 'pr_discount', true);
            if (empty($column)) {
                $column = 0;
            }
            echo "<p>" . $column . "%</p>";
            break;
        case "pr_selling":
            $column = (int)get_post_meta($post_id, 'pr_selling', true);
            if (empty($column)) {
                $column = 0;
            }
            echo "<p>" . $column . "</p>";
            break;
        case "pr_views":
            $column = (int)get_post_meta($post_id, 'pr_views', true);
            if (empty($column)) {
                $column = 0;
            }
            echo "<p>" . $column . "</p>";
            break;
        default:
            echo "";
    }
}
add_filter('manage_products_posts_columns', 'manage_pr_columns');
add_action('manage_products_posts_custom_column', 'manage_pr_custom_columns', 10, 2);

/* APIs */
$currentUserID = get_current_user_id();
$namespaceAPI = "aatheme";
$versionAPI = "v1";

$clsActionTerm = new aa_action_term($namespaceAPI, $versionAPI, $currentUserID);
$clsActionSidebar = new aa_action_sidebar($namespaceAPI, $versionAPI, $currentUserID);
$clsActionSearch = new aa_action_search($namespaceAPI, $versionAPI, $currentUserID);
$clsActionComments = new aa_action_comments($namespaceAPI, $versionAPI, $currentUserID);
$clsActionAuth = new aa_action_auth($namespaceAPI, $versionAPI, $currentUserID);
$clsActionAdmin = new aa_action_admin($namespaceAPI, $versionAPI, $currentUserID);
$clsActionPayments = new aa_action_payments($namespaceAPI, $versionAPI, $currentUserID);
