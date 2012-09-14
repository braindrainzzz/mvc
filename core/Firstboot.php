<?php

class Firstboot
{
    public $firstmod;
    public $url;
    public $routs;
    public $modules = array();

    public function __construct()
    {
        $this->routs = require 'config.php';
        foreach ($this->routs as $name) {
            $modules[] = $name;
            ModuleManager::loadMod($name);
        }

        $url = isset($_GET['url']) ? $_GET['url'] : null;
        $url = rtrim($url, '/');
        $url = explode('/', $url);
        print_r($url);

        if (empty($url) || $url == 'index') {
            $this->firstmod = new IndexModule();
        }

    }


}