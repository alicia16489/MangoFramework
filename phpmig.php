<?php

require_once "./vendors/symfony/class-loader/Symfony/Component/ClassLoader/UniversalClassLoader.php";
require './vendors/autoload.php';

use Symfony\Component\ClassLoader\UniversalClassLoader;
use \Phpmig\Adapter;
use core\Container;

$loader = new UniversalClassLoader();
$loader->useIncludePath(true);
$loader->registerNamespaces(array(
   'core' => __DIR__.'./app/core'
));

$container = core\Container::getInstance();

// replace this with a better Phpmig\Adapter\AdapterInterface
$container['phpmig.adapter'] = new Adapter\PDO\Sql($container['db'], 'migrations');

$container['phpmig.migrations_path'] = __DIR__ . DIRECTORY_SEPARATOR . 'migrations';

// You can also provide an array of migration files
// $container['phpmig.migrations'] = array_merge(
//     glob('migrations_1/*.php'),
//     glob('migrations_2/*.php')
// );

return $container;