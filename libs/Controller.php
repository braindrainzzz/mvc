<?php

class Controller
{
    public $model;

    public function __construct()
    {
        $this->view = new View();
    }

    public function loadModel($name)
    {
        $path = 'modules/' . $name . '/models/' . $name . 'models.php';
        $model = $name . 'Model';
        if (file_exists($path)) {
            require $path;
            $this->model = new $model;
        }

    }
}