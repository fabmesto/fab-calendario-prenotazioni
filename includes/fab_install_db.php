<?php

namespace fabcalpre;

if (!class_exists('fabcalpre\fab_install_db')) {
    class fab_install_db
    {
        public $db_version = '0.4';
        public $db_version_key = 'fabcalpre-db-version';
        public $prefix_plugin_table = 'fabcalpre_';
        public $query_install_db = array();
        public $query_alter_db = array();
        public $query_initialdata_db = array();

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

        public function install_db()
        {
            $this->prepare_install_db();

            global $wpdb;
            $installed_ver = get_option($this->db_version_key);

            if ($installed_ver != $this->db_version) {

                $this->install_roles();

                require_once ABSPATH . 'wp-admin/includes/upgrade.php';
                foreach ($this->query_install_db as $key => $query) {
                    dbDelta($query);
                }

                foreach ($this->query_alter_db as $key => $query) {
                    $wpdb->query($query);
                }

                foreach ($this->query_initialdata_db as $key => $query) {
                    $wpdb->query($query);
                }

                update_option($this->db_version_key, $this->db_version);
            }
        }

        public function install_roles()
        {
            $roles_name = array('dipendenti', 'operatori_calendario');
            foreach ($roles_name as $role_name) {
                $role = get_role($role_name);
                if ($role == null) {
                    $capabilities = array(
                        'read' => true
                    );
                    $role = add_role($role_name, __($role_name), $capabilities);
                }
            }

            // add custom cap
            $custom_caps = array(
                'administrator' => array(
                    "show_all_prenotazioni" => true,
                ),

                'operatori_calendario' => array(
                    "show_all_prenotazioni" => true,
                ),
            );

            foreach ($custom_caps as $role_name => $caps) {
                foreach ($caps as $custom_cap => $grant) {
                    foreach ($GLOBALS['wp_roles']->role_objects as $key => $role) {
                        //\fab\functions::print_r($role->name);
                        if ($role->name == $role_name && !$role->has_cap($custom_cap)) {
                            //\fab\functions::print_r($role);
                            $role->add_cap($custom_cap, $grant);
                        }
                    }
                }
            }
            //\fab\functions::print_r($role);
        }
    }
}
