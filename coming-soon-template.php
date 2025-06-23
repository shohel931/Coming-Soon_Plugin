<?php
// Email Submit Data
if (
    isset($_SERVER['REQUEST_METHOD']) &&
    $_SERVER['REQUEST_METHOD'] === 'POST' &&
    isset($_POST['csms_email']) &&
    isset($_POST['csms_email_nonce']) &&
    wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['csms_email_nonce'])), 'csms_email_action')
) {
    $email = sanitize_email(wp_unslash($_POST['csms_email']));
    if (is_email($email)) {
        $saved = get_option('csms_email_list', []);
        if (!in_array($email, $saved)) {
            $saved[] = $email;
            update_option('csms_email_list', $saved);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Coming Soon</title>
    <?php wp_head(); ?>
</head>
<body>
    <div class="csms-wrapper">
        <h1>Coming Soon</h1>
        <p>We're nearing the completion of our site. <br> Subscribe below to stay updated and be informed when we launch!</p>
        <form action="" method="post">
            <?php wp_nonce_field('csms_email_action', 'csms_email_nonce'); ?>
            <input type="email" name="csms_email" placeholder="Enter your email" required>
            <button type="submit" name="submit">Subscribe</button>
        </form>
    </div>
</body>
</html>

