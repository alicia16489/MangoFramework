<?php

namespace core\components;
use core\App;

class BlueprintException extends \Exception
{
}

class Blueprint
{
  public $route;
  public $pathInfo;
  public $controller;
  public $type;
  public $lockRouter = false;
  public $exist = array();
  public $restMethod;
  public $method;
  public $options;

  private $patterns = array(
    "rest" => array(
      "#^\/[a-zA-Z0-9]+\/?$#",
      "#^\/[a-zA-Z0-9]+\/\d+$#"
    ),
    "logic" => "#^\/[a-zA-Z0-9]+\/?$#",
    "complexe" => array()
  );

  public function __construct(Request $request)
  {
    $this->pathInfo = $request->properties['REQUEST_OPTION'];
    $this->controller = ucfirst($request->properties['REQUEST_OPTION_PARTS'][1]).'Controller';
    $this->restMethod = $this->method = strtolower($request->properties['REQUEST_METHOD']);;
    $this->existAsLogic();
    $this->existAsPhysical();
  }

  private function existAsPhysical()
  {
    $physicalList = App::$container['controllerMap']->controllers['physical'];
    $entity = str_replace('Controller','',$this->controller);

    if (in_array($entity, $physicalList)) {
      $class = '\controllers\\' . $this->controller;

      if (class_exists($class)) {
        $this->exist['physical'] = true;
        return;
      }
    }
    $this->exist['physical'] = false;
  }

  private function existAsLogic()
  {
    $logicList = App::$container['controllerMap']->controllers['logic'];

    if (in_array($this->controller, $logicList)) {
      $class = '\controllers\\' . $this->controller;

      if (class_exists($class)) {
        $this->exist['logic'] = true;
        return;
      }
    }

    $this->exist['logic'] = false;
  }

  public function isLogic()
  {
    if (preg_match($this->patterns['logic'], $this->pathInfo)) {
      $class = 'controllers\logic\\' . $this->controller;
      $controller = new $class();

      if (method_exists($controller, 'get')) {
        return true;
      }
    }

    return false;
  }

  public function isSubLogic()
  {
    if (!preg_match($this->patterns['logic'], $this->pathInfo)) {
      $class = 'controllers\logic\\' . $this->controller;
      $controller = new $class();

      if (property_exists($class, "routes")) {
        foreach ($controller->routes as $route => $value) {
          if($route[0] != '/'){
            $route = '/'.$route;
          }
          if (is_array($value) && isset($value['cond'])) {
            if ($this->routeMatch($route, $value['cond'])) {
              $this->method = $value['method'];
              $this->route = $route;
              return true;
            }
          } else {
            if ($this->routeMatch($route)) {
              if (is_array($value))
                $this->method = $value['method'];
              else
                $this->method = $value;

              $this->route = $route;
              return true;
            }
          }
        }
      }

    }

    return false;
  }

  public function isRest()
  {
    $route = '/'.strtolower(str_replace('Controller','',$this->controller));
    $param = '/:id';
    $pattern = array(':id' => '\d+');

    if($this->routeMatch($route,$pattern)){
      return true;
    }
    elseif($this->routeMatch($route.$param,$pattern)){
      return true;
    }

    return false;
  }

  private function isComplexe()
  {

  }

  private function getOptions()
  {

  }

  public function routeMatch($route, $paterns = NULL)
  {
    $split = explode("/", $route);

    // build regex
    $regex = "#^\/";
    foreach ($split as $key => $part) {
      if ($key > 1)
        $regex .= "\/";

      if ($part != "") {
        if ($part[0] == ":") {
          if (!is_null($paterns) && isset($paterns[$part]))
            $regex .= $paterns[$part];
          else
            $regex .= "[a-zA-Z0-9]+";

        } else {
          $regex .= $part;
        }

      }
    }

    $regex .= "\/?$#";

    return preg_match($regex, $this->pathInfo);
  }
}