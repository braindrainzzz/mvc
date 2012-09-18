<?php

class NewsController extends Controller
{
    function __construct()
    {
        parent::__construct();
        $this->index();
    }

    function index()
    {
        $this->view->render('index');
    }

}