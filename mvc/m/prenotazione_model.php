<?php

namespace fabcalpre;

class prenotazione_model extends \fab\fab_model
{
    public $name = 'prenotazione';
    public $prefix_plugin_table = 'fabcalpre_';
    public $filter_cols = array(
        "id_user" => array("default" => "", "type" => "="),
        "id_risorsa" => array("default" => "", "type" => "="),
        "deleted" => array("default" => "0", "type" => "=")
    );
    public $sort = "id DESC";
    public $paging = 30;

    public $belongs_to = array(
        'risorsa' =>
        array(
            'table_name' => 'fabcalpre_risorsa',
            'cols' => 'fabcalpre_risorsa.*',
            'conditions' => '',
            'order' => '',
            'foreign_key' => 'id_risorsa',
            'primary_key' => 'id'
        )
    );
}
