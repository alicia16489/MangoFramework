<?php

require_once "../vendors/symfony/class-loader/Symfony/Component/ClassLoader/UniversalClassLoader.php";
require '../vendors/autoload.php';

use Symfony\Component\ClassLoader\UniversalClassLoader;

$loader = new UniversalClassLoader();
$loader->useIncludePath(true);
$loader->registerNamespaces(array(
  "core" => __DIR__."/../app"
));

$loader->register();

$container = new Pimple();

// start app
core\App\App::run();