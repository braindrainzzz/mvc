<?php

class NewsModel extends Model
{
    function __construct()
    {
        parent::__construct();

    }

    function getNews()
    {
        DB::select("id, text");
        $result = DB::getResult();
        return $result;
    }

    function addNews($text)
    {
        DB::insert(array('text' => $text));
    }
    }

    function updateNews($id, $text)
    {
        DB::update(array('text' => $text), array('id' => $id));
    }


    function delNews($id)
    {
        DB::delete(Array('id' => $id));
    }
