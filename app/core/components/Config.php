<?php

namespace core\components;

class Config
{
    public function getResponse()
    {
        $config = include('./config/response.php');
        return $config;
    }
}