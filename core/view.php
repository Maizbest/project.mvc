<?php

namespace App;

class View
{
    public function render(String $filename, array $data = null)
    {
        require_once __DIR__."/../views/header.php";
        require_once __DIR__."/../views/".$filename.".php";
    }

    
}