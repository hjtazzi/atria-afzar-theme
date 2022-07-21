<?php
$imgs = array(
    'bg-right' => get_template_directory_uri() . "/img/about-us-bg-right.png",
    'bg-left' => get_template_directory_uri() . "/img/about-us-bg-left.png",
    'img-left' => get_template_directory_uri() . "/img/about-us-img-left.png",
);
?>
<section id="first-about" class="first-about">
    <div class="ha-r" style="background-image: url(<?php echo $imgs["bg-right"] ?>);">
        <div>
            <h1><i></i>آتریا افزار<i></i></h1>
            <p class="description">
                گروه جوان و خلاق آتریا، در زمینه ارائه خدمات طراحی و بهینه سازی وب‌سایت با هدف پیشرفت و به
                کارگیری تکنولوژی‌های روز مربوط و ارائه آگاهی به اشخاص مشتاق، فعالیت می‌کند و پیوسته درحال
                پیشرفت و کسب دانش‌های نو است.
                <br />
                گروه طراحی سایت آتریا، با هدف خدمت‌رسانی به کسب و کارها با طراحی و ارائه ابزارهای مورد نیاز
                برای بازاریابی و معرفی محصول یا خدمات، در تلاش است تا حس خوبی را برای شما تداعی کند. شما
                می‌توانید در کنار ابزارهای موجود از پیش آماده شده در سایت، درخواست طراحی سایت بر اساس نیاز و
                سلیقه خود را به ما ارسال کنید تا در کوتاه‌ترین زمان سایتی منحصر به فرد برای شروع کسب و کار
                خود پیاده‌سازی کنید.
            </p>
            <i></i>
        </div>
    </div>
    <div class="ha-l" style="background-image: url(<?php echo $imgs["bg-left"] ?>);">
        <img src="<?php echo $imgs["img-left"] ?>" alt="about-us-img-left">
    </div>
</section>
<div class="clearfix space"></div>