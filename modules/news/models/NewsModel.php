<?php

class NewsModel extends Model
{
    function __construct()
    {
        parent::__construct();
        DB::setTable('data_rus');
    }

    public static function getNews()
    {
        DB::select("id, text");
        $result = DB::getResult();
        return $result;
    }

    public static function addNews()
    {
        $text = $_POST['text'];
        DB::insert(array('text' => $text));
    }

    public static function updateNews()
    {
        $text = $_POST['text'];
        $id = $_POST['id'];
        DB::update(array('text' => $text), array('id' => $id));
    }


    public static function delNews()
    {
        $id = (int) $_POST['id'];
        DB::delete(Array('id' => $id));
    }
}
