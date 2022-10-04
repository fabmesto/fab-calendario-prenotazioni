<?php

namespace fabcalpre;

class log_model extends \fab\fab_model
{
    public $name = 'log';
    public $prefix_plugin_table = 'h2o_';
    public $sort = "id DESC";
    public $paging = 20;
    public $filter_cols = array(
        "category" => array("default" => "", "type" => "="),
        "subject" => array("default" => "", "type" => "%%"),
        "message" => array("default" => "", "type" => "%%"),
        "ip" => array("default" => "", "type" => "%%"),
        "deleted" => array("default" => "0", "type" => "="),
    );

    public $belongs_to = array(
        'user_insert' =>
        array(
            'table_name' => 'users',
            'cols' => 'users.ID, users.display_name, users.user_email',
            'conditions' => '',
            'order' => '',
            'foreign_key' => 'id_user_insert',
            'primary_key' => 'ID'
        ),
        'user_update' =>
        array(
            'table_name' => 'users',
            'cols' => 'users.ID, users.display_name, users.user_email',
            'conditions' => '',
            'order' => '',
            'foreign_key' => 'id_user_update',
            'primary_key' => 'ID'
        ),
    );
}
