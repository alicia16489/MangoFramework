<?php
require_once("./core/App.php");
$time_start = microtime(true);

core\App::run();

$time_end = microtime(true);
$time = $time_end - $time_start;

echo"<br>";
echo $time;