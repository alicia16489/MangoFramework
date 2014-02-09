<?php
//Here, you have to add all your external dependencies like SDK, factory, so they can be access by the container. Specify the full namespace name.
// example : "FB" => "\Module\Facebook\fb-sdk", can be use later : Container::make('FB')
return array(
    'providers' => array(
        'UserFactory' => 'factories\UserFactory'
    )
);