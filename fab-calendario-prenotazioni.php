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

error_reporting(E_ALL | E_WARNING | E_NOTICE);
ini_set('display_errors', TRUE);

if (!defined(__NAMESPACE__ . '\FAB_DEBUG')) define(__NAMESPACE__ . '\FAB_DEBUG', 'debug');
if (!defined(__NAMESPACE__ . '\FAB_PLUGIN_DIR_PATH')) define(__NAMESPACE__ . '\FAB_PLUGIN_DIR_PATH', plugin_dir_path(__FILE__));
if (!defined(__NAMESPACE__ . '\FAB_PLUGIN_DIR_URL')) define(__NAMESPACE__ . '\FAB_PLUGIN_DIR_URL', plugin_dir_url(__FILE__));

if (class_exists('\fab\Fab_Base')) {

    class Fab_Calendarioprenotazioni extends \fab\Fab_Base
    {
        public $shortcode_name = 'fab-tesi-admin';
        public $PLUGIN_DIR_PATH = FAB_PLUGIN_DIR_PATH;
        public $PLUGIN_DIR_URL = FAB_PLUGIN_DIR_URL;
        public $DEBUG = FAB_DEBUG;
        public $NAMESPACE = __NAMESPACE__;
        public $default_controller = 'prenotazioni';
        public $current_controller = 'prenotazioni';
        public $upload_dir = 'files/fabcalpre';
        public $rewrite_login = false;

        public function plugins_loaded()
        {
            require_once FAB_PLUGIN_DIR_PATH . 'includes/fab_install_db.php';
            $install = new fab_install_db();
            $install->install_db();

            require FAB_PLUGIN_DIR_PATH . 'includes/service.php';

            parent::plugins_loaded();

            require FAB_PLUGIN_DIR_PATH . 'includes/settings.php';
            new settings($this);

            require_once FAB_PLUGIN_DIR_PATH . 'includes/shortcode.php';
            $this->shortcode = new shortcode($this, 'fab-calendario-prenotazioni');

            require_once FAB_PLUGIN_DIR_PATH . 'includes/shortcode_admin.php';
            $this->shortcode_admin = new shortcode_admin($this, 'fab-calendario-prenotazioni-admin');
        }
    }
    $Fab_Calendarioprenotazioni = Fab_Calendarioprenotazioni::getInstance();
} else {
    include_once(ABSPATH . 'wp-admin/includes/plugin.php');
    if (!is_plugin_active('fab-base-plugin/fab-base.php')) {
        echo "Questo plugin richiede fab-base";
    }
}
