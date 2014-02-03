<?php

namespace core\components;
class resourceMapException extends \Exception{}

class resourceMap
{
  public $resources = array();
  const physicalListPath = "./resources/physical/list.php";
  const logicListPath = "./resources/logic";

  public function __construct()
  {
    $this->resources['physical'] = $this->getPhysical();
    $this->resources['logic'] = $this->getLogic();
  }

  private function getPhysical()
  {
    if (!file_exists("./resources/physical/list.php"))
      throw new resourceMapException("Missing physical resource list from ORM migrate command, path : " . $this::physicalListPath);

    if (!is_readable("./resources/physical/list.php"))
      throw new resourceMapException("File not readable, premission denied, path : " . $this::physicalListPath);

    $physicalList = include($this::physicalListPath);

    if (!is_array($physicalList))
      throw new resourceMapException($this::physicalListPath . " do not return a array.");

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