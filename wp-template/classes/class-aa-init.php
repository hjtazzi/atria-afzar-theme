<?php

if (!class_exists('aa_init')) {

    class aa_init
    {
        private $pageArgs;

        public function __construct($settings)
        {
        }

        /* Create Tables */
        public function aa_createTables()
        {
            require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
            global $wpdb;
            $prefix = $wpdb->prefix;
            $charset_collate = $wpdb->get_charset_collate();

            /* tables name */
            $ticketsTable  = "aa_tickets";
            $shoppingTable = "aa_shopping";
            $paymentsTable = "aa_payments";

            #tickets table
            $tickets_table = $prefix . $ticketsTable;
            $ticketsDB = "CREATE TABLE $tickets_table (
                id      BIGINT(20) NOT NULL AUTO_INCREMENT,
                user    BIGINT(20) NOT NULL,
                role    TINYINT(4) NOT NULL,
                status  TINYINT(4) NOT NULL,
                content LONGTEXT NOT NULL,
                date    DATETIME NOT NULL,
                PRIMARY KEY (id)
            ) $charset_collate;";

            #shopping table
            $shopping_table = $prefix . $shoppingTable;
            $tshoppingDB = "CREATE TABLE $shopping_table (
                id      BIGINT(20) NOT NULL AUTO_INCREMENT,
                user    BIGINT(20) NOT NULL,
                product BIGINT(20) NOT NULL,
                PRIMARY KEY (id)
            ) $charset_collate;";

            #payments table
            $payments_table  = $prefix . $paymentsTable;
            $paymentsDB = "CREATE TABLE $payments_table (
            id              BIGINT(20) NOT NULL AUTO_INCREMENT,
            user            BIGINT(20) NOT NULL,
            order_id        TINYTEXT NOT NULL,
            payment_id      LONGTEXT NOT NULL,
            amount          INT(11) NOT NULL,
            objects         LONGTEXT,
            details         LONGTEXT,
            user_info       LONGTEXT NOT NULL,
            status_no       MEDIUMINT(9) NOT NULL DEFAULT 0,
            pay_date        DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP(),
            track_id        INT(11) NOT NULL DEFAULT 0,
            pay_track_id    INT(11) NOT NULL DEFAULT 0,
            card_no         LONGTEXT NOT NULL,
            hashed_card_no  LONGTEXT NOT NULL,
            pay_amount      INT(11) NOT NULL DEFAULT 0,
            verify_date     DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP(),
            payment_type    TINYINT(4) NOT NULL DEFAULT 0,
            errors          LONGTEXT,
            PRIMARY KEY (id)
            ) $charset_collate;";


            dbDelta(array(
                $ticketsDB,
                $tshoppingDB,
                $paymentsDB,
            ));
        }

        /* Insert Pages */
        public function aa_insertPages($args)
        {
            $this->pageArgs = $args;
            add_action('admin_menu', [$this, 'aa_searchPages']);
        }
        public function aa_searchPages()
        {
            $pageArgs = $this->pageArgs;
            $pages    = get_pages();
            $availables = array();

            if (!empty($pages)) {
                for ($i = 0; $i < count($pages); $i++) {
                    for ($j = 0; $j < count($pageArgs); $j++) {
                        if ($pages[$i]->post_name == $pageArgs[$j]['post_name']) {
                            $availables[$i] = $pageArgs[$j]['post_name'];
                            break;
                        }
                    }
                }
            }
            if (empty($pages) || count($availables) == 0) {
                self::aa_insertPage();
            }
        }
        private function aa_insertPage()
        {
            $pageArgs = $this->pageArgs;
            $error = array();

            for ($i = 0; $i < count($pageArgs); $i++) {
                $page = array(
                    'post_type'   => 'page',
                    'post_name'   => $pageArgs[$i]['post_name'],
                    'post_title'  => $pageArgs[$i]['post_title'],
                    'post_status' => 'publish',
                    'post_author' => 1,
                );
                $id = wp_insert_post($page);

                if ($id == 0 || is_wp_error($id)) {
                    $error[] = $pageArgs[$i]['post_name'];
                } else {
                    update_post_meta($id, '_wp_page_template', $pageArgs[$i]['post_template']);
                }
            }

            if (count($error) == 0) {
?>
                <div class="notice notice-success is-dismissible">
                    <p>برگه ها با موفقیت ایجاد شدند.</p>
                </div>
                <button type="button" class="notice-dismiss"><span class="screen-reader-text">رد کردن این اخطار</span></button>
            <?php
            } else {
            ?>
                <div class="notice error is-dismissible">
                    <p>مشکلی در ایجاد برگه ها پیش آمده: <?php print_r($error); ?></p>
                </div>
                <button type="button" class="notice-dismiss"><span class="screen-reader-text">رد کردن این اخطار</span></button>
<?php
            }
        }

        public function aa_getCategories()
        {
            global $wpdb;
            $termID = $wpdb->get_col("SELECT term_id FROM $wpdb->term_taxonomy WHERE taxonomy = 'category'");

            $categorys = array();
            for ($i = 0; $i < count($termID); $i++) {
                $categorys[] = get_term_by('id', $termID[$i], 'category', 'ARRAY_A');
            }

            usort($categorys, function ($a, $b) {
                return $b['count'] <=> $a['count'];
            });
            return $categorys;
        }

        public function aa_getUserIpAddr()
        {
            if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
                //ip from share internet
                $ip = $_SERVER['HTTP_CLIENT_IP'];
            } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
                //ip pass from proxy
                $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
            } else {
                $ip = $_SERVER['REMOTE_ADDR'];
            }
            return $ip;
        }

        public function aa_shamsiDate($date, $formatTime = 'H:i:s')
        {
            if (is_numeric($date)) {
                $timestamp = $date;
            } else {
                $timestamp = strtotime($date);
            }

            $dateParse = date_parse(wp_date('Y-m-d H:i:s', $timestamp));
            $theDateArr  = gregorian_to_jalali($dateParse['year'], $dateParse['month'], $dateParse['day']);
            $mm = jdate_words(array("mm" => $theDateArr[1]));
            $the_Date = $theDateArr[2] . " " . $mm["mm"] . " " . $theDateArr[0];
            $the_Time  = wp_date($formatTime, $timestamp);

            if ($formatTime == '') {
                $theDate = $the_Date;
            } else {
                $theDate = $the_Date . ' - ' . $the_Time;
            }
            return $theDate;
        }

        function aa_emailContent()
        {
            $msg = '<!DOCTYPE html>
                <html lang="fa" dir="rtl">
                <head>
                    <meta charset="UTF-8">
                    <meta name="viewport" content="width=device-width, initial-scale=1.0">
                </head>
                <body style="margin: 0; padding: 0;; direction: rtl; text-align: right; color: #282828;"><div>';

            $msg .= '<div><h3 style="margin: 0; padding: 16px 6px;">[msg_titile]</h3></div>';
            $msg .= '<div>
                <p style="margin: 0; padding: 8px 6px;">[msg_content]</p>
                <p style="margin: 0; padding: 8px 6px">';

            $msg .= '<a style="text-decoration: none; padding: 2px 4px; border: 1px solid #282828; border-radius: 4px; display: inline-block; color: #282828;" href="[msg_link]">[msg_link_txt]</a>';

            $msg .= '</p></div>';
            $msg .= '<div><h6 style="margin: 0; padding: 16px 0; text-align: center;"><a style="text-decoration: none; color: #282828; padding: 4px 6px;" href="[msg_site_url]">[msg_site_name]</a><span> | </span>[msg_date]</h6></div>';

            $msg .= '</div></body></html>';

            $msg = str_replace("[msg_date]", $this->aa_shamsiDate(current_time('mysql', true), "H:i"), $msg);
            return $msg;
        }
    }
    # v00.12.xx
}
