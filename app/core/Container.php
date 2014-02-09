<?php

namespace core;
use factories\UserFactory;
use core\components\Database;

class ContainerException extends \Exception
{
}

class Container extends \Pimple
{
    private static $container;

    public static function getInstance()
    {
        if (is_null(self::$container)) {
            self::$container = new self();
        }

        return self::$container;
    }

    public function __construct()
    {
        parse_str(file_get_contents("php://input"), $post_vars);
        $this['post'] = $post_vars;
        $this['dependencies'] = array(
            'Config' => __NAMESPACE__ . '\components\Config',
            'Request' => __NAMESPACE__ . '\components\Request',
            'Blueprint' => __NAMESPACE__ . '\components\Blueprint',
            'Router' => __NAMESPACE__ . '\components\Router',
            'Response' => __NAMESPACE__ . '\components\Response',
            'ControllerMap' => __NAMESPACE__ . '\components\ControllerMap',
            'Database' => __NAMESPACE__ . '\components\Database'
        );

        foreach ($this['dependencies'] as $key => $path) {
            if (!class_exists($path, true)) {
                throw new ContainerException('Missing components : ' . $key . ' at path : ' . $path);
            }
        }
    }

    public function loaders()
    {

        // Config
        $this['Config'] = function ($c) {
            return new $c['dependencies']['Config']();
        };

        // Request
        $this['Request'] = function ($c) {
            return new $c['dependencies']['Request']();
        };

        // Blueprint
        $this['Blueprint'] = function ($c) {
            return new $c['dependencies']['Blueprint']($c['Request']);
        };

        // Router
        $this['Router'] = function ($c) {
            return new $c['dependencies']['Router']($c['Blueprint']);
        };

        // Response
        $this['Response'] = function ($c) {
            return new $c['dependencies']['Response']();
        };

        // controllerMap
        $this['ControllerMap'] = function ($c) {
            return new $c['dependencies']['ControllerMap']();
        };

        // Database
        $this['Database'] = function () {
            return Database::getInstance();
        };
    }

    public static function make($name, $args = array())
    {

        if (!file_exists('./config/app.php')) {
            throw new Exception("Config file missing : ./config/app.php", 1);
            return false;
        }
        else {
            $providers = require_once './config/app.php';
            $providers = $providers['providers'];
            $provider = $providers[$name];
            if (!array_key_exists($name, $providers)) {
                throw new \Exception("No such providers :".$name, 1);
                return 42;
            }
            else {
                if(class_exists($provider, true)) {
                    return call_user_func_array(array(
                            new \ReflectionClass($provider), 'newInstance'),
                        $args);
                }
                throw new \Exception('Class not found :'.$provider);
                return false;
            }
        }
    }
}