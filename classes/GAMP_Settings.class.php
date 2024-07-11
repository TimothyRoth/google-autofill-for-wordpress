<?php
/**
 * GAMP_Settings
 */

class GAMP_Settings
{

    private string $settings_page_url;
    private string $settings_tab_general;
    private array $tabsArray;
    private string $rs_gamp_location;
    private string $rs_gamp_zip_code;

    public function __construct()
    {

        add_action('admin_menu', array($this, 'add_options_menu'));
        add_action('admin_init', array($this, 'gamp_display_options'));
        add_action('admin_footer', array($this, 'add_admin_script'));

        $this->rs_gamp_api_key = 'gamp_api_key';
        $this->rs_gamp_zip_code = 'gamp_zip_code';
        $this->rs_gamp_location = 'gamp_location';
        $this->settings_page_url = 'gamp_settings';
        $this->settings_tab_general = 'gamp_settings_general';

        $this->tabsArray = array(
            array(
                'title' => 'General',
                'slug' => $this->settings_tab_general,
            ),

        );
    }

    public function get_api_key(): string
    {
        return get_option($this->rs_gamp_api_key);
    }

    public function get_zip_code(): string
    {
        return get_option($this->rs_gamp_zip_code);
    }

    public function get_location(): string
    {
        return get_option($this->rs_gamp_location);
    }

    public function add_admin_script(): void
    {
        // ... add code if needed
    }

    public function add_options_menu(): void
    {
        error_log('Adding options menu'); // Zum Debuggen hinzufügen
        add_options_page(
            __('Google Autofill MP', 'wp_gamp'),
            __('Google Autofill MP', 'wp_gamp'),
            'manage_options',
            'gamp_settings',
            array($this, 'add_settings_page')
        );
    }

    public function add_settings_page(): void
    {
        ?>
        <style>
            /* The switch - the box around the slider */
            .toggle-switch {
                position: relative;
                display: inline-block;
                width: 50px;
                height: 25px;
            }

            /* Hide default HTML checkbox */
            .toggle-switch input {
                opacity: 0;
                width: 0;
                height: 0;
            }

            /* The slider */
            .slider {
                position: absolute;
                cursor: pointer;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background-color: #ccc;
                -webkit-transition: .4s;
                transition: .4s;
                border-radius: 25px;

            }

            .slider:before {
                position: absolute;
                content: "";
                height: 22px;
                width: 22px;
                left: 1px;
                bottom: 2px;
                background-color: white;
                -webkit-transition: .4s;
                transition: .4s;
                border-radius: 50%;
            }

            input:checked + .slider {
                background-color: #2196F3;
            }

            input:focus + .slider {
                box-shadow: 0 0 1px #2196F3;
            }

            input:checked + .slider:before {
                -webkit-transform: translateX(26px);
                -ms-transform: translateX(26px);
                transform: translateX(26px);
            }
        </style>

        <?php

        $active_tab = $_GET['tab'] ?? $this->settings_tab_general;

        if (count($this->tabsArray) > 1) {
            ?>

            <div class="wrap">
            <?php
            $this->option_tabs($active_tab);
        }

        if ($_POST) {
            $this->save_options($active_tab);
        }

        ?>
        <form method="post"
              action="options-general.php?page=<?php echo $this->settings_page_url; ?>&tab=<?php echo $active_tab; ?>">
            <?php

            settings_fields("gamp_settings_option_group");

            do_settings_sections("gamp-settings-page");

            submit_button();
            ?>
        </form>
        </div>
        <?php
    }

    public function option_tabs($active_tab): void
    {

        ?>
        <h2 class="nav-tab-wrapper">
            <a href="?page=<?php echo $this->settings_page_url; ?>&tab=<?php echo $this->settings_tab_general; ?>"
               class="nav-tab <?php if ($active_tab == $this->settings_tab_general) {
                   echo 'nav-tab-active';
               } ?> "><?php _e('General', 'wp_gamp'); ?></a>
        </h2>
        <?php
    }


    public function gamp_display_options(): void
    {
        $this->show_GeneralSettingsTab();
    }

    public function get_plugin_settings(): array
    {
        return [
            $this->rs_gamp_api_key => $this->get_api_key(),
            $this->rs_gamp_zip_code => $this->get_zip_code(),
            $this->rs_gamp_location => $this->get_location(),
        ];
    }

    public function show_GeneralSettingsTab(): void
    {
        add_settings_section(
            'general_settings_gamp_general_section',
            __('General', 'wp_gamp'),
            array($this, 'general_info_callback'),
            'gamp-settings-page'
        );

        add_settings_field(
            'gamp_api_key',
            __('Google API Key', 'wp_gamp'),
            array($this, 'gamp_apI_key_callback'),
            'gamp-settings-page',
            'general_settings_gamp_general_section'
        );

        add_settings_field(
            'gamp_zip_code_class',
            __('Klasse für das Inputfeld PLZ', 'wp_gamp'),
            array($this, 'gamp_zip_code_callback'),
            'gamp-settings-page',
            'general_settings_gamp_general_section'
        );

        add_settings_field(
            'gamp_location_class',
            __('Klasse für das Inputfeld Ort', 'wp_gamp'),
            array($this, 'gamp_location_callback'),
            'gamp-settings-page',
            'general_settings_gamp_general_section'
        );

    }

    public function general_info_callback(): void
    {
        _e('General Settings', 'wp_gamp');
    }

    public function gamp_api_key_callback(): void
    { ?>
        <input type="text" id="<?= $this->rs_gamp_api_key ?>" name="<?= $this->rs_gamp_api_key ?>"
               value="<?php echo $this->get_api_key() ?>"
               style="width: 50%;"/>
        <?php
    }

    public function gamp_zip_code_callback(): void
    { ?>
        <input type="text" id="<?= $this->rs_gamp_zip_code ?>" name="<?= $this->rs_gamp_zip_code ?>"
               value="<?php echo $this->get_zip_code() ?>"
               style="width: 50%;"/>
        <?php
    }

    public function gamp_location_callback(): void
    { ?>
        <input type="text" id="<?= $this->rs_gamp_location ?>" name="<?= $this->rs_gamp_location ?>"
               value="<?php echo $this->get_location() ?>"
               style="width: 50%;"/>
        <?php
    }


    public function save_options(): void
    {
        if ($_POST && isset($_POST[$this->rs_gamp_api_key])) {
            update_option($this->rs_gamp_api_key, sanitize_text_field($_POST[$this->rs_gamp_api_key]));
        }
        if ($_POST && isset($_POST[$this->rs_gamp_zip_code])) {
            update_option($this->rs_gamp_zip_code, sanitize_text_field($_POST[$this->rs_gamp_zip_code]));
        }
        if ($_POST && isset($_POST[$this->rs_gamp_location])) {
            update_option($this->rs_gamp_location, sanitize_text_field($_POST[$this->rs_gamp_location]));
        }
    }
}