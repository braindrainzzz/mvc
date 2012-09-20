<?php

class Firstboot
{
    private $routs;
    private $modules = array();


    public function __construct()
    {
        require 'libs/Smarty/Smarty.class.php';
        require 'libs/controller.php';
        require 'libs/module.php';
        require 'libs/model.php';
        require 'libs/view.php';
        require 'libs/DB.class.php';
        require 'libs/URL.php';
        require 'libs/Session.php';


        $this->routs = require 'config.php';
        $temp = $this->routs['modules'] ;
        foreach ($temp as $name) {
            $this->modules[] = $name;
            ModuleManager::loadMod($name);
        }

        $url = isset($_GET['url']) ? $_GET['url'] : null;
        $url = rtrim($url, '/');
        $url = explode('/', $url);
        if (empty($_GET['url']) || $_GET['url'] == 'index') {
            ModuleManager::loadMod('index');
        }

    }


}