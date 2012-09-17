<?php

class Controller
{
    public $view;
    public $model;

    function __construct()
    {
        $this->view = new View();
    }

    public function loadModel($name)
    {
        $path = 'modules/' . $name . '/models/' . $name . 'model.php';
        $model = $name . 'Model';
        if (file_exists($path)) {
            require $path;
            $this->model = new $model;
        }

    }
}