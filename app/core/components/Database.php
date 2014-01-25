<?php namespace core\components;

use \Illuminate\Database\Capsule\Manager as Capsule;
Class Database {

    private static $config;
    private static $capsule;
    private static $connection;
    private static $instance;

    public function getInstance()
    {
        if (!is_null(self::$instance)) {
            return self::$instance;
        }
        else {
            $capsule = new \Illuminate\Database\Capsule\Manager();
            return new self($capsule);
        }
    }
    private function __construct(Capsule $capsule)
    {
        self::$config = include('../../config/database.php');
        self::$capsule = $capsule;
        $defaultDB = self::$config['default'];
        if (!empty(self::$config['connections'][$defaultDB])) {
            try {
                $capsule->addConnection(self::$config['connections'][$defaultDB]);
            }
            catch (\PDOException $e) {
                echo $e->getMessage();
            }

            $capsule->bootEloquent();
            self::$connection = $capsule->getConnection();
        }
        else {

        }
    }

    public static function getConnection()
    {
       return self::$connection;
    }

    public function getSchema()
    {
        return Capsule::schema();
    }
}