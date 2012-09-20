<?php

class NewsController extends Controller
{
    function __construct() {
        parent::__construct();
        Session::init();
        $logged = Session::get('loggedIn');
        if ($logged == false) {
            Session::destroy();
            exit;
        }
    }

    function index()
    {
        $this->view->render('index');
    }

    function logout()
    {
        Session::destroy();
        exit;
    }

}