<?php

class ModuleManager
{

    static $_modules = array();


    public static function loadMod($mod_name)
    {
        $mod_path = 'modules/' . $mod_name . '/' . $mod_name . 'Module.php';
        if (file_exists($mod_path)) {
            include_once($mod_path);
        }
    }
}
