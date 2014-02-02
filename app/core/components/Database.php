<?php namespace core\components;

use \Illuminate\Database\Capsule\Manager as Capsule;
Class Database {

    private $config;
    private static $capsule;
    private $connection;
    private static $instance;

    public static function getInstance()
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
            //throw Exception
        }
    }

    public function getConnection()
    {
       return $this->connection;
    }

    public function getSchema()
    {
        return Capsule::schema();
    }

    public function getConfig()
    {
        return $this->config;
    }
}