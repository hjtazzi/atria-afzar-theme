<?php
/* Template Name: payments */
?>

<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $clsPayments = new aa_action_payments(null, null, get_current_user_id());

    $response = array(
        'status'   => (int)$_POST['status'],
        'id'       => (string)$_POST['id'],
        'order_id' => (string)$_POST['order_id'],
        'track_id' => (int)$_POST['track_id'],
    );

    $clsPayments->aa_pay_verify($response);
} else {
    wp_redirect(site_url() . "/account?state=order");
    exit;
}
?>

<!DOCTYPE html>
<html lang="fa_IR" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#fdfdff">
    <title><?php wp_title(); ?></title>
    <?php wp_head(); ?>
</head>

<body>
    <div id="loading" class="loading">
        <div class="loader"></div>
    </div>
</body>

</html>