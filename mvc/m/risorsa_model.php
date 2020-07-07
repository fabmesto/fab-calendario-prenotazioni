<?php

namespace fabcalpre;

class risorsa_model extends \fab\fab_model
{
    public $name = 'risorsa';
    public $prefix_plugin_table = 'fabcalpre_';
    public $filter_cols = array(
        "nome" => array("default" => "", "type" => "%%"),
        "deleted" => array("default" => "0", "type" => "=")
    );
}
