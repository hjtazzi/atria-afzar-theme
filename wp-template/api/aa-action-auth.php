<?php

if (!class_exists('aa_action_auth')) {

    class aa_action_auth
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
                '/login',
                array(
                    'methods' => 'POST',
                    'callback' => [$this, 'aa_post_loginCheck'],
                    'permission_callback' => [$permission, 'aa_permission_all_can']
                )
            );

            register_rest_route(
                $this->namespaceRoute,
                '/signon',
                array(
                    'methods' => 'POST',
                    'callback' => [$this, 'aa_post_signon'],
                    'permission_callback' => [$permission, 'aa_permission_can_read']
                )
            );

            register_rest_route(
                $this->namespaceRoute,
                '/register',
                array(
                    'methods' => 'POST',
                    'callback' => [$this, 'aa_post_register'],
                    'permission_callback' => [$permission, 'aa_permission_all_can']
                )
            );

            register_rest_route(
                $this->namespaceRoute,
                '/sendresetpass',
                array(
                    'methods' => 'POST',
                    'callback' => [$this, 'aa_post_send_reset_pass'],
                    'permission_callback' => [$permission, 'aa_permission_all_can']
                )
            );

            register_rest_route(
                $this->namespaceRoute,
                '/changepass',
                array(
                    'methods' => 'POST',
                    'callback' => [$this, 'aa_post_change_pass'],
                    'permission_callback' => [$permission, 'aa_permission_all_can']
                )
            );
        }

        public function aa_post_loginCheck($data)
        {
            $res = array();
            $error = array();
            $username = sanitize_user($data["username"]);
            $password = $data["password"];

            if (empty($username)) {
                $error[] = "نام کاربری نمیتواند خالی باشد.";
            } else {
                if (is_email($username) && !email_exists($username)) {
                    $error[] = "ایمیل وارد شده وجود ندارد.";
                }
                if (!is_email($username) && !username_exists($username)) {
                    $error[] = "نام کاربری وارد شده وجود ندارد.";
                }
            }

            if (count($error) !== 0) {
                $res = array(
                    'success' => false,
                    'error' => $error,
                );
            } else {
                $user = wp_authenticate($username, $password);
                if (is_wp_error($user)) {
                    $error[] = "رمز ورود نامعتبر است.";
                    $res = array(
                        'success' => false,
                        'error' => $error,
                    );
                } else {
                    $res = array(
                        'success' => true,
                        'checked' => true,
                    );
                }
            }

            return $res;
        }

        public function aa_post_signon($data)
        {
            $res = array();
            $error = array();
            $username = sanitize_user($data["username"]);
            $password = $data["password"];
            $remember = $data["remember"];

            $login_info = array(
                'user_login'    => $username,
                'user_password' => $password,
                'remember'      => $remember,
            );
            $verify_user = wp_signon($login_info, true);

            if (is_wp_error($verify_user)) {
                $error[] = "خطایی در ورود پیش آمد! لطفا دوباره امتحان کنید.";
                $res = array(
                    'success' => false,
                    'error' => $error,
                );
            } else {
                $res = array(
                    'success' => true,
                    'signon' => true,
                );
            }

            return $res;
        }

        public function aa_post_register($data)
        {
            $res = array();
            $error = array();

            $newUser = array(
                'username' => sanitize_user($data["username"]),
                'email' => sanitize_email($data["email"]),
                'fName' => sanitize_text_field($data["fName"]),
                'lName' => sanitize_text_field($data["lName"]),
                'phoneNumber' => (string)$data["phoneNumber"],
                'password' => (string)$data["password"],
                'remember' => (bool)$data["remember"],
            );

            if (username_exists($newUser["username"])) {
                $error[] = "نام کاربری وارد شده از قبل وجود دارد.";
            }
            if (email_exists($newUser["email"])) {
                $error[] = "پست الکترونیک وارد شده از قبل وجود دارد.";
            }

            if (count($error) !== 0) {
                $res = array(
                    'success' => false,
                    'error' => $error,
                );
            } else {
                $userID = wp_create_user($newUser["username"], $newUser["password"], $newUser["email"]);

                if (is_wp_error($userID)) {
                    $error[] = "خطایی در ثبت نام پیش آمد! لطفا دوباره امتحان کنید.";
                    $res = array(
                        'success' => false,
                        'error' => $error,
                    );
                } else {
                    update_user_option($userID, 'show_admin_bar_front', false);
                    add_user_meta($userID, 'phone_number', $newUser["phoneNumber"]);
                    add_user_meta($userID, 'avatar_url', get_template_directory_uri() . "/img/avatar-d.png");
                    add_user_meta($userID, 'gender', "not_set");
                    update_usermeta($userID, 'first_name', $newUser["fName"]);
                    update_usermeta($userID, 'last_name', $newUser["lName"]);
                    wp_update_user(array(
                        'ID' => $userID,
                        'display_name' => $newUser["fName"] . " " . $newUser["lName"],
                    ));

                    $res = array(
                        'success' => true,
                        'create_user' => true,
                        'user' => array(
                            'display_name' => $newUser["fName"] . " " . $newUser["lName"],
                            'username' => $newUser["username"],
                            'password' => $newUser["password"],
                            'remember' => $newUser["remember"],
                        ),
                    );
                }
            }

            return $res;
        }

        public function aa_post_send_reset_pass($data)
        {
            $res = array();
            $error = array();

            $usernameOrEmail = null;
            if (is_email($data["usernameOrEmail"])) {
                $usernameOrEmail = sanitize_email($data["usernameOrEmail"]);
            } else {
                $usernameOrEmail = sanitize_user($data["usernameOrEmail"]);
            }
            if (empty($usernameOrEmail)) {
                return new WP_Error(
                    "no_username",
                    "Invalid username",
                    array('status' => 404)
                );
            }

            if (is_email($usernameOrEmail)) {
                if (email_exists($usernameOrEmail)) {
                    $user = get_user_by('email', $usernameOrEmail);
                } else {
                    $error[] = "پست الکترونیک وارد شده وجود ندارد.";
                }
            } else {
                if (username_exists($usernameOrEmail)) {
                    $user = get_user_by('login', $usernameOrEmail);
                } else {
                    $error[] = "نام کاربری وارد شده وجود ندارد.";
                }
            }

            if (!empty($user) && $user == false) {
                $error[] = "کاربر پیدا نشد! لطفا دوباره امتحان کنید";
            }

            if (count($error) !== 0) {
                $res = array(
                    'success' => false,
                    'error'   => $error,
                );
            } else {
                $userID    = $user->ID;
                $userEmail = $user->user_email;
                $userLogin = $user->user_login;
                $activ_key = wp_generate_password(32, false, false);

                global $wpdb;
                $updateDB = $wpdb->update(
                    $wpdb->users,
                    array('user_activation_key' => $activ_key),
                    array('ID' => $userID),
                );

                $reset_link = site_url() . "/account?reset-pass=true&key=" . $activ_key . "&user=" . $userLogin;

                if ($updateDB === false) {
                    $error[] = "خطایی پیش آمده !";
                    $res = array(
                        'success' => false,
                        'error'   => $error,
                    );
                } else {
                    $clsInit = new aa_init([]);
                    $msg = $clsInit->aa_emailContent();
                    
                    $msg = str_replace("[msg_titile]", "کاربر " . $userLogin, $msg);
                    $msg = str_replace("[msg_content]", "برای بازیابی رمز ورود خود روی لینک زیر کلیک کنید:", $msg);
                    $msg = str_replace("[msg_link]", $reset_link, $msg);
                    $msg = str_replace("[msg_link_txt]", "بازیابی رمز ورود", $msg);
                    $msg = str_replace("[msg_site_url]", site_url(), $msg);
                    $msg = str_replace("[msg_site_name]", get_bloginfo('name'), $msg);

                    $successfullySend = wp_mail($userEmail, "بازیابی رمز ورود", $msg);

                    if (!$successfullySend) {
                        $error[] = "خطایی در ارسال ایمیل پیش آمده!";
                        $res = array(
                            'success' => false,
                            'send'    => false,
                            'error'   => $error,
                        );
                    } else {
                        $res = array(
                            'success' => true,
                            'send'    => true,
                        );
                    }
                }
            }

            return $res;
        }

        public function aa_post_change_pass($data)
        {
            $res = array();
            $error = array();

            $password = (string)$data["password"];
            $activKey = (string)$data["key"];
            $username = (string)$data["user"];
            $getUser = get_user_by('login', $username);

            if ($getUser === false) {
                $error[] = "کاربر یافت نشد! لطفا دوباره امتحان کنید.";
                $res = array(
                    'success' => false,
                    'error'   => $error,
                );
            } else {
                global $wpdb;
                $userID = $getUser->ID;
                $getActivKey = $wpdb->get_row("SELECT user_activation_key FROM $wpdb->users WHERE ID = $userID");

                if (empty($getActivKey->user_activation_key)) {
                    $error[] = "خطایی پیش آمده! لطفا عملیات تغییر رمز را دوباره انجام دهید.";
                    $res = array(
                        'success' => false,
                        'error'   => $error,
                    );
                } else {
                    $DBActivKey = $getActivKey->user_activation_key;

                    if (strcmp($DBActivKey, $activKey) !== 0) {
                        $error[] = "پارامترهای ارسالی نامعتبر است! لطفا عملیات تغییر رمز را دوباره انجام دهید.";
                        $res = array(
                            'success' => false,
                            'error'   => $error,
                        );
                    } else {
                        $hash = wp_hash_password($password);
                        $wpdb->update(
                            $wpdb->users,
                            array(
                                'user_pass'           => $hash,
                                'user_activation_key' => '',
                            ),
                            array('ID' => $userID)
                        );
                        clean_user_cache($userID);

                        $res = array(
                            'success' => true,
                            'change_pass' => true
                        );
                    }
                }
            }

            return $res;
        }
    }
}
