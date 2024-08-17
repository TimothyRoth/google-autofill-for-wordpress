<?php
/**
 * Plugin Name: Google Autofill for WordPress
 * Plugin URI: www.timothy-roth.de
 * Description: Autofill for Zip Codes and Locations connected via Google Api Key
 * Version: 1.0.0
 * Author: Timothy Roth
 * Author URI: www.timothy-roth.de
 * License: GPL2
 * Text Domain: wp_ga_mp
 */

define('WP_GAMP_PLUGIN_PATH', plugin_dir_path(__FILE__));
define('WP_GAMP_Plugin_URI', plugin_dir_url(__FILE__));

require_once(WP_GAMP_PLUGIN_PATH . "/classes/GAMP_Plugin.class.php");
require_once(WP_GAMP_PLUGIN_PATH . "/classes/GAMP_Settings.class.php");

global $gamp_Plugin, $gamp_Settings;

$gamp_Plugin = new GAMP_Plugin();
$gamp_Settings = new GAMP_Settings();

register_activation_hook(
    __FILE__,
    array($gamp_Plugin, 'plugin_activation')
);

register_deactivation_hook(
    __FILE__,
    array($gamp_Plugin, 'plugin_deactivation')
);
