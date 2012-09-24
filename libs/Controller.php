<?php

class Controller
{
    public $view;
    public static $model;

    function __construct()
    {
        $this->view = new View();
    }

    public static function loadModel($name)
    {
        $path = 'modules/' . $name . '/models/' . $name . 'Model.php';
        print_r($path) ;
        $model = $name . 'Model';
        if (file_exists($path)) {
            require $path;
            self::$model = new $model;
        }
        else self::$model = new ErrorModule();

    }
}