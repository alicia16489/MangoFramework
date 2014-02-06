<?php

namespace core\components;

use core\App;

abstract class Controller
{
    protected static $response;
    protected static $controller;

    public function beforeMain()
    {
        self::$controller = strtolower(str_replace('Controller', '', str_replace('controllers\\', '', get_called_class())));
        self::$response = App::$container['Response'];
    }


}