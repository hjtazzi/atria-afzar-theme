<?php

if (!class_exists('aa_action_sidebar')) {

    class aa_action_sidebar
    {
        public $namespaceRoute = "";
        private $user_id = null;

        public function __construct($namespace, $version, $user_id)
        {
            $this->namespaceRoute = $namespace . "/" . $version;
            $this->user_id = $user_id;

            add_action('rest_api_init', [$this, 'aa_register_routes']);
        }

        public function aa_register_routes()
        {
            $permission = new aa_api_permission($this->user_id);

            register_rest_route(
                $this->namespaceRoute,
                '/sidebar',
                array(
                    'methods' => 'GET',
                    'callback' => [$this, 'aa_get_sidebar_items'],
                    'permission_callback' => [$permission, 'aa_permission_all_can']
                )
            );
        }

        public function aa_get_sidebar_items($data)
        {
            $res = array(
                'search' => array(),
                'category' => array(),
                'posts' => array(),
            );

            # search
            $resSearch = array();

            $searchArgs = array(
                'post_type' => ['post', 'products'],
                'posts_per_page' => -1,
            );
            $searchQurey = new WP_Query($searchArgs);

            if (!$searchQurey->have_posts()) {
                $resSearch = false;
            } else {
                while ($searchQurey->have_posts()) {
                    $searchQurey->the_post();
                    $postID = (int)get_the_ID();
                    $item = array(
                        'postID' => $postID,
                        'post_type' => get_post_type(),
                        'title' => the_title('', '', false),
                        'permalink' => get_permalink($postID),
                    );

                    $resSearch[] = $item;
                }
                wp_reset_postdata();
            }

            $res['search'] = $resSearch;

            # categoey
            $clsInit = new aa_init(null);
            $resCategories = $clsInit->aa_getCategories();

            $res['category'] = $resCategories;

            # posts
            $resPosts = array();

            $postsArgs = array(
                'post_type' => ['post'],
                'posts_per_page' => 5,
            );
            $postsQurey = new WP_Query($postsArgs);

            if (!$searchQurey->have_posts()) {
                $resPosts = false;
            } else {
                while ($postsQurey->have_posts()) {
                    $postsQurey->the_post();
                    $postID = (int)get_the_ID();
                    $item = array(
                        'postID' => $postID,
                        'post_type' => get_post_type(),
                        'title' => the_title('', '', false),
                        'permalink' => get_permalink($postID),
                    );

                    $resPosts[] = $item;
                }
                wp_reset_postdata();
            }

            $res['posts'] = $resPosts;

            return new WP_REST_Response($res, 200);
        }
    }
}
