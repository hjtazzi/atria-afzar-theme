<?php

if (isset($_GET["reset-pass"])) {
    if ($_GET["reset-pass"] == true) {
        aa_userForm("auth-change-pass_root");
    } else {
        aa_userForm("auth-reset-pass_root");
    }
} elseif (isset($_GET["register"])) {
    aa_userForm("auth-register_root");
} else {
    aa_userForm("auth-login_root");
}


function aa_userForm($rootID)
{
    $imgs = array(
        'bg-right' => get_template_directory_uri() . "/img/about-us-bg-right.png",
        'img-left' => get_template_directory_uri() . "/img/login-img.png",
    );
?>
    <!-- Main -->
    <main id="content">
        <div class="container">
            <section id="authentication" class="authentication">
                <div class="authentication-before">
                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="280" height="50" preserveAspectRatio="xMidYMid" viewBox="0 0 280 50">
                        <use x="0" y="0" xlink:href="#svg-live-bg"></use>
                    </svg>
                </div>
                <div class="authentication-form">
                    <div class="af-r">
                        <div style="background-image: url(<?php echo $imgs["bg-right"] ?>);">
                            <img src="<?php echo $imgs["img-left"] ?>" alt="about-us-img-left">
                        </div>
                    </div>
                    <div id="<?php echo $rootID; ?>" class="af-l">
                        <div id="auth-section-loading" class="section-loading loading-hidden">
                            <div class="loader"></div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </main>
<?php
}
