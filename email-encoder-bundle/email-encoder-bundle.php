<?php defined('ABSPATH') OR die('No direct access.');
/*
Plugin Name:    Email Encoder Bundle - Protect Email Address
Plugin URI:     http://www.freelancephp.net/email-encoder-php-class-wp-plugin/
Description:    Protect email addresses on your site and hide them from spambots by using an encoding method. Easy to use, flexible .
Author:         Victor Villaverde Laan
Version:        1.4.6
Author URI:     http://www.freelancephp.net
License:        Dual licensed under the MIT and GPL licenses
Text Domain:    email-encoder-bundle
Domain Path:    /languages
*/

// constants
if (!defined('EMAIL_ENCODER_BUNDLE_VERSION')) { define('EMAIL_ENCODER_BUNDLE_VERSION', '1.4.5'); }
if (!defined('EMAIL_ENCODER_BUNDLE_FILE')) { define('EMAIL_ENCODER_BUNDLE_FILE', defined('TEST_EEB_PLUGIN_FILE') ? TEST_EEB_PLUGIN_FILE : __FILE__); }
if (!defined('EMAIL_ENCODER_BUNDLE_KEY')) { define('EMAIL_ENCODER_BUNDLE_KEY', 'WP_Email_Encoder_Bundle'); }
if (!defined('EMAIL_ENCODER_BUNDLE_OPTIONS_NAME')) { define('EMAIL_ENCODER_BUNDLE_OPTIONS_NAME', 'WP_Email_Encoder_Bundle_options'); }
if (!defined('EMAIL_ENCODER_BUNDLE_ADMIN_PAGE')) { define('EMAIL_ENCODER_BUNDLE_ADMIN_PAGE', 'email-encoder-bundle-settings'); }

// wp_version var was used by older WP versions
if (!isset($wp_version)) {
    $wp_version = get_bloginfo('version');
}

// check plugin compatibility
if (version_compare($wp_version, '3.6', '>=') && version_compare(phpversion(), '5.2.4', '>=')) {

    // include classes
    require_once('includes/class-eeb-admin.php');
    require_once('includes/class-eeb-site.php');
    require_once('includes/template-functions.php');

    // create instance
    $Eeb_Site = Eeb_Site::getInstance();

    // handle AJAX request
    // input vars
    if (!empty($_POST['eebActionEncodeEmail'])) {
        $eebActionEncodeEmail = sanitize_text_field($_POST['eebActionEncodeEmail']);
        $method = sanitize_text_field($_POST['eebMethod']);
        $email = sanitize_email($_POST['eebEmail']);
        $display = wp_kses_post($_POST['eebDisplay']);

        if (empty($display)) {
            $display = $email;
        }

        echo $Eeb_Site->encode_email($email, $display, '', $method, true);
        exit;
    }

} else {

    // set error message
    if (!function_exists('eeb_error_notice')):
        function eeb_error_notice() {
            $plugin_title = get_admin_page_title();

            echo '<div class="error">'
                . sprintf(__('<p>Warning - The plugin <strong>%s</strong> requires PHP 5.2.4+ and WP 3.6+.  Please upgrade your PHP and/or WordPress.'
                             . '<br/>Disable the plugin to remove this message.</p>'
                             , 'email-encoder-bundle'), $plugin_title)
                . '</div>';
        }

        add_action('admin_notices', 'eeb_error_notice');
    endif;

}
