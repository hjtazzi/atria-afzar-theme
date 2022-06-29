<?php

if (!class_exists('aa_action_term')) {

    class aa_action_term
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
                '/taxonomy/(?P<taxonomy>[a-zA-Z0-9-]+)' . '/term/(?P<term_id>\d+)',
                array(
                    'methods' => 'GET',
                    'callback' => [$this, 'aa_get_term_items'],
                    'permission_callback' => [$permission, 'aa_permission_all_can']
                )
            );
        }

        public function aa_get_term_items($data)
        {
            $taxonomy = $data['taxonomy'];
            $term_id = $data['term_id'];
            $args = array(
                'post_type' => ['post', 'products'],
                'posts_per_page' => -1,
            );
            $res = array();

            switch ($taxonomy) {
                case 'tag':
                    $args['tag_id'] = $term_id;
                    break;
                case 'cat':
                    $args['cat'] = $term_id;
                    break;
                default:
                    break;
            }

            $query = new WP_Query($args);

            if (!$query->have_posts()) {
                return new WP_Error(
                    "no_posts",
                    "Invalid term id",
                    array('status' => 404)
                );
            }

            while ($query->have_posts()) {
                $query->the_post();
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
                $res[] = $item;
            }

            return new WP_REST_Response($res, 200);
        }
    }
}
