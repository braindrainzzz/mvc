<?php

class UserModule extends Module
{
    public function __construct()
    {
        parent::loadController('user');
        Controller::loadModel('User');

    }
}