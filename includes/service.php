<?php

namespace fabcalpre;

if (!class_exists('fabcalpre\service')) {
    class service
    {

        public static function isWeekend($date)
        {
            return (date('N', strtotime($date)) >= 6);
        }

        public static function add_log($data)
        {
            return Fab_Calendarioprenotazioni::getInstance()->call_method_controller('logs', 'add_log', $data);
        }
    }
}
