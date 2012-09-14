<?php

class Firstboot
{
    private $firstmod;
    private $routs;
    private $modules = array();

    public function __construct()
    {
        require 'libs/controller.php';
        require 'libs/module.php';
        require 'libs/model.php';
        require 'libs/view.php';
        require 'libs/DB.class.php';


        $this->routs = require 'config.php';
        foreach ($this->routs as $name) {
            $this->modules[] = $name;
            ModuleManager::loadMod($name);
        }

        $url = isset($_GET['url']) ? $_GET['url'] : null;
        $url = rtrim($url, '/');
        $url = explode('/', $url);
        if (empty($url) || $url == 'index') {
            $this->firstmod = new IndexModule();
        }

    }


}