<?php

if (!class_exists('aa_action_search')) {

    class aa_action_search
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
                '/search',
                array(
                    'methods' => 'GET',
                    'callback' => [$this, 'aa_get_search_items'],
                    'permission_callback' => [$permission, 'aa_permission_all_can']
                )
            );
        }

        public function aa_get_search_items($data)
        {
            $resSearch = array();

            $searchArgs = array(
                'post_type' => ['post', 'products'],
                'posts_per_page' => -1,
            );
            $searchQurey = new WP_Query($searchArgs);

            if (!$searchQurey->have_posts()) {
                return new WP_Error(
                    "no_posts",
                    "Invalid search text",
                    array('status' => 404)
                );
            }

            while ($searchQurey->have_posts()) {
                $searchQurey->the_post();
                $postID = (int)get_the_ID();

                $item = array(
                    'postID' => $postID,
                    'post_type' => get_post_type(),
                    'title' => the_title('', '', false),
                    'permalink' => get_permalink($postID),
                    'thumbnail_url' => get_the_post_thumbnail_url(),
                    'excerpt' => get_the_excerpt($postID),
                );

                if ($item['post_type'] == "products") {
                    $price = (int)get_post_meta($postID, "pr_price", true);
                    $discount = (int)get_post_meta($postID, "pr_discount", true);

                    if ($price > 0 && $discount > 0) {
                        $d = 100 - $discount;
                        $tPrice = ($price * $d) / 100;
                    } else {
                        $tPrice = $price;
                    }

                    $item['price'] = (int)$tPrice;
                    $item['discount'] = (int)$discount;
                    $item['selling']  = (int)get_post_meta($postID, "pr_selling", true);
                }
                $resSearch[] = $item;
            }

            return new WP_REST_Response($resSearch, 200);
        }
    }
}
