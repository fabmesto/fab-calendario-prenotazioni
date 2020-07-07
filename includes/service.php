<?php

namespace fabcalpre;

if (!class_exists('fabcalpre\service')) {
    class service
    {

        public static function isWeekend($date)
        {
            return (date('N', strtotime($date)) >= 6);
        }
    }
}
