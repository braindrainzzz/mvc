<?php

class UserController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->index();
        Session::init();
        $logged = Session::get('loggedIn');
        $role = Session::get('role');
        if ($logged == false || $role != 'owner') {
            Session::destroy();
            exit;
        }
        $this->index();
    }

    function index()
    {
        $this->view->render('index');
    }

    public function addUser()
    {
        $data = array();
        $data['login'] = validation::filter($_POST['login']);
        $data['password'] = validation::filter($_POST['password']);
        $data['role'] = validation::filter($_POST['role']);
        UserModel::addUser($data);
    }

    public static function updateUser($id)
    {
        $data = array();
        $data['id'] = $id;
        $data['login'] = validation::filter($_POST['login']);
        $data['password'] = validation::filter($_POST['password']);
        $data['role'] = validation::filter($_POST['role']);
        UserModel::updateUser($data);
    }

    public function delete($id)
    {
        UserModel::delete($id);
    }
}