<?php

namespace controllers;

use core\components\Rest;
use core\App;
use Illuminate\Container\Container;
use models\User;

class UserController extends Rest
{
    public $routes = array(
        "/user/toto/:id/name/:name" => array("method" => "myMethod", "cond" => array(":id" => "\d+")),
        "/user/toto" => "toto",
        //"/user/:id" => "myGet"
    );

    public function myGet()
    {
     $u = Container::make('UserFactory');

    }

    public function toto()
    {
        echo "HERE DUDE";
        self::$response->setData('dfdfd','dds');
    }

    public function index()
    {
        /**
         * If you need some treatment before the default behaviour
         * Insert your code here
         */

        //self::$response->setData('dfdfd','dds');

        /**
         * Comment this line to prevent the default behaviour
         */
        parent::index();
    }

    public function get($id)
    {
        /**
         * If you need some treatment before the default behaviour
         * Insert your code here
         */

        /**
         * Comment this line to prevent the default behaviour
         */
        parent::get($id);
    }

    public function post()
    {
        /**
         * If you need some treatment before the default behaviour
         * Insert your code here
         */

        echo "AKAKAKAKAK";

        /**
         * Comment this line to prevent the default behaviour
         */
        parent::post();
    }

    public function put($id)
    {
        /**
         * If you need some treatment before the default behaviour
         * Insert your code here
         */

        /**
         * Comment this line to prevent the default behaviour
         */
        parent::put($id);
    }

    public function delete($id)
    {
        /**
         * If you need some treatment before the default behaviour
         * Insert your code here
         */

        /**
         * Comment this line to prevent the default behaviour
         */
        parent::delete($id);
    }
}