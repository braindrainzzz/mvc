<?php

class NewsController extends Controller
{
    function __construct() {
        parent::__construct();

        $this->index();
        Session::init();
        $logged = Session::get('loggedIn');
        if ($logged == false) {
            Session::destroy();
            exit;
        }
    }

    function index()
    {
        $result = NewsModel::getNews();
        parent::$view->assign('news', $result);
        parent::$view->assign('c', 0);
        $this->view->render('index');
    }

    function logout()
    {
        Session::destroy();
        exit;
    }

    public static function getNews()
    {
        NewsModel::getNews();
    }

    public static function addNews()
    {
        NewsModel::addNews();
    }

    public static function updateNews()
    {
        NewsModel::updateNews();
    }


    public static function delNews()
    {
        NewsModel::delNews();
    }
}