<?php

namespace fabcalpre;

if (!class_exists('fabcalpre\logs_controller')) {
    class logs_controller extends \fab\fab_controller
    {
        public $name = 'logs';
        public $models_name = array('log');

        public function home()
        {
            wp_enqueue_script('vuetify');
            wp_enqueue_script('vue-mixin_datatable', FAB_BASE_PLUGIN_DIR_URL . 'js/vue/mixin_datatable.js', array('vuetify'), '1.3');
            wp_enqueue_script('vue-base-datatable', FAB_BASE_PLUGIN_DIR_URL . 'js/vue/base_datatable.js', array('vue-mixin_datatable'), '1.1');
            wp_localize_script(
                'vue-base-datatable',
                'wpLocalizedObj',
                array()
            );
            wp_enqueue_style('font-roboto', 'https://fonts.googleapis.com/css?family=Roboto:100,300,400,500,700,900');
            wp_enqueue_style('materialdesignicons', 'https://cdn.jsdelivr.net/npm/@mdi/font@4.x/css/materialdesignicons.min.css');
            wp_enqueue_style('vuetify', 'https://cdn.jsdelivr.net/npm/vuetify@2.x/dist/vuetify.min.css');
        }

        public function add_log($data_to_save)
        {
            if (
                !isset($data_to_save['category']) or
                !isset($data_to_save['subject']) or
                !isset($data_to_save['message'])
            ) {
                return false;
            }
            $data = [];
            $data['id'] = 0;
            $data['category'] = $data_to_save['category'] ?? '';
            $data['subject'] = $data_to_save['subject'] ?? '';
            $data['message'] = $data_to_save['message'] ?? '';
            $data['json_data'] = $data_to_save['json_data'] ?? '';
            $data['ip'] = $_SERVER['REMOTE_ADDR'];
            return $this->log->save($data);
        }

        public function ajax_csv()
        {
            if (isset($_GET['csv_action'])) {
                if (method_exists($this, $_GET['csv_action'])) {
                    $this->{$_GET['csv_action']}();
                } else {
                    echo "Non esiste: " . $_GET['csv_action'];
                    exit();
                }
            } else {
                $this->home();
            }

            header("Content-type: application/x-msdownload", true, 200);
            header("Content-Disposition: attachment; filename=export.csv");
            header("Pragma: no-cache");
            header("Expires: 0");

            //\fab\functions::print_r($this->params);

            $newline = PHP_EOL;
            $csv = "";
            if (count($this->data['rows']) > 0) {
                $sep = "";
                if (!isset($this->data['cols'])) {
                    $keys = array_keys($this->data['rows'][0]);
                    $this->data['cols'] = array();
                    foreach ($keys as $key => $value) {
                        $this->data['cols'][$value] = '';
                    }
                }
                foreach ($this->data['cols'] as $col => $default_value) {
                    $csv .= $sep . \fab\functions::clean_col_name($col);
                    $sep = ";";
                }
                $csv .= $newline;
                foreach ($this->data['rows'] as $row) {
                    $sep = "";
                    foreach ($this->data['cols'] as $col => $default_value) {
                        if (strpos($col, '.') > 0) {
                            $split = preg_split('/\./', $col);
                            $relation = $split[0];
                            $col = $split[1];
                            if (isset($row[$relation][$col])) {
                                $csv .= $sep . \fab\functions::clean_col_value($col, $row[$relation][$col], false);
                                $sep = ";";
                            }
                        } else {
                            if (isset($row[$col])) {
                                $csv .= $sep . \fab\functions::clean_col_value($col, $row[$col], false);
                                $sep = ";";
                            }
                        }
                    }

                    $csv .= $newline;
                }
            }
            echo $csv;
            exit();
        }
    }
}
