<?php

class Module
{
    protected $controller;

    public function __construct()
    {

    }

    public function loadController($name)
    {
        $path = 'modules/' . $name . '/controller/' . $name . 'Controller.php';
        $controller = $name . 'Controller';
        print_r($controller);
        if (file_exists($path)) {
            require $path;
            $this->controller = new $controller();
        }
        return $this->controller;
    }
}
