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
            $this->data['users'] = new \WP_User_Query( $args );
            //$this->data['rows'] = get_users($args);
            //echo \fab\functions::print_r($this->data['rows']);
        }

        public function default_forms_fields()
        {
            $html = '';
            $html .= \fab\functions::html_input_search('Email', 'q', $this->params['q']);
            return $html;
        }
    }
}
