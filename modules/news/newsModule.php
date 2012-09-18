<?php

class NewsModule extends Module
{
    public function __construct()
    {
        parent::loadController('news');

    }
}