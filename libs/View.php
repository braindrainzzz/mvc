<?php

class View
{
    public $smarty;

    public function __construct()
    {
        $this->smarty = new Smarty();
    }


    public function render($name)
    {
        require 'views/header.tpl';
        if ($_GET['url'] === 'index')
            $this->smarty->display('modules/index/views/news.tpl');
        else
            $this->smarty->display('modules/' .$_GET['url'] . '/views/' . $_GET['url'] . '.tpl');
        require 'views/footer.tpl';

    }
}