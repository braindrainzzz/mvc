<?php

class ErrorModule extends Module
{
    public function __construct()
    {
        $this->loadController('error');

    }
}