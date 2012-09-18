<?php

class Model
{
    private $db;

    public function __construct()
    {
        $data = require '../config.php';
        $_data = $data['DB'] ;
        $this->db =  DB::connect($_data['host'], $_data['user'], $_data['pass'], $_data['name']);
    }


}