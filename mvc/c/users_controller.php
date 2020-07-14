<?php

namespace fabcalpre;

if (!class_exists('fabcalpre\users_controller')) {
    class users_controller extends \fab\fab_controller
    {
        public $name = 'users';
        public $models_name = array();

        public function home()
        {
            $this->params['paging'] = 20;
            $this->params['pag'] = 1;
            $this->params['q'] = '';
            if (isset($_GET['q']) && $_GET['q'] != '') {
                $this->params['q'] = $_GET['q'];
            }
            if (isset($_GET['pag']) && $_GET['pag'] != '') {
                $this->params['pag'] = $_GET['pag'];
            }

            // main user query
            $args  = array(
                'order' => 'DESC',
                'orderby' => 'ID',
                // return all fields
                //'fields' => array('ID', 'user_email', 'display_name','user_registered'),
                'fields' => 'all',
                'count_total' => true,
                'paged' => $this->params['pag'],
                'number' =>  $this->params['paging'],
            );
            if ($this->params['q']) {
                $args['search'] = '*' . esc_attr($this->params['q']) . '*';
            }
            $this->data['users'] = new \WP_User_Query($args);
            //$this->data['rows'] = get_users($args);
            //echo \fab\functions::print_r($this->data['rows']);
        }

        public function edit()
        {
            $id_key = 'id_user';
            $this->params = \fab\functions::params_from_get(
                array($id_key => 0)
            );

            if ($this->params[$id_key] > 0) {
                $user = get_userdata($this->params[$id_key]);
                $this->data['row'] = $user;
                //$this->data['row']->info = $user_info;
            } else {
                $this->data['row'] = false;
            }
        }

        public function url_edit($id = false)
        {
            $id_key = 'id_user';
            return $this->parent->url($this->name, 'edit', array($id_key => $id));
        }

        public function default_forms_fields()
        {
            $html = '';
            $html .= \fab\functions::html_input_search('Email', 'q', $this->params['q']);
            return $html;
        }

        public function rest_save()
        {
            if (current_user_can('show_all_prenotazioni') != 1) {
                return array(
                    "code" => "error",
                    "message" => 'Non hai le autorizzazioni!',
                    "data" => array('post' => $_POST)
                );
            }

            $action = '';
            if (isset($_POST['action'])) {
                $action = $_POST['action'];
            }

            switch ($action) {
                case 'edit':
                    if (current_user_can('show_all_prenotazioni') == 1) {
                        $updates = array();
                        $keys_date = array('birth_date', 'documento_data');
                        foreach ($_POST['info'] as $key => $value) {

                            if (in_array($key, $keys_date)) {
                                $value = \fab\functions::date_to_sql($value);
                            }
                            $updates[$key] = update_user_meta($_POST['id_user'], $key, $value);
                        }
                        return array(
                            'code' => 'ok',
                            'message' => 'Salvato con successo!',
                            'data' => array('POST' => $_POST, 'updates' => $updates),
                        );
                    }
                    break;
            }
            return array(
                'code' => 'error',
                'message' => 'no action',
                'POST' => $_POST,
            );
        }
    }
}
