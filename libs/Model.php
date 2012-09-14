<?php

class Model
{

    public function __construct()
    {
        DB::connect('localhost', 'root', '123', 'mvcSource');
    }
}