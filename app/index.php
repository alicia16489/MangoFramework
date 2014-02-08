<?php
require_once './core/App.php';
$time_start = microtime(true);

core\App::run();

$time_end = microtime(true);
$time = $time_end - $time_start;

/*echo"<br>";
echo "end: ".$time;*/


// TEST THE RESPONSE
/*var_dump(core\App::$container['Response']);*/


// test if response was successful
/*var_dump(array(core\App::$container['Response']->getStatus() => core\App::$container['Response']->is("successful")));

var_dump(core\App::$container['Response']);

$analysis = new utils\docGen(array('utils/htmlPattern.php'));
$analysis->create();*/
