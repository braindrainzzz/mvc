<?php

class View
{
    public $smarty;
    private $url;

    public function __construct()
    {
        $this->smarty = new Smarty();
    }


    public function render($name)
    {

        require 'views/header.tpl';

        $mod_name = Url::getModuleName();
        if ($_GET['url'] === 'index' || empty($_GET['url']))
            $this->smarty->display('modules/index/views/index.tpl');
        else
            $this->smarty->display('modules/'. $mod_name . '/views/' . $name . '.tpl');

        require 'views/footer.tpl';

    }

    public function assign($var, $value)
    {
        $this->smarty->assign($var, $value);
    }
}