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
core\App::$container['Response']->sendResponse(array("prénom" => "nicolas", "nom" => "portier", "sousbranche" => array("age" => "21")),
    $code = 200,
    $encode = TRUE,
    $replace = FALSE);

echo (core\App::$container['Response']->getLength());
?>