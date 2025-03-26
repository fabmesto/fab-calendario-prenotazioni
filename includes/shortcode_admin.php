<?php

namespace fabcalpre;

require_once FAB_BASE_PLUGIN_DIR_PATH . 'fab_base/adminlte3/shortcode.php';

if (!class_exists('fabcalpre\shortcode_admin')) {
    class shortcode_admin extends \fab\adminlte3\shortcode
    {
        public $macaddress_name = "fabcalpre_macaddress";
        public $hide_admin_bar = true;
        public $register_url = false;

        public function __construct($parent, $shortcode_name)
        {
            $this->logo_img = get_option('mailrestapi_login_head', '');
            parent::__construct($parent, $shortcode_name);
        }

        public function load_scripts_styles()
        {
            parent::load_scripts_styles();

            // sweetalert2
            wp_register_script('sweetalert2', 'https://cdn.jsdelivr.net/npm/sweetalert2@9', array(), '1.0');

            wp_register_style('fabcalpre-admin-css', FAB_PLUGIN_DIR_URL . 'assets/admin.css', array(), '1.1');

            $this->register_vue_core();
            $this->register_vue_fab_base();

            $this->register_vuetify();
        }

        public function load_assets()
        {
            wp_enqueue_style('fab-vuetify');
            wp_enqueue_script('sweetalert2');
            wp_enqueue_style('fabcalpre-admin-css');

            parent::load_assets();
            // wp_enqueue_style
            // wp_enqueue_script
            return false;
        }

        public function macaddress()
        {
            return true;
        }
    }
}
