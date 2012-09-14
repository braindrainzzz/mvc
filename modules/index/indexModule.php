<?php

class IndexModule extends Module
{
    public function __construct()
    {
        $this->loadController('index');
    }
}