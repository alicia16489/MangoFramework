<?php

namespace core\components;
class RessourceMapException extends \Exception{}

class RessourceMap
{
  public $ressources = array();
  const physicalListPath = "./ressources/physical/list.php";
  const logicListPath = "./ressources/logic";

  public function __construct()
  {
    $this->ressources['physical'] = $this->getPhysical();
    $this->ressources['logic'] = $this->getLogic();
  }

  private function getPhysical()
  {
    if (!file_exists("./ressources/physical/list.php"))
      throw new RessourceMapException("Missing physical ressource list from ORM migrate command, path : " . $this::physicalListPath);

    if (!is_readable("./ressources/physical/list.php"))
      throw new RessourceMapException("File not readable, premission denied, path : " . $this::physicalListPath);

    $physicalList = include($this::physicalListPath);

    if (!is_array($physicalList))
      throw new RessourceMapException($this::physicalListPath . " do not return a array.");

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