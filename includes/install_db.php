<?php

namespace fabcalpre;

require_once FAB_BASE_PLUGIN_DIR_PATH . '/fab_base/fab_install_db.php';

if (!class_exists('fabcalpre\install_db')) {
  class install_db extends \fab\fab_install_db
  {
    public $db_version = '0.8';
    public $db_version_key = 'fabcalpre-db-version';
    public $prefix_plugin_table = 'fabcalpre_';

    public function prepare_install_db()
    {
      global $wpdb;
      $charset_collate = $wpdb->get_charset_collate();
      $this->query_install_db = array(
        "CREATE TABLE IF NOT EXISTS " . $wpdb->prefix . $this->prefix_plugin_table . "risorsa (
          id mediumint(9) NOT NULL AUTO_INCREMENT,
          `nome` tinytext NOT NULL DEFAULT '',
          `note` text NOT NULL DEFAULT '',
          `ordinamento` mediumint(9) NOT NULL DEFAULT '0',
          `id_user_insert` int(11) NOT NULL DEFAULT '0',
          `id_user_update` int(11) NOT NULL DEFAULT '0',
          `date_update` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
          `date_insert` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
          `deleted` tinyint(1) NOT NULL DEFAULT '0',
          PRIMARY KEY  (id)
          ) " . $charset_collate . ";",

        "CREATE TABLE IF NOT EXISTS " . $wpdb->prefix . $this->prefix_plugin_table . "prenotazione (
          id mediumint(9) NOT NULL AUTO_INCREMENT,
          `id_user` mediumint(9) NOT NULL DEFAULT '0',
          `id_risorsa` mediumint(9) NOT NULL DEFAULT '0',
          `data_inizio` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
          `data_fine` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
          `note` text NOT NULL DEFAULT '',
          `confirmed` tinyint(1) NOT NULL DEFAULT '0',
          `id_user_insert` int(11) NOT NULL DEFAULT '0',
          `id_user_update` int(11) NOT NULL DEFAULT '0',
          `date_update` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
          `date_insert` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
          `deleted` tinyint(1) NOT NULL DEFAULT '0',
          PRIMARY KEY  (id)
          ) " . $charset_collate . ";",

      );

      $this->query_alter_db = array(
        "ALTER TABLE " . $wpdb->prefix . $this->prefix_plugin_table . "prenotazione ADD `confirmed` tinyint(1) NOT NULL DEFAULT '0' AFTER `note`;",
      );
    }

    public function install_roles_and_caps()
    {
      $this->install_roles([
        'operatori_calendario',
      ]);
      // add custom cap
      $custom_caps = [
        'administrator' => [
          "show_all_prenotazioni" => true,

        ],
        'operatori_calendario' => [
          "show_all_prenotazioni" => true,
        ],
      ];
      $this->install_caps($custom_caps);
    }
  }
}
