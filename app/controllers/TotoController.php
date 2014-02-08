<?php

namespace controllers;

use core\components\Rest;
use core\App;
use models\User;

class TotoController extends Rest
{
    public $routes = array(
        "/user/toto/:id/name/:name" => array("method" => "myMethod", "cond" => array(":id" => "\d+")),
        "/user/toto" => "toto",
        "/user/:id" => "myGet"
    );

    public function show()
    {
        echo "fdfgsdf";
    }

}