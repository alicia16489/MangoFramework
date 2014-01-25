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


?>

<br />
<font size='1'><table class='xdebug-error xe-notice' dir='ltr' border='1' cellspacing='0' cellpadding='1'>
        <tr><th align='left' bgcolor='#f57900' colspan="5"><span style='background-color: #cc0000; color: #fce94f; font-size: x-large;'>( ! )</span> Notice: Array to string conversion in C:\wamp\www\Dropbox\Framework\app\core\components\Response.php on line <i>224</i></th></tr>
        <tr><th align='left' bgcolor='#e9b96e' colspan='5'>Call Stack</th></tr>
        <tr><th align='center' bgcolor='#eeeeec'>#</th><th align='left' bgcolor='#eeeeec'>Time</th><th align='left' bgcolor='#eeeeec'>Memory</th><th align='left' bgcolor='#eeeeec'>Function</th><th align='left' bgcolor='#eeeeec'>Location</th></tr>
        <tr><td bgcolor='#eeeeec' align='center'>1</td><td bgcolor='#eeeeec' align='center'>0.0003</td><td bgcolor='#eeeeec' align='right'>251912</td><td bgcolor='#eeeeec'>{main}(  )</td><td title='C:\wamp\www\Dropbox\Framework\app\index.php' bgcolor='#eeeeec'>..\index.php<b>:</b>0</td></tr>
        <tr><td bgcolor='#eeeeec' align='center'>2</td><td bgcolor='#eeeeec' align='center'>0.0093</td><td bgcolor='#eeeeec' align='right'>591968</td><td bgcolor='#eeeeec'>core\components\Response->sendResponse(  )</td><td title='C:\wamp\www\Dropbox\Framework\app\index.php' bgcolor='#eeeeec'>..\index.php<b>:</b>18</td></tr>
        <tr><td bgcolor='#eeeeec' align='center'>3</td><td bgcolor='#eeeeec' align='center'>0.0097</td><td bgcolor='#eeeeec' align='right'>592488</td><td bgcolor='#eeeeec'>core\components\Response->send(  )</td><td title='C:\wamp\www\Dropbox\Framework\app\core\components\Response.php' bgcolor='#eeeeec'>..\Response.php<b>:</b>234</td></tr>
    </table></font>