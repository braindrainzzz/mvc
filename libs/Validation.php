<?php

class validation
{

    public static function filter($str)
    {
        return stripslashes(trim(htmlspecialchars($str, ENT_QUOTES)));
    }
}
