<?php

    require_once "../vendors/symfony/class-loader/Symfony/Component/ClassLoader/UniversalClassLoader.php";
    require '../vendors/autoload.php';

    use Symfony\Component\ClassLoader\UniversalClassLoader;

    $loader = new UniversalClassLoader();
    $loader->useIncludePath(true);
    $loader->register();

    // start app
    core\App::run();


    // test response
    var_dump(core\App::$container['Response']);

    // with die at TRUE and erasePrevBuffer at TRUE the buffer will contain only this response
    // if not all old or/and next content in buffer will be append
    $params = array(
        'die' => TRUE,
        'erasePrevBuffer' => TRUE,
    );
    // JSON RESPONSE
    core\App::$container['Response']->sendResponse(
        array(
            "prénom" => "nicolas",
            "nom" => "portier",
            "sousbranche" => array(
                "age" => "21"
            )
        ),
        'html',
        $params
    );

    /* //XML RESPONSE
    core\App::$container['Response']->sendResponse(
        array(
            "prénom" => "nicolas",
            "nom" => "portier",
            "sousbranche" => array(
                "age" => "21"
            )
        ),
        'xml',
        $params
    );
    */

    /* //HTML RESPONSE
    core\App::$container['Response']->sendResponse(
        array(
            "prénom" => "nicolas",
            "nom" => "portier",
            "sousbranche" => array(
                "age" => "21"
            )
        ),
        'html',
        $params
    );
    */

    var_dump(array(core\App::$container['Response']->getStatus() => core\App::$container['Response']->is("successful")));

    var_dump(core\App::$container['Response']);