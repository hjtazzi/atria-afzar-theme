<?php

if (!class_exists('aa_action_payments')) {

    class aa_action_payments
    {
        public $namespaceRoute = "";
        private $user_id = 0;
        private $paymentHeader = array();

        public function __construct($namespace, $version, $user_id)
        {
            $this->namespaceRoute = $namespace . "/" . $version;
            $this->user_id = $user_id;

            $this->paymentHeader = array(
                'Content-Type: application/json',
                'X-API-KEY: 6a7f99eb-7c20-4412-a972-6dfb7cd253a4',
                'X-SANDBOX: 1'
            );

            if (!empty($namespace)) {
                add_action('rest_api_init', [$this, 'aa_register_routes']);
            }
        }

        public function aa_register_routes()
        {
            $permission = new aa_api_permission($this->user_id);

            register_rest_route(
                $this->namespaceRoute,
                '/payments',
                array(
                    array(
                        'methods' => 'GET',
                        'callback' => [$this, 'aa_pay_get_payments'],
                        'permission_callback' => [$permission, 'aa_permission_can_editor']
                    ),
                    array(
                        'methods' => 'POST',
                        'callback' => [$this, 'aa_pay_create_payment'],
                        'permission_callback' => [$permission, 'aa_permission_can_read']
                    ),
                )
            );
        }

        private function aa_pay_checkOrderId($order_id)
        {
            global $wpdb;
            $paymentsTable = $wpdb->prefix . "aa_payments";
            $data = $wpdb->get_col("SELECT order_id FROM $paymentsTable WHERE order_id = '$order_id'");

            if (count($data) != 0) {
                return self::aa_pay_checkOrderId(rand(1111111, 9999999));
            }

            return $order_id;
        }

        private function aa_pay_clearShoppingCart($rows)
        {
            global $wpdb;
            $shoppingTable = $wpdb->prefix . "aa_shopping";

            foreach ($rows as $val) {
                $wpdb->delete(
                    $shoppingTable,
                    ['id' => $val]
                );
            }
        }

        private function aa_pay_set_status($order_id, $status)
        {
            global $wpdb;
            $paymentsTable = $wpdb->prefix . "aa_payments";

            $data = $wpdb->update(
                $paymentsTable,
                ['status_no' => $status,],
                ['order_id' => $order_id]
            );

            return $data;
        }

        private function aa_pay_set_track_id($order_id, $track_id)
        {
            global $wpdb;
            $paymentsTable = $wpdb->prefix . "aa_payments";

            $data = $wpdb->update(
                $paymentsTable,
                ['track_id' => $track_id,],
                ['order_id' => $order_id]
            );

            return $data;
        }

        private function aa_pay_set_errors($order_id, $msg)
        {
            global $wpdb;
            $paymentsTable = $wpdb->prefix . "aa_payments";

            $data = $wpdb->update(
                $paymentsTable,
                ['errors' => $msg,],
                ['order_id' => $order_id]
            );

            if ($data !== false)
                return true;

            return false;
        }

        private function aa_pay_check_order($order_id, $id)
        {
            global $wpdb;
            $paymentsTable = $wpdb->prefix . "aa_payments";

            $data = $wpdb->get_var("SELECT payment_id FROM $paymentsTable WHERE order_id = '$order_id'");

            if (!empty($data))
                if (strcmp($id, $data) === 0)
                    return true;

            return false;
        }

        private function aa_pay_verify_payment($params)
        {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, 'https://api.idpay.ir/v1.1/payment/verify');
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($params));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $this->paymentHeader);

            $result = curl_exec($ch);
            curl_close($ch);

            return json_decode($result);
        }

        private function aa_pay_set_info_payent($order_id, $params)
        {
            global $wpdb;
            $paymentsTable = $wpdb->prefix . "aa_payments";

            $data = $wpdb->update(
                $paymentsTable,
                $params,
                ['order_id' => $order_id]
            );

            if ($data !== false)
                return true;

            return false;
        }

        private function aa_pay_set_pr_selling($order_id)
        {
            global $wpdb;
            $paymentsTable = $wpdb->prefix . "aa_payments";

            $objectsRes = $wpdb->get_var("SELECT objects FROM $paymentsTable WHERE order_id = '$order_id'");
            if (!empty($objectsRes)) {
                $objects = json_decode($objectsRes);
                for ($i = 0; $i < count($objects); $i++) {
                    $pr_id = $objects[$i]->pr_id;
                    $selling = (int)get_post_meta($pr_id, "pr_selling", true) + 1;
                    update_post_meta($pr_id, "pr_selling", $selling);
                }
                return true;
            }
            return false;
        }

        #
        public function aa_pay_create_payment($data)
        {
            global $wpdb;
            $res = array();
            $error = array();
            $paymentsTable = $wpdb->prefix . "aa_payments";
            $shoppingTable = $wpdb->prefix . "aa_shopping";
            $user_id = (int)$this->user_id;
            $user = get_user_by('id', $user_id);

            $order_id = self::aa_pay_checkOrderId(rand(1111111, 9999999));
            $username = (string)$user->user_login;
            $name  = (string)get_user_meta($user_id, "first_name", true) . " " . (string)get_user_meta($user_id, "last_name", true);
            $phone = (string)get_user_meta($user_id, "phone_number", true);
            $email = (string)$user->user_email;

            $amount = 0;
            $objects = array();
            $cartRows = array();

            $userCart = $wpdb->get_results("SELECT * FROM $shoppingTable WHERE user = '$user_id'");
            for ($i = 0; $i < count($userCart); $i++) {
                $row_id   = (int)$userCart[$i]->id;
                $post_id  = (int)$userCart[$i]->product;
                $price    = (int)get_post_meta($post_id, "pr_price", true);
                $discount = (int)get_post_meta($post_id, "pr_discount", true);

                if ($price > 0 && $discount > 0) {
                    $d = 100 - $discount;
                    $tPrice = ($price * $d) / 100;
                } else {
                    $tPrice = $price;
                }

                $productArr = array(
                    'pr_id' => $post_id,
                    'price' => (int)$tPrice,
                    'discount' => (int)$discount,
                );

                $objects[] = $productArr;
                $cartRows[] = $row_id;
                $amount += $tPrice;
            }

            $params = array(
                'order_id' => (string)$order_id,
                'amount'   => (int)$amount * 10,
                'name'     => (string)$name,
                'phone'    => (string)$phone,
                'mail'     => (string)$email,
                'desc'     => (string)$username,
                'callback' => (string)site_url() . "/payments",
            );

            $payParams = array(
                'user'      => $user_id,
                'order_id'  => $params["order_id"],
                'amount'    => $params["amount"],
                'objects'   => json_encode($objects),
                'user_info' => json_encode(array(
                    'user'  => (string)$username,
                    'name'  => (string)$name,
                    'phone' => (string)$phone,
                    'mail'  => (string)$email,
                )),
                'pay_date' => current_time('mysql', true),
            );

            if ($amount === 0) {
                self::aa_pay_clearShoppingCart($cartRows);
                $newPay = $wpdb->insert(
                    $paymentsTable,
                    array_merge(
                        $payParams,
                        array(
                            'payment_id' => "1",
                            'status_no' => 200,
                        )
                    ),
                );

                if ($newPay === false) {
                    $error[] = "خطایی در پرداخت پیش آمد! لطفا دوباره امتحان کنید.";
                    $res = array(
                        'success' => false,
                        'error' => $error
                    );
                } else {
                    $res = array(
                        'success' => true,
                        'redirect' => false
                    );
                }
                #
            } elseif ($params["amount"] <= 1000 || $params["amount"] >= 500000000) {
                $error[] = "مبلغ باید بین 1,000 ریال تا 500,000,000 ریال باشد.";
                $res = array(
                    'success' => false,
                    'error' => $error
                );
                #
            } else {
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, 'https://api.idpay.ir/v1.1/payment');
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($params));
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
                curl_setopt($ch, CURLOPT_HTTPHEADER, $this->paymentHeader);

                $createRes = json_decode(curl_exec($ch));
                curl_close($ch);

                if (empty($createRes)) {
                    $error[] = "پاسخی دریافت نشد! لطفا دقایقی دیگر تلاش کنید.";
                    $res = array(
                        'success' => false,
                        'error' => $error
                    );
                } else {
                    if (isset($createRes->error_code)) {
                        $newPay = $wpdb->insert(
                            $paymentsTable,
                            array_merge(
                                $payParams,
                                array(
                                    'payment_id' => "0",
                                    'status_no' => (int)$createRes->error_code,
                                    'errors' => $createRes->error_message,
                                )
                            ),
                        );

                        $error[] = "خطایی در پرداخت پیش آمد! لطفا دوباره امتحان کنید. #" . $createRes->error_code;
                        $res = array(
                            'success' => false,
                            'error' => $error
                        );
                    } else {
                        $newPay = $wpdb->insert(
                            $paymentsTable,
                            array_merge(
                                $payParams,
                                array(
                                    'payment_id' => $createRes->id,
                                    'status_no' => 0,
                                )
                            ),
                        );

                        if ($newPay === false) {
                            $error[] = "خطایی در ایجاد پرداخت پیش آمد! لطفا دوباره امتحان کنید.";
                            $res = array(
                                'success' => false,
                                'error' => $error
                            );
                        } else {
                            self::aa_pay_clearShoppingCart($cartRows);
                            $res = array(
                                'success' => true,
                                'redirect' => true,
                                'link' => $createRes->link,
                            );
                        }
                    }
                }
            }

            return $res;
        }

        public function aa_pay_verify($response)
        {
            $error = null;

            $status  = self::aa_pay_set_status($response["order_id"], $response["status"]);
            $trackID = self::aa_pay_set_track_id($response["order_id"], $response["track_id"]);
            if ($status) {
                if ($response["status"] == 10) {
                    $checkOrder = self::aa_pay_check_order($response["order_id"], $response["id"]);
                    if ($checkOrder) {
                        $verify = self::aa_pay_verify_payment(array(
                            'id'       => $response['id'],
                            'order_id' => $response['order_id'],
                        ));
                        if (!empty($verify)) {
                            if (empty($verify->error_code)) {
                                self::aa_pay_set_status($response["order_id"], $verify->status);
                                if ($verify->status >= 100) {
                                    $verifyParams = array(
                                        'track_id'       => $verify->track_id,
                                        'pay_track_id'   => $verify->payment->track_id,
                                        'card_no'        => $verify->payment->card_no,
                                        'hashed_card_no' => $verify->payment->hashed_card_no,
                                        'pay_amount'     => $verify->payment->amount,
                                        'verify_date'    => date('Y-m-d H:i:s', $verify->verify->date),
                                    );

                                    $updateInfo = self::aa_pay_set_info_payent($response["order_id"], $verifyParams);
                                    self::aa_pay_set_pr_selling($response["order_id"]);

                                    if ($updateInfo) {
                                        wp_redirect(site_url() . "/account?state=order&success=true&id=" . $response["order_id"]);
                                        exit;
                                    } else {
                                        $error = 'پرداخت تایید شد ولی در ذخیره اطلاعات خطایی رخ داده. | e7';
                                        self::aa_pay_set_errors($response['order_id'], $error);
                                    }
                                } else {
                                    $error = 'e6';
                                    if ($verify->status == 101) {
                                        $msg = 'پرداخت قبلا تایید شده است';
                                    } else {
                                        $msg = $error;
                                    }
                                    self::aa_pay_set_errors($response['order_id'], $msg);
                                }
                            } else {
                                self::aa_pay_set_status($response["order_id"], $verify->error_code);
                                self::aa_pay_set_errors($response['order_id'], $verify->error_message . " | e5");
                            }
                        } else {
                            $error = 'پاسخی دریافت نشد. | e4';
                            self::aa_pay_set_errors($response['order_id'], $error);
                        }
                    } else {
                        $error = 'کلید دریافت شده از درگاه پرداخت با کلید ذخیره شده برابر نیست. | e3';
                        self::aa_pay_set_errors($response['order_id'], $error);
                    }
                } else {
                    $error = 'e2';
                    if ($response['status'] == 1) {
                        $msg = 'پرداخت انجام نشده است';
                    } elseif ($response['status'] == 2) {
                        $msg = 'پرداخت ناموفق بوده است';
                    } elseif ($response['status'] == 3) {
                        $msg = 'خطا رخ داده است';
                    } elseif ($response['status'] == 4) {
                        $msg = 'بلوکه شده';
                    } elseif ($response['status'] == 5) {
                        $msg = 'برگشت به پرداخت کننده';
                    } elseif ($response['status'] == 6) {
                        $msg = 'برگشت خورده سیستمی';
                    } elseif ($response['status'] == 7) {
                        $msg = 'انصراف از پرداخت';
                    } elseif ($response['status'] == 8) {
                        $msg = 'به درگاه پرداخت منتقل شد';
                    } else {
                        $msg = $error;
                    }
                    self::aa_pay_set_errors($response['order_id'], $msg);
                }
            } else {
                $error = 'e1';
                self::aa_pay_set_errors($response['order_id'], $error);
            }

            if (!empty($error)) {
                wp_redirect(site_url() . "/account?state=order&success=false");
                exit;
            }
        }

        public function aa_pay_get_payments($data)
        {
            global $wpdb;
            $paymentsTable = $wpdb->prefix . "aa_payments";

            $results = $wpdb->get_results("SELECT order_id,amount,objects,user_info,status_no,pay_date,errors  FROM $paymentsTable");

            for ($i = 0; $i < count($results); $i++) {
                $results[$i]->objects = json_decode($results[$i]->objects);
                $results[$i]->user_info = json_decode($results[$i]->user_info);
            }

            return $results;
        }
    }
}
