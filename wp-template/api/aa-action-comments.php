<?php

if (!class_exists('aa_action_comments')) {

    class aa_action_comments
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
                '/comments/(?P<post_id>\d+)',
                array(
                    'methods' => 'GET',
                    'callback' => [$this, 'aa_get_comments_items'],
                    'permission_callback' => [$permission, 'aa_permission_all_can']
                )
            );

            register_rest_route(
                $this->namespaceRoute,
                '/comments',
                array(
                    'methods' => 'POST',
                    'callback' => [$this, 'aa_post_new_comment'],
                    'permission_callback' => [$permission, 'aa_permission_can_read']
                )
            );
        }

        public function aa_get_comments_items($data)
        {
            global $wpdb;
            $postID = $data["post_id"];
            $res = array();

            $commentsID = $wpdb->get_col("SELECT comment_ID FROM $wpdb->comments WHERE comment_post_ID = '$postID' AND comment_approved = '1' AND comment_type = 'comment'");
            foreach ($commentsID as $i => $id) {
                $getComment = get_comment($id);
                $authorID = (int)$getComment->user_id;
                $getAuthor = get_user_by('id', $authorID);

                $authorMeta = array(
                    'display_name' => (string)$getAuthor->display_name,
                    'role' => (string)$getAuthor->roles[0],
                    'avatar_url' => (string)get_user_meta($authorID, "avatar_url", true),
                    'page_url' => (string)site_url() . "/author/" . $getAuthor->user_login,
                );
                $item = array(
                    'id' => (int)$getComment->comment_ID,
                    'post' => (int)$getComment->comment_post_ID,
                    'parent' => (int)$getComment->comment_parent,
                    'author_id' => $authorID,
                    'content' => (string)$getComment->comment_content,
                    'date' => (string)$getComment->comment_date,
                    'date_gmt' => (string)$getComment->comment_date_gmt,
                    'author_meta' => (array)$authorMeta
                );
                $res[] = $item;
            }

            usort($res, function ($a, $b) {
                return $b['date_gmt'] <=> $a['date_gmt'];
            });
            return $res;
        }

        public function aa_post_new_comment($data)
        {
            $author = (int)$data["author"];
            $user = get_userdata($author);
            $clsInit = new aa_init(null);
            $ip = $clsInit->aa_getUserIpAddr();
            $res = array(
                'author' => $author,
                'author_email' => (string)$user->user_email,
                'author_ip' => (string)$ip,
                'author_name' => (string)$user->display_name,
                'author_url' => (string)site_url() . "/author/" . $user->user_login,
                'author_user_agent' => (string)$_SERVER['HTTP_USER_AGENT'],
                'content' => (string)sanitize_textarea_field($data["content"]),
                'date' => (string)current_time('mysql', false),
                'date_gmt' => (string)current_time('mysql', true),
                'parent' => (int)$data["parent"],
                'post' => (int)$data["post"],
                'meta' => [],
            );

            return $res;
        }
    }
}
