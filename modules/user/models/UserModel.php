<?php

class UserModel extends Model
{
    public function __construct()
    {
        parent::__construct();
        DB::setTable('rus_user');
    }

    public static function getUsers()
    {
        DB::select("id, login, role");
        $result = DB::getResult();
        return $result;
    }

    public static function delete($id)
    {
        DB::delete(Array('id' => $id));
    }

    public static function addUser($data)
    {
        DB::insert(array(
            'login' => $data['login'],
            'password' => $data['password'],
            'role' => $data['role']
        ));

    }

}