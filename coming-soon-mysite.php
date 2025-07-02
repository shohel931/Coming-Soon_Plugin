<?php
/*
* Plugin Name: Coming Soon Mysite
* Plugin URI: https://wordpress.org/plugins/coming-soon-mysite
* Description: A simple plugin to show coming soon page for visitors.
* Version: 1.1.0
* Requires at least: 5.2
* Requires PHP: 7.2
* Author: Shohel Rana
* Author URI: https://shohelrana.top
* License: GPLv2 or later
* License URI: https://www.gnu.org/licenses/gpl-2.0.html
* Text Domain: coming-soon-mysite
*/

// Enqueue Css

function csms_enqueue_style() {
    if (!current_user_can('manage_options')) {
        $enabled = get_option('csms_enabled', true);
        if ($enabled && !is_admin()) {
            wp_register_style('coming-style', plugin_dir_url(__FILE__). 'coming-style.css', [], '1.0.0');
            wp_enqueue_style('coming-style');
        }
    }
}
add_action('wp_enqueue_scripts', 'csms_enqueue_style');


// Show Coming Show Page
function csms_show_coming_soon() {
    if (!current_user_can('manage_options')) {
        $enabled = get_option('csms_enabled', true);
        if ($enabled && !is_admin()) {
            include plugin_dir_path(__FILE__) . 'coming-soon-template.php';
            exit;
        }
    }
}
add_action('template_redirect', 'csms_show_coming_soon');

// Admin Menu 
function csms_coming_add_admin_menu() {
    add_menu_page(
        'Coming Soon Mysite',
        'Coming Soon',
        'manage_options',
        'csms_coming_mysite',
        'csms_settings_callback',
        'dashicons-clock',
        70
    );

    add_submenu_page(
        'csms_coming_mysite',
        'Settings',
        'Settings',
        'manage_options',
        'csms_settings',
        'csms_settings_callback'
    );
    add_submenu_page(
        'csms_coming_mysite',
        'Subscriber Emails',
        'Email List',
        'manage_options',
        'csms_email_list',
        'csms_email_list_page'
    );
}
add_action('admin_menu', 'csms_coming_add_admin_menu');

// Remove Duplicate Coming Soon Submenu
function csms_remove_coming_soon_menu() {
    remove_submenu_page('csms_coming_mysite', 'csms_coming_mysite');
}
add_action('admin_menu', 'csms_remove_coming_soon_menu', 999);

// Settings Page 
function csms_settings_callback() {
    ?>
    <div class="wrap">
        <h2><?php esc_html_e('Coming Soon Settings', 'coming-soon-mysite'); ?></h2>
        <form action="options.php" method="post">
            <?php 
            settings_fields('csms_settings_group');
            do_settings_sections('csms_settings');
            submit_button('Save Changes');
            ?>
        </form>
    </div>
<?php
}

// Register Settings
function csms_register_settings() {
    register_setting('csms_settings_group', 'csms_enabled', array(
        'sanitize_callback' => 'csms_enable'
    ));

    add_settings_section('csms_main', '', null, 'csms_settings');

    add_settings_field('csms_enabled', 'Enable Coming Soon Page', function() {
        $enabled = get_option('csms_enabled');
        echo '<input type="checkbox" name="csms_enabled" value="1"' . checked(1, $enabled, false) . '> Enable';
    }, 'csms_settings', 'csms_main');
}
add_action('admin_init', 'csms_register_settings');

function csms_enable($value) {
    return ($value == '1') ? 1 : 0 ;
}

// Email Sub Menu Calling
function csms_email_list_page() {
    $emails = get_option('csms_email_list', []);
    ?>
    <div class="wrap">
        <h2><?php esc_html_e('Subscriber Email List', 'coming-soon-mysite'); ?></h2>
        <?php if (!empty($emails)) : ?>
            <ul style="margin-left: 20px;">
                <?php foreach ($emails as $email) : ?>
                    <li><?php echo esc_html($email); ?></li>
                <?php endforeach; ?>
            </ul>
        <?php else : ?>
            <p><?php esc_html_e('No emails yet.', 'coming-soon-mysite'); ?></p>
        <?php endif; ?>
    </div>
    <?php 
}



?>