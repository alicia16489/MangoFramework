<?php

namespace core\components;
class controllerMapException extends \Exception
{
}

class ControllerMap
{
    public $controllers = array();
    const physicalListPath = "./utils/list.php";
    const logicListPath = "./controllers/";

    public function __construct()
    {
        $this->controllers['physical'] = $this->getPhysical();
        $this->controllers['logic'] = $this->getLogic();
    }

    private function getPhysical()
    {
        if (!file_exists($this::physicalListPath))
            throw new controllerMapException("Missing physical controller list from ORM migrate command, path : " . $this::physicalListPath);

        if (!is_readable($this::physicalListPath))
            throw new controllerMapException("File not readable, premission denied, path : " . $this::physicalListPath);

        $physicalList = include($this::physicalListPath);

        if (!is_array($physicalList))
            throw new controllerMapException($this::physicalListPath . " do not return a array.");

        return $physicalList;
    }

    private function getLogic()
    {
        $files = array();
        $dir = $this::logicListPath;
        if (false !== ($dh = opendir($dir))) {
            while (false !== ($filename = readdir($dh))) {
                if ($filename != "." && $filename != "..")
                    $files[] = str_replace(".php", "", $filename);
            }
        } else {
            // Exception
        }

        return $files;
    }
}