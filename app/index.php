<?php

    require_once "../vendors/symfony/class-loader/Symfony/Component/ClassLoader/UniversalClassLoader.php";
    require '../vendors/autoload.php';

    use Symfony\Component\ClassLoader\UniversalClassLoader;

    $loader = new UniversalClassLoader();
    $loader->useIncludePath(true);
    $loader->register();

    // start app
    core\App::run();


    // TEST THE RESPONSE
    var_dump(core\App::$container['Response']);

    // set the response data default
    core\App::$container['Response']->setData(array(
            "prénom" => "nicolas",
            "nom" => "portier",
            "sousbranche" => array(
                "age" => "21"
            )
        )
    );

    // set response type (json html or xml)
    core\App::$container['Response']->setType('html');

    // set pretty print or not to have a beautiful JSON print
    core\App::$container['Response']->setPrettyPrint(FALSE);

    // with die at TRUE and erasePrevBuffer at TRUE the buffer will contain only this response
    // if not all old or/and next content in buffer will be append
    $params = array(
        'die' => FALSE,
        'erasePrevBuffer' => FALSE,
    );

    // JSON RESPONSE
    core\App::$container['Response']->sendResponse($params);

    // test if response was successful
    var_dump(array(core\App::$container['Response']->getStatus() => core\App::$container['Response']->is("successful")));

    var_dump(core\App::$container['Response']);

    $analysis = new utils\docGen(array('utils/htmlPattern.php'));
    $analysis->create();