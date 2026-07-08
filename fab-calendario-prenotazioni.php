<?php
/*
Plugin Name: Fab Calendario Prenotazioni
Plugin URI: https://www.telnetsrl.com/
Description: Plugin Calendario Prenotazioni
Author: Fabrizio MESTO
Version: 0.0.1
Author URI: https://www.telnetsrl.com/
Text Domain: fabcalpre
Domain Path: lang
*/

namespace fabcalpre;

defined('ABSPATH') or die('No script kiddies please!');

if (!defined(__NAMESPACE__ . '\FAB_PLUGIN_DIR_PATH')) define(__NAMESPACE__ . '\FAB_PLUGIN_DIR_PATH', plugin_dir_path(__FILE__));
if (!defined(__NAMESPACE__ . '\FAB_PLUGIN_DIR_URL')) define(__NAMESPACE__ . '\FAB_PLUGIN_DIR_URL', plugin_dir_url(__FILE__));

require_once FAB_PLUGIN_DIR_PATH . 'includes/install_db.php';
require FAB_PLUGIN_DIR_PATH . 'includes/service.php';

if (class_exists('\fab\Fab_Base')) {

    class Fab_Calendarioprenotazioni extends \fab\Fab_Base
    {
        public $shortcode_name = 'fab-calendario-prenotazioni';
        public $PLUGIN_DIR_PATH = FAB_PLUGIN_DIR_PATH;
        public $PLUGIN_DIR_URL = FAB_PLUGIN_DIR_URL;
        public $NAMESPACE = __NAMESPACE__;
        public $default_controller = 'prenotazioni';
        public $current_controller = 'prenotazioni';
        public $upload_dir = 'files/fabcalpre';
        public $rewrite_login = false;
        public $public_ajax_mode = 'enforce';
        public $public_ajax_actions = [];
        public $rest_permission_mode = 'enforce';
        public $public_rest_actions = [];

        public function plugins_loaded()
        {
            add_filter($this->NAMESPACE . '_rest_permission', [$this, 'rest_permission'], 10, 6);

            $install = new install_db();
            $install->install_db();

            parent::plugins_loaded();

            require FAB_PLUGIN_DIR_PATH . 'includes/settings.php';
            new settings($this);

            require_once FAB_PLUGIN_DIR_PATH . 'includes/shortcode.php';
            $this->shortcode = new shortcode($this, 'fab-calendario-prenotazioni');

            require_once FAB_PLUGIN_DIR_PATH . 'includes/shortcode_admin.php';
            $this->shortcode_admin = new shortcode_admin($this, 'fab-calendario-prenotazioni-admin');
        }

        public function rest_permission($permission, $parent, $route_action, $controller, $action, $request)
        {
            if ($parent !== $this) {
                return $permission;
            }

            if ($this->can_use_public_rest_action($route_action, $controller, $action)) {
                return true;
            }

            if (!is_user_logged_in()) {
                return null;
            }

            if ($this->can_use_rest_action($route_action, $controller, $action)) {
                return true;
            }

            return new \WP_Error(
                'fabcalpre_rest_forbidden',
                'Non autorizzato',
                ['status' => 403]
            );
        }

        private function can_use_public_rest_action($route_action, $controller, $action)
        {
            return $route_action === 'read' && $controller === 'prenotazioni';
        }

        private function can_use_rest_action($route_action, $controller, $action)
        {
            if (current_user_can('show_all_prenotazioni')) {
                return true;
            }

            if ($controller === 'prenotazioni') {
                return in_array($route_action, ['read', 'save'], true);
            }

            return false;
        }
    }
    $BASE = Fab_Calendarioprenotazioni::getInstance();
} else {
    include_once(ABSPATH . 'wp-admin/includes/plugin.php');
    if (!is_plugin_active('fab-base-plugin/fab-base.php')) {
        echo "Questo plugin richiede fab-base";
    }
}
