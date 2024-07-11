<?php
/**
 * gamp_Plugin
 */

class GAMP_Plugin
{

    public function __construct()
    {
        setlocale(LC_TIME, 'de_DE', 'de_DE.UTF-8');
        // enqeue scripts
        add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'));

    }

    /**
     * Activate plugin
     */
    public function plugin_activation(): void
    {
    }

    /**
     * Deactivate plugin
     */
    public function plugin_deactivation(): void
    {
    }

    /**
     * Deactivate plugin
     */
    public function plugin_uninstall(): void
    {
    }

    public function enqueue_scripts(): void
    {
        global $gamp_Settings;

        wp_enqueue_script('gamp_bundle', WP_GAMP_Plugin_URI . 'src/js/gamp_google_autofill.js', array('jquery'), '1.0.0', true);
        wp_localize_script('gamp_bundle', 'gamp_settings', $gamp_Settings->get_plugin_settings());
    }
}