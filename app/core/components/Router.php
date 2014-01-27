<?php

namespace core\components;
use Pux\Mux;
use Pux\Executor;

class Router extends Mux
{
  private $route;
  private $blueprints;

  public function __construct(Blueprints $blueprints)
  {
    $this->blueprints = $blueprints;
  }

  public function errorRouting()
  {
    $this->add('/error/:status', ['\core\components\Error','run']);
  }

  public function logicRouting(){
    $routePatern = '/'.strtolower($this->blueprints->ressource);
    $this->blueprints->ressource = '\ressources\logic\\'.$this->blueprints->ressource;

    $this->add($routePatern , [$this->blueprints->ressource,'get']);
    $this->add($routePatern."/" , [$this->blueprints->ressource,'get']);

    $this->prepare( $_SERVER['PATH_INFO'] );
  }

  public function subLogicRouting()
  {
    $routePatern = $this->blueprints->route;
    $this->blueprints->ressource = '\ressources\logic\\'.$this->blueprints->ressource;

    $this->add($routePatern , [$this->blueprints->ressource,$this->blueprints->method]);

    $this->prepare( $_SERVER['PATH_INFO'] );
  }

  public function restRouting()
  {
    $routePatern = '/'.strtolower($this->blueprints->ressource);
    $this->blueprints->ressource = '\ressources\physical\\'.$this->blueprints->ressource;

    $this->get($routePatern."/" , [$this->blueprints->ressource,'index']);
    $this->get($routePatern , [$this->blueprints->ressource,'index']);
    $this->get($routePatern.'/:id' , [$this->blueprints->ressource,$this->blueprints->restMethod]);
    $this->post($routePatern , [$this->blueprints->ressource,$this->blueprints->restMethod]);
    $this->put($routePatern.'/:id' , [$this->blueprints->ressource,$this->blueprints->restMethod]);
    $this->delete($routePatern.'/:id' , [$this->blueprints->ressource,$this->blueprints->restMethod]);

    $this->prepare( $_SERVER['PATH_INFO'] );
  }

  public function prepare($path)
  {
    $prepare = $this->dispatch($path);
    if(!empty($prepare)){
      $this->route = $this->dispatch($path);
    }
  }

  public function execute()
  {
    Executor::execute($this->route);
  }
}