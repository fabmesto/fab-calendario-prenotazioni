<?php

namespace fabcalpre;

if (!class_exists('fabcalpre\settings')) {
    class settings
    {
        var $parent = false;

        public function __construct($parent)
        {
            $this->parent = $parent;
            if (is_admin()) {
                // wp-admin actions
                add_action('admin_menu', array(&$this, 'add_admin_menu'));
                add_action('admin_init', array(&$this, 'register_settings'));
            }
        }

        public function add_admin_menu()
        {
            // add_management_page -> Strumenti
            // add_options_page -> Impostazioni
            // add_menu_page -> in ROOT
            add_menu_page(
                'Fab calendario prenotazioni',
                'Fab calendario prenotazioni',
                'manage_options',
                'fabcalpre_settings',
                array(&$this, 'settings')
            );
        }

        public function settings()
        {
            ob_start();
            $action_file = FAB_PLUGIN_DIR_PATH . 'includes/v/settings.php';
            if (file_exists($action_file)) {
                require_once($action_file);
            } else {
                echo "settings: Nessuna azione trovata: " . $action_file;
            }
            echo ob_get_clean();
        }

        public function register_settings()
        { // whitelist options
            register_setting('fabcalpre-options', $this->parent->shortcode_admin->macaddress_name);
            register_setting('fabcalpre-options', 'fabcalpre-email-subject-new');
            register_setting('fabcalpre-options', 'fabcalpre-email-message-new');
            register_setting('fabcalpre-options', 'fabcalpre-email-subject-del');
            register_setting('fabcalpre-options', 'fabcalpre-email-message-del');
            register_setting('fabcalpre-options', 'fabcalpre-pdf-title');
            register_setting('fabcalpre-options', 'fabcalpre-pdf-html');
            register_setting('fabcalpre-options', 'fabcalpre-max-date');
            register_setting('fabcalpre-options', 'fabcalpre-min-date');
            register_setting('fabcalpre-options', 'fabcalpre-ora-prenotazione-attiva');
        }
    }
}
