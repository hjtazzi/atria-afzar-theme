<?php

if (!class_exists('aa_api_permission')) {

    class aa_api_permission
    {
        private $user_id = null;

        public function __construct($user_id)
        {
            $this->user_id = $user_id;

            add_filter('jwt_auth_token_before_dispatch', [$this, 'aa_add_user_info_jwt'], 10, 2);
        }

        public function aa_add_user_info_jwt($data, $user)
        {
            $data["user_id"] = $user->ID;
            unset($data["user_email"]);
            unset($data["user_display_name"]);
            unset($data["user_nicename"]);

            return $data;
        }

        public function aa_current_user_check($userID)
        {
            $user_id = (int)$this->user_id;

            if ($userID !== $user_id) {
                return new WP_Error(
                    "permission_error",
                    "Invalid user",
                    array('status' => 403)
                );
            } else {
                return true;
            }
        }

        public function aa_permission_all_can()
        {
            return true;
        }

        public function aa_permission_can_read()
        {
            return current_user_can("read");
        }

        public function aa_permission_can_editor()
        {
            return current_user_can("level_7");
        }

        public function aa_permission_can_edit_posts()
        {
            return current_user_can("edit_posts");
        }
    }
}
