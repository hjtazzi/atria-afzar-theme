<?php

if (!class_exists('aa_action_admin')) {

    class aa_action_admin
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
                '/sidemenu',
                array(
                    'methods' => 'POST',
                    'callback' => [$this, 'aa_admin_side_menu'],
                    'permission_callback' => [$permission, 'aa_permission_can_read']
                )
            );

            register_rest_route(
                $this->namespaceRoute,
                '/editprofile',
                array(
                    array(
                        'methods' => 'GET',
                        'callback' => [$this, 'aa_admin_get_edit_profile'],
                        'permission_callback' => [$permission, 'aa_permission_can_read']
                    ),
                    array(
                        'methods' => 'PUT',
                        'callback' => [$this, 'aa_admin_put_edit_profile'],
                        'permission_callback' => [$permission, 'aa_permission_can_read']
                    )
                )
            );

            register_rest_route(
                $this->namespaceRoute,
                '/editpass',
                array(
                    'methods' => 'PUT',
                    'callback' => [$this, 'aa_admin_put_edit_password'],
                    'permission_callback' => [$permission, 'aa_permission_can_read']
                )
            );

            register_rest_route(
                $this->namespaceRoute,
                '/tickets',
                array(
                    array(
                        'methods' => 'GET',
                        'callback' => [$this, 'aa_admin_get_user_tickets'],
                        'permission_callback' => [$permission, 'aa_permission_can_read']
                    ),
                    array(
                        'methods' => 'PUT',
                        'callback' => [$this, 'aa_admin_update_status_tickets'],
                        'permission_callback' => [$permission, 'aa_permission_can_read']
                    ),
                    array(
                        'methods' => 'POST',
                        'callback' => [$this, 'aa_admin_create_user_tickets'],
                        'permission_callback' => [$permission, 'aa_permission_can_read']
                    ),
                )
            );

            register_rest_route(
                $this->namespaceRoute,
                '/received_tickets',
                array(
                    array(
                        'methods' => 'GET',
                        'callback' => [$this, 'aa_admin_get_received_tickets'],
                        'permission_callback' => [$permission, 'aa_permission_can_editor']
                    ),
                    array(
                        'methods' => 'POST',
                        'callback' => [$this, 'aa_admin_create_received_tickets'],
                        'permission_callback' => [$permission, 'aa_permission_can_editor']
                    ),
                )
            );

            register_rest_route(
                $this->namespaceRoute,
                '/author_posts/(?P<post_type>[a-zA-Z0-9-]+)',
                array(
                    'methods' => 'GET',
                    'callback' => [$this, 'aa_admin_get_author_posts'],
                    'permission_callback' => [$permission, 'aa_permission_can_edit_posts']
                )
            );

            register_rest_route(
                $this->namespaceRoute,
                '/editusers',
                array(
                    array(
                        'methods' => 'GET',
                        'callback' => [$this, 'aa_admin_get_edit_users'],
                        'permission_callback' => [$permission, 'aa_permission_can_editor']
                    ),
                    array(
                        'methods' => 'PUT',
                        'callback' => [$this, 'aa_admin_put_edit_users'],
                        'permission_callback' => [$permission, 'aa_permission_can_editor']
                    )
                )
            );

            register_rest_route(
                $this->namespaceRoute,
                '/product/(?P<post_id>\d+)',
                array(
                    array(
                        'methods' => 'GET',
                        'callback' => [$this, 'aa_admin_get_product_root'],
                        'permission_callback' => [$permission, 'aa_permission_all_can']
                    ),
                    array(
                        'methods' => 'POST',
                        'callback' => [$this, 'aa_admin_create_cart_item'],
                        'permission_callback' => [$permission, 'aa_permission_can_read']
                    )
                )
            );

            register_rest_route(
                $this->namespaceRoute,
                '/cart',
                array(
                    array(
                        'methods' => 'GET',
                        'callback' => [$this, 'aa_admin_get_user_cart'],
                        'permission_callback' => [$permission, 'aa_permission_can_read']
                    ),
                    array(
                        'methods' => 'POST',
                        'callback' => [$this, 'aa_admin_create_cart_item'],
                        'permission_callback' => [$permission, 'aa_permission_can_read']
                    ),
                )
            );

            register_rest_route(
                $this->namespaceRoute,
                '/cart/(?P<row>\d+)',
                array(
                    array(
                        'methods' => 'DELETE',
                        'callback' => [$this, 'aa_admin_remove_cart_item'],
                        'permission_callback' => [$permission, 'aa_permission_can_read']
                    )
                )
            );

            register_rest_route(
                $this->namespaceRoute,
                '/order',
                array(
                    'methods' => 'GET',
                    'callback' => [$this, 'aa_admin_get_user_order'],
                    'permission_callback' => [$permission, 'aa_permission_can_read']
                )
            );
        }

        public function aa_admin_side_menu($data)
        {
            $res = array();

            $user_id = (int)$this->user_id;
            $user = get_user_by('id', $user_id);
            $userRole = $user->roles[0];
            $sideMenu = array();

            $userID = (int)$data["user_id"];
            $clsPermission = new aa_api_permission($user_id);
            $userCheck = $clsPermission->aa_current_user_check($userID);
            if ($userCheck !== true)
                return $userCheck;

            $subscriberArr = array(
                0 => ['state' => "dashboard", 'title' => "داشبورد", 'icon' => "icons-person"],
                1 => ['state' => "shopping_cart", 'title' => "سبد خرید", 'icon' => "icons-local_grocery_store"],
                2 => ['state' => "order", 'title' => "سفارشات", 'icon' => "icons-local_mall"],
                3 => ['state' => "tickets", 'title' => "تیکت‌ها", 'icon' => "icons-question_answer"],
                4 => ['state' => "edit_profile", 'title' => "ویرایش مشخصات", 'icon' => "icons-manage_accounts"],
            );
            $contributorArr = array(
                10 => ['state' => "posts", 'title' => "نوشته‌های من", 'icon' => "icons-chat"],
                11 => ['state' => "products", 'title' => "محصولات من", 'icon' => "icons-shopping_bag"],
            );
            $authorArr = array();
            $editorArr = array(
                30 => ['state' => "received_tickets", 'title' => "تیکت‌های دریافتی", 'icon' => "icons-chat_bubble_outline"],
                #31 => ['state' => "create_order", 'title' => "ایجاد سفارش", 'icon' => "icons-add_card"],
                32 => ['state' => "edit_users", 'title' => "ویرایش کاربران", 'icon' => "icons-people_outline"],
                33 => ['state' => "store", 'title' => "فروشگاه", 'icon' => "icons-storefront"],
            );
            $administratorArr = array();

            switch ($userRole) {
                case 'subscriber':
                    $sideMenu = $subscriberArr;
                    break;
                case 'contributor':
                    $sideMenu = array_merge($subscriberArr, $contributorArr);
                    break;
                case 'author':
                    $sideMenu = array_merge($subscriberArr, $contributorArr, $authorArr);
                    break;
                case 'editor':
                    $sideMenu = array_merge($subscriberArr, $contributorArr, $authorArr, $editorArr);
                    break;
                case 'administrator':
                    $sideMenu = array_merge($subscriberArr, $contributorArr, $authorArr, $editorArr, $administratorArr);
                    break;
                default:
                    $sideMenu = array(
                        0 => ['state' => "dashboard", 'title' => "داشبورد", 'icon' => "icons-person"],
                    );
                    break;
            }

            $res = array(
                'user' => array(
                    'display_name' => (string)$user->display_name,
                    'avatar_url' => (string)get_user_meta($user_id, "avatar_url", true),
                ),
                'side_menu' => $sideMenu,
            );

            return $res;
        }

        public function aa_admin_get_edit_profile($data)
        {
            $res = array();

            $user_id = (int)$this->user_id;
            $user = get_user_by('id', $user_id);

            $userRes = array(
                'user_login' => (string)$user->user_login,
                'user_email' => (string)$user->user_email,
                'first_name' => (string)get_user_meta($user_id, "first_name", true),
                'last_name' => (string)get_user_meta($user_id, "last_name", true),
                'phone_number' => (string)get_user_meta($user_id, "phone_number", true),
                'user_gender' => (string)get_user_meta($user_id, "gender", true),
                'description' => (string)get_user_meta($user_id, "description", true),
            );

            $res = array(
                'success' => true,
                'user' => $userRes,
            );

            return $res;
        }

        public function aa_admin_put_edit_profile($data)
        {
            $res = array();
            $error = array();

            $user_id = (int)$this->user_id;
            $user = get_user_by('id', $user_id);

            $email = sanitize_email($data["email"]);
            $fName = sanitize_text_field($data["fName"]);
            $lName = sanitize_text_field($data["lName"]);
            $dName = $fName . " " . $lName;
            $gender = sanitize_text_field($data["gender"]);
            $description = sanitize_textarea_field($data["description"]);
            $phoneNumber = (string)$data["phoneNumber"];

            $avatarC = (string)get_user_meta($user_id, "avatar_url", true);
            $avatarD = get_template_directory_uri() . "/img/avatar-d.png";
            $avatarM = get_template_directory_uri() . "/img/avatar-b.png";
            $avatarF = get_template_directory_uri() . "/img/avatar-g.png";

            update_user_meta($user_id, "first_name", $fName);
            update_user_meta($user_id, "last_name", $lName);
            update_user_meta($user_id, "gender", $gender);
            update_user_meta($user_id, "description", $description);
            update_user_meta($user_id, "phone_number", $phoneNumber);

            if (strcmp($avatarC, $avatarD) === 0 || strcmp($avatarC, $avatarM) === 0 || strcmp($avatarC, $avatarF) === 0) {
                if ($gender === "male") {
                    update_user_meta($user_id, "avatar_url", $avatarM);
                } elseif ($gender === "female") {
                    update_user_meta($user_id, "avatar_url", $avatarF);
                } else {
                    update_user_meta($user_id, "avatar_url", $avatarD);
                }
            }

            if ($email !== $user->user_email) {
                if (email_exists($email)) {
                    $error[] = "پست الکترونیک وارد شده از قبل وجود دارد.";
                } else {
                    wp_update_user(array(
                        'ID' => $user_id,
                        'user_email' => $email,
                    ));
                }
            }
            wp_update_user(array(
                'ID' => $user_id,
                'display_name' => $dName,
            ));

            if (count($error) !== 0) {
                $res = array(
                    'success' => false,
                    'error' => $error
                );
            } else {
                $res = array(
                    'success' => true
                );
            }
            return $res;
        }

        public function aa_admin_put_edit_password($data)
        {
            $res = array();
            $error = array();

            $user_id = (int)$this->user_id;
            $user = get_user_by('id', $user_id);

            $currentPassword = (string)$data["currentPassword"];
            $newPassword = (string)$data["password"];
            $currentPasswordHash = $user->user_pass;

            $checkPassword = wp_check_password(
                $currentPassword,
                $currentPasswordHash,
                $user_id
            );
            if (!$checkPassword) {
                $error[] = "رمز ورود فعلی نامعتبر است.";
            }

            if (count($error) !== 0) {
                $res = array(
                    'success' => false,
                    'error' => $error,
                );
            } else {
                global $wpdb;
                $hashNewPassword = wp_hash_password($newPassword);

                $changePass = $wpdb->update(
                    $wpdb->users,
                    array(
                        'user_pass'           => $hashNewPassword,
                        'user_activation_key' => '',
                    ),
                    array('ID' => $user_id)
                );

                if ($changePass === false) {
                    $error[] = "خطایی در ویرایش رمز عبور پیش آمد!";
                    $res = array(
                        'success' => false,
                        'error' => $error,
                    );
                } else {
                    clean_user_cache($user_id);
                    $res = array(
                        'success' => true,
                        'change_pass' => true
                    );
                }
            }

            return $res;
        }

        public function aa_admin_get_user_tickets($data)
        {
            $res = array();

            global $wpdb;
            $user_id = (int)$this->user_id;
            $table = $wpdb->prefix . "aa_tickets";
            $clsInit = new aa_init(null);

            $userTicketsID = $wpdb->get_col("SELECT id FROM $table WHERE user = $user_id");
            for ($i = 0; $i < count($userTicketsID); $i++) {
                $id = $userTicketsID[$i];
                $ticket = $wpdb->get_row("SELECT * FROM $table WHERE id = $id");
                $ticket->date = $clsInit->aa_shamsiDate($ticket->date, "H:i");
                $res[] = $ticket;
            }

            usort($res, function ($a, $b) {
                return $a->id <=> $b->id;
            });

            return $res;
        }

        public function aa_admin_update_status_tickets($data)
        {
            global $wpdb;
            $table = $wpdb->prefix . "aa_tickets";
            $rowID = (int)$data["row_id"];

            $wpdb->update(
                $table,
                array('status' => 1),
                array('id' => $rowID)
            );

            return;
        }

        public function aa_admin_create_user_tickets($data)
        {
            $res = array();
            $error = array();

            global $wpdb;
            $table = $wpdb->prefix . "aa_tickets";

            $newTicket = array(
                'user' => (int)$this->user_id,
                'role' => 0,
                'status' => 0,
                'content' => sanitize_textarea_field($data["newTicket"]),
                'date' => current_time("mysql", true),
            );

            $insertTicket = $wpdb->insert(
                $table,
                $newTicket
            );

            if ($insertTicket === false) {
                $error[] = "خطایی در ارسال تیکت شما پیش آمد! لطفا دوباره امتحان کنید.";
                $res = array(
                    'success' => false,
                    'error' => $error,
                );
            } else {
                $res = array(
                    'success' => true,
                );
            }

            return $res;
        }

        public function aa_admin_get_received_tickets($data)
        {
            $res = array();

            global $wpdb;
            $table = $wpdb->prefix . "aa_tickets";
            $clsInit = new aa_init(null);

            $users = $wpdb->get_col("SELECT id FROM $wpdb->users WHERE id > 1");

            for ($i = 0; $i < count($users); $i++) {
                $userID = $users[$i];

                $userTickets = $wpdb->get_results("SELECT * FROM $table WHERE user = $userID");

                for ($j = 0; $j < count($userTickets); $j++) {
                    $userTickets[$j]->date = $clsInit->aa_shamsiDate($userTickets[$j]->date);
                }

                if (!empty($userTickets)) {
                    $user = get_user_by("id", (int)$userID);
                    $res[] = array(
                        'user' => array(
                            'id' => (int)$userID,
                            'username' => (string)$user->user_login,
                            'display_name' => (string)$user->display_name,
                            'avatar_url' => (string)get_user_meta((int)$userID, "avatar_url", true),
                        ),
                        'tickets' => $userTickets,
                    );
                }
            }

            return $res;
        }

        public function aa_admin_create_received_tickets($data)
        {
            $res = array();
            $error = array();

            global $wpdb;
            $table = $wpdb->prefix . "aa_tickets";

            $newTicket = array(
                'user' => (int)$data["id"],
                'role' => 1,
                'status' => 0,
                'content' => sanitize_textarea_field($data["newTicket"]),
                'date' => current_time("mysql", true),
            );

            $insertTicket = $wpdb->insert(
                $table,
                $newTicket
            );

            if ($insertTicket === false) {
                $error[] = "خطایی در ارسال تیکت شما پیش آمد! لطفا دوباره امتحان کنید.";
                $res = array(
                    'success' => false,
                    'error' => $error,
                );
            } else {
                $res = array(
                    'success' => true,
                );
            }

            return $res;
        }

        public function aa_admin_get_author_posts($data)
        {
            $res = array();
            $posts = array();
            $clsInit = new aa_init(null);

            $post_type = $data["post_type"];

            $args = array(
                'post_type' => $post_type,
                'author' => (int)$this->user_id,
                'posts_per_page' => -1,
            );

            $query = new WP_Query($args);

            while ($query->have_posts()) {
                $query->the_post();

                $postID   = (int)get_the_ID();
                $getPost = get_post($postID, "ARRAY_A");
                unset($getPost["ancestors"]);
                unset($getPost["filter"]);
                unset($getPost["guid"]);
                unset($getPost["menu_order"]);
                unset($getPost["page_template"]);
                unset($getPost["ping_status"]);
                unset($getPost["pinged"]);
                unset($getPost["post_content"]);
                unset($getPost["post_content_filtered"]);
                unset($getPost["post_mime_type"]);
                unset($getPost["post_parent"]);
                unset($getPost["post_password"]);
                unset($getPost["to_ping"]);

                $postCats = $getPost["post_category"];
                $cats = array();
                foreach ($postCats as $cat) {
                    $cat = get_term_by('id', $cat, 'category', 'ARRAY_A');
                    $cats[] = array(
                        'cat_name' => $cat["name"],
                        'cat_slug' => $cat["slug"],
                    );
                }

                $postTags = get_the_tags();
                $keywords = array();
                if ($postTags) {
                    foreach ($postTags as $tag) {
                        $keywords[] = array(
                            'tag_name' => $tag->name,
                            'tag_slug' => $tag->slug,
                        );
                    }
                }

                $getPostVar = array(
                    'post_view' => (int)get_post_meta($postID, "post_views", true),
                    'post_keywords' => $keywords,
                    'post_cats' => $cats,
                    'post_short_link' => site_url() . "?p=" . $postID,
                    'post_date' => $clsInit->aa_shamsiDate($getPost["post_date"], "H:i"),
                );

                $getProductVar = array();
                if ($post_type == "products") {
                    $getProductVar = array(
                        'post_view' => (int)get_post_meta($postID, "pr_views", true),
                        'post_price' => (int)get_post_meta($postID, 'pr_price', true),
                        'post_discount' => (int)get_post_meta($postID, 'pr_discount', true),
                        'post_selling' => (int)get_post_meta($postID, "pr_selling", true),
                    );
                }

                $postVar = array_merge($getPost, $getPostVar, $getProductVar);

                $posts[] = $postVar;
            }
            wp_reset_postdata();

            $res = array(
                'posts' => $posts,
                'have_posts'  => $query->have_posts(),
                'found_posts' => $query->found_posts
            );

            return $res;
        }

        public function aa_admin_get_edit_users($data)
        {
            $res = array();

            global $wpdb;

            $users = $wpdb->get_col("SELECT id FROM $wpdb->users WHERE id > 1");

            for ($i = 0; $i < count($users); $i++) {
                $userID = (int)$users[$i];
                $user = get_user_by('id', $userID);

                $item = array(
                    'id' => (int)$userID,
                    'username' => (string)$user->user_login,
                    'display_name' => (string)$user->display_name,
                    'user_login' => (string)$user->user_login,
                    'user_email' => (string)$user->user_email,
                    'first_name' => (string)get_user_meta($userID, "first_name", true),
                    'last_name' => (string)get_user_meta($userID, "last_name", true),
                    'phone_number' => (string)get_user_meta($userID, "phone_number", true),
                    'avatar_url' => (string)get_user_meta($userID, "avatar_url", true),
                );

                $res[] = $item;
            }

            return $res;
        }

        public function aa_admin_put_edit_users($data)
        {
            $res = array();
            $error = array();

            $userID = (int)$data["user_id"];
            $user = get_user_by('id', $userID);

            $email = sanitize_email($data["email"]);
            $fName = sanitize_text_field($data["fName"]);
            $lName = sanitize_text_field($data["lName"]);
            $dName = $fName . " " . $lName;
            $phoneNumber = (string)$data["phoneNumber"];
            $avatarUrl = (string)$data["avatar_url"];

            update_user_meta($userID, "first_name", $fName);
            update_user_meta($userID, "last_name", $lName);
            update_user_meta($userID, "phone_number", $phoneNumber);
            update_user_meta($userID, "avatar_url", $avatarUrl);

            if ($email !== $user->user_email) {
                if (email_exists($email)) {
                    $error[] = "پست الکترونیک وارد شده از قبل وجود دارد.";
                    $error[] = $email;
                    $error[] = $user->user_email;
                } else {
                    wp_update_user(array(
                        'ID' => $userID,
                        'user_email' => $email,
                    ));
                }
            }

            wp_update_user(array(
                'ID' => $userID,
                'display_name' => $dName,
            ));

            if (count($error) !== 0) {
                $res = array(
                    'success' => false,
                    'error' => $error
                );
            } else {
                $res = array(
                    'success' => true
                );
            }

            return $res;
        }

        public function aa_admin_get_product_root($data)
        {
            global $wpdb;
            $res = array();
            $error = array();

            $user_id = (int)$this->user_id;
            $post_id = (int)$data["post_id"];
            $shoppingTable = $wpdb->prefix . "aa_shopping";

            if ($post_id <= 0) {
                $error[] = "در حال حاظر امکان نمایش مشخصات محصول وجود ندارد.";
                $res = array(
                    'success' => false,
                    'error' => $error
                );

                return $res;
            }

            $price = (int)get_post_meta($post_id, "pr_price", true);
            $discount = (int)get_post_meta($post_id, "pr_discount", true);

            if ($price > 0 && $discount > 0) {
                $d = 100 - $discount;
                $tPrice = ($price * $d) / 100;
            } else {
                $tPrice = $price;
            }

            $productArr = array(
                'user' => $user_id,
                'id' => $post_id,
                'price' => (int)$price,
                'tPrice' => (int)$tPrice,
                'discount' => (int)$discount,
                'have_post' => true,
            );

            $userCart = $wpdb->get_row("SELECT id FROM $shoppingTable WHERE user = '$user_id' AND product = '$post_id'");
            if (!empty($userCart)) {
                $isCart = true;
            } else {
                $isCart = false;
            }
            $productArr["have_cart"] = $isCart;

            $res = array(
                'success' => true,
                'product' => $productArr,
            );

            return $res;
        }

        public function aa_admin_create_cart_item($data)
        {
            global $wpdb;
            $res = array();
            $error = array();

            $user_id = (int)$this->user_id;
            $post_id = (int)$data["post_id"];
            $shoppingTable = $wpdb->prefix . "aa_shopping";

            $createItem = $wpdb->insert(
                $shoppingTable,
                [
                    'user' => $user_id,
                    'product' => $post_id
                ]
            );

            if ($createItem === false) {
                $error[] = "خطایی در افزودن به سبد خرید پسش آمد!";
                $res = array(
                    'success' => false,
                    'error' => $error
                );
            } else {
                $res = array(
                    'success' => true,
                    'create' => true
                );
            }

            return $res;
        }

        public function aa_admin_get_user_cart($data)
        {
            global $wpdb;
            $res = array();
            $finalPrice = 0;

            $user_id = (int)$this->user_id;
            $shoppingTable = $wpdb->prefix . "aa_shopping";

            $results = $wpdb->get_results("SELECT * FROM $shoppingTable WHERE user = '$user_id'");
            for ($i = 0; $i < count($results); $i++) {
                $post_id = (int)$results[$i]->product;

                $postTitle = get_the_title($post_id);
                $postUrl = site_url() . "?p=" . $post_id;
                $thumbnail = get_the_post_thumbnail_url($post_id);
                $price = (int)get_post_meta($post_id, "pr_price", true);
                $discount = (int)get_post_meta($post_id, "pr_discount", true);

                if ($price > 0 && $discount > 0) {
                    $d = 100 - $discount;
                    $tPrice = ($price * $d) / 100;
                } else {
                    $tPrice = $price;
                }

                $productArr = array(
                    'id' => $post_id,
                    'title' => (string)$postTitle,
                    'thumbnail_url' => (string)$thumbnail,
                    'short_link' => (string)$postUrl,
                    'price' => (int)$price,
                    'tPrice' => (int)$tPrice,
                    'discount' => (int)$discount,
                );

                $results[$i]->product = $productArr;
                $finalPrice += $tPrice;
            }

            $res = array(
                'cart_items' => $results,
                'final_price' => $finalPrice
            );

            return $res;
        }

        public function aa_admin_remove_cart_item($data)
        {
            global $wpdb;
            $res = array();
            $error = array();

            $shoppingTable = $wpdb->prefix . "aa_shopping";
            $row = (int)$data["row"];

            $delete = $wpdb->delete(
                $shoppingTable,
                ['id' => $row]
            );

            if ($delete !== false) {
                $res = array(
                    'success' => true,
                    'deleted' => true
                );
            } else {
                $error[] = "خطایی در حذف محصول پسش آمد!";
                $res = array(
                    'success' => false,
                    'error' => $error
                );
            }

            return $res;
        }

        public function aa_admin_get_user_order($data)
        {
            global $wpdb;
            $results = array();
            $clsInit = new aa_init(null);

            $paymentsTable = $wpdb->prefix . "aa_payments";
            $user_id = (int)$this->user_id;

            $results = $wpdb->get_results("SELECT order_id,amount,objects,details,status_no,pay_date,track_id,payment_type FROM $paymentsTable WHERE user = '$user_id'");
            for ($i = 0; $i < count($results); $i++) {
                $results[$i]->amount = number_format((int)$results[$i]->amount / 10);

                $payDate = $results[$i]->pay_date;
                $results[$i]->pay_date = $clsInit->aa_shamsiDate($payDate, "H:i");

                $status = $results[$i]->status_no;
                $stt = "خطا رخ داده است";
                if ((int)$status == 1) {
                    $stt = 'انجام نشده است';
                } elseif ((int)$status == 2) {
                    $stt = 'ناموفق بوده است';
                } elseif ((int)$status == 7) {
                    $stt = 'انصراف از پرداخت';
                } elseif ((int)$status >= 100) {
                    $stt = "با موفقیت انجام شده";
                }

                $objects = array();
                $obj = json_decode($results[$i]->objects);
                for ($j = 0; $j < count($obj); $j++) {
                    $pr_id = (int)$obj[$j]->pr_id;

                    $price = number_format((int)$obj[$j]->price);
                    $title = get_the_title($pr_id);
                    $link  = site_url() . "?p=" . $pr_id;
                    $dl_link = null;

                    if ((int)$status >= 100) {
                        $dl_link = (string)get_post_meta($pr_id, "dl_url", true);
                    }

                    $objects[] = array(
                        'title' => $title,
                        'price' => $price,
                        'link'  => $link,
                        'dl_link' => $dl_link,
                    );
                }
                $results[$i]->objects = $objects;

                $results[$i]->status_no = $stt;
            }

            return $results;
        }
    }
}
