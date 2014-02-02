<?php
use \Pimple;
use Illuminate\Database\Capsule\Manager as Capsule;
$container = new \Phpmig\Pimple\Pimple();
$capsule = new \Illuminate\Database\Capsule\Manager();
$config = include('./app/config/database.php');
$config = $config['connections']['mysql'];
$capsule->addConnection($config);
$container['config'] = $config;

$container['db'] = $container->share(function($c) {
    return new PDO("mysql:host=" . $c['config']['host'] . ";dbname=" . $c['config']['database'], $c['config']['username'], $c['config']['password']);
});

$container['schema'] = $container->share(function($c) {
    /* Bootstrap Eloquent */
    $capsule = new Capsule;
    $capsule->addConnection($c['config']);
    $capsule->setAsGlobal();
    /* Bootstrap end */

    return Capsule::schema();
});
// replace this with a better Phpmig\Adapter\AdapterInterfaceo
$container['phpmig.adapter'] = $container->share(function() use ($container) {
    return new Phpmig\Adapter\PDO\Sql($container['db'], 'migrations');
});

$container['phpmig.migrations_path'] = __DIR__ . DIRECTORY_SEPARATOR . 'migrations';

// You can also provide an array of migration files
// $container['phpmig.migrations'] = array_merge(
//     glob('migrations_1/*.php'),
//     glob('migrations_2/*.php')
// );

return $container;