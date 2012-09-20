<?php

class NewsModel extends Model
{
    function __construct()
    {
        parent::__construct();
        DB::setTable('data');
    }

    public static function getNews()
    {
        DB::select("id, text");
        $result = DB::getResult();
        return $result;
    }

    public static function addNews($text)
    {
        DB::insert(array('text' => $text));
    }

    public static function updateNews($id, $text)
    {
        DB::update(array('text' => $text), array('id' => $id));
    }


    public static function delNews($id)
    {
        DB::delete(Array('id' => $id));
    }
}
