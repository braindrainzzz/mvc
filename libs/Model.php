<?php

class Model
{
    private $db;
    private $data;

    public function __construct()
    {
        $this->data = Model::DBdata();
        $this->db =  DB::connect($this->data['host'], $this->data['user'], $this->data['pass'], $this->data['name']);
    }

    public static function DBdata()
    {
        $data = require 'config.php';
        return $data['db'];
    }

}