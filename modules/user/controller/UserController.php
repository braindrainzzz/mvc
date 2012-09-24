<?php

class UserController extends Controller
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

    public function addUser($data)
    {
        $data = array();
        $data['login'] = $_POST['login'];
        $data['password'] = $_POST['password'];
        $data['role'] = $_POST['role'];
        UserModel::addUser($data);
    }
}