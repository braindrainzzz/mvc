<?php

class View
{
    private $smarty;
    private $url;

    public function __construct()
    {
        $this->smarty = new Smarty();
    }


    public function render($name)
    {
        $this->url = "http://".$_SERVER['SERVER_NAME']."/mvc";
        $this->smarty->assign('url', $this->url);

        require 'views/header.tpl';

        $mod_name = Url::getModuleName();
        if ($_GET['url'] === 'index' || empty($_GET['url']))
            $this->smarty->display('modules/index/views/index.tpl');
        else
            $this->smarty->display('modules/'. $mod_name . '/views/' . $name . '.tpl');

        require 'views/footer.tpl';

    }
}