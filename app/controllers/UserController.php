<?php

namespace controllers;
use factories;
use core\components\Rest;
use core\App;
use core\Container;
use models\User;

class UserController extends Rest
{
    public $routes = array(
        "/user/toto/:id/name/:name" => array("method" => "myMethod", "cond" => array(":id" => "\d+")),
        "/user/myget" => "myGet"
    );

    public function myGet()
    {
        $factory = new factories\UserFactory();
        //$factory = Container::make('UserFactory');
        self::$response->setData($factory->countUser());
    }

    public function index()
    {
        /**
         * If you need some treatment before the default behaviour
         * Insert your code here
         */

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