<?php

require_once "../vendors/symfony/class-loader/Symfony/Component/ClassLoader/UniversalClassLoader.php";
require '../vendors/autoload.php';

use Symfony\Component\ClassLoader\UniversalClassLoader;

$loader = new UniversalClassLoader();
$loader->useIncludePath(true);
$loader->register();

// start app
core\App::run();
var_dump(core\App::$container['Response']);
core\App::$container['Response']->sendResponse(array("nom" => "nicolas", "test" => "lol", "test1" => array("soustest" => "tg")),
    $code = 200,
    $encode = TRUE,
    $replace = FALSE);
core\App::stop();

echo (core\App::$container['Response']->getLength());
?>