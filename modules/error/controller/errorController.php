<?php

class ErrorController extends Controller {

    function __construct() {
        parent::__construct();
        $this->index();
    }

    function index() {
        $this->view->msg = 'Нет такой страницы';
        $this->view->render('index');
    }

}