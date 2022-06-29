    <!-- Footer -->
    <footer id="footer" class="footer">
        <div class="before-footer">
            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="280" height="50" preserveAspectRatio="xMidYMid" viewBox="0 0 280 50">
                <use x="0" y="0" xlink:href="#svg-live-bg"></use>
            </svg>
        </div>
        <div class="footer-container row c-1 c-md-3">
            <div class="col">
                <div class="title">
                    <i></i>
                    <h4>درباره ما</h4>
                    <i></i>
                </div>
                <div class="footer-about">
                    <div class="footer-about-img">
                        <img src="./img/logo.png" alt="<?php echo get_bloginfo('name'); ?>" />
                    </div>
                    <div>
                        <p>
                            گروه جوان و خلاق آتریا، در زمینه ارائه خدمات طراحی و بهینه سازی وب‌سایت با هدف پیشرفت و به
                            کارگیری تکنولوژی‌های روز مربوط و ارائه آگاهی به اشخاص مشتاق، فعالیت می‌کند و پیوسته درحال
                            پیشرفت و کسب دانش‌های نو است.
                        </p>
                    </div>
                    <div>
                        <?php
                        wp_nav_menu(array(
                            'theme_location' => "about-menu",
                            'menu_id'        => "",
                            'container'      => "ul",
                        ));
                        ?>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="title">
                    <i></i>
                    <h4>آخرین مطالب</h4>
                    <i></i>
                </div>
                <div class="footer-recent-posts">
                    <?php
                    $args = array(
                        'post_type'      => 'post',
                        'posts_per_page' => 8,
                    );
                    $clsHavePosts = new aa_havePosts();
                    $footerPosts = $clsHavePosts->aa_have_posts($args);
                    $qyeryFooter = $footerPosts["query_posts"];
                    ?>
                    <div>
                        <ul>
                            <?php
                            while ($qyeryFooter->have_posts()) :
                                $qyeryFooter->the_post();
                            ?>
                                <li>
                                    <a  href="<?php echo the_permalink(); ?>" title="<?php echo the_title(); ?>"><?php echo the_title(); ?></a>
                                </li>
                            <?php
                            endwhile;
                            wp_reset_postdata();
                            ?>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="title">
                    <i></i>
                    <h4>دسترسی سریع</h4>
                    <i></i>
                </div>
                <div class="footer-quick-access">
                    <div>
                        <?php
                        wp_nav_menu(array(
                            'theme_location' => "quick-access-menu",
                            'menu_id'        => "",
                            'container'      => "ul",
                        ));
                        ?>
                    </div>
                    <div class="enamad">
                        <a referrerpolicy="origin" target="_blank" href="#">
                            <img referrerpolicy="origin" src="./img/enamad(1).png" alt="enamad" style="cursor:pointer" />
                        </a>
                        <a referrerpolicy="origin" target="_blank" href="#">
                            <img referrerpolicy="origin" src="./img/enamad(1).png" alt="enamad" style="cursor:pointer" />
                        </a>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="copyright">
            <p>1399-1401 | تمامی حقوق مادی و معنوی <a href="<?php echo site_url(); ?>">آتریا افزار</a> محفوظ است.</p>
        </div>
    </footer>
    <div class="clearfix"></div>

    <div id="app_root" class="root"></div>

    <div id="svg_root">
        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="0" height="0" preserveAspectRatio="xMidYMid" viewBox="0 0 280 50">
            <defs>
                <g id="svg-live-bg">
                    <path class="svg-live-bg-l1" d="M 0 0 C 160 0 220 40 280 40 L 280 0" fill="#fdfdff" opacity="0.75">
                        <animate attributeName="d" dur="8s" repeatCount="indefinite" begin="0" values="M 0 0 C 160 0 220 40 280 40 L 280 0; M 0 0 C 80 20 110 10 280 20 L 280 0; M 0 0 C 160 0 220 40 280 40 L 280 0;">
                        </animate>
                    </path>
                    <path class="svg-live-bg-l2" d="M 0 0 C 140 30 200 40 280 20 L 280 0" fill="#fdfdff" opacity="0.75">
                        <animate attributeName="d" dur="8s" repeatCount="indefinite" begin="-0.5s" values="M 0 0 C 140 30 200 40 280 20 L 280 0; M 0 0 C 160 0 220 40 280 40 L 280 0; M 0 0 C 140 30 200 40 280 20 L 280 0;">
                        </animate>
                    </path>
                    <path class="svg-live-bg-l3" d="M 60 0 C 170 50 220 50 280 30 L 280 0" fill="#fdfdff" opacity="0.75">
                        <animate attributeName="d" dur="8s" repeatCount="indefinite" begin="-0.75s" values="M 60 0 C 170 50 220 50 280 30 L 280 0; M 0 0 C 160 0 220 40 280 40 L 280 0; M 60 0 C 170 50 220 50 280 30 L 280 0;">
                        </animate>
                    </path>
                </g>
            </defs>
        </svg>
    </div>

    <?php wp_footer(); ?>
</body>

</html>