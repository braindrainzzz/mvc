<?php

class URL
{
    public static function getModuleName()
    {
        $temp = strstr($_GET['url'], "/", true);
        return $temp;
    }
}
