<?php

namespace fabcalpre;

require_once FAB_BASE_PLUGIN_DIR_PATH . 'fab_base/fab_shortcode_adminlte3.php';

if (!class_exists('fabcalpre\shortcode_admin')) {
    class shortcode_admin extends \fab\fab_shortcode_adminlte3
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

            wp_register_style('fabcalpre-admin-css', FAB_PLUGIN_DIR_URL . 'assets/admin.css', array(), '1.1');
        }

        public function load_assets()
        {
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
