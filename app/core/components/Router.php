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
    $this->route = '/'.strtolower($blueprints->ressource);
    $this->blueprints = $blueprints;
  }

  public function logicRouting(){
    $this->blueprints->ressource = '\ressources\logic\\'.$this->blueprints->ressource;

    $this->add($this->route , [$this->blueprints->ressource,'get']);
    $this->add($this->route."/" , [$this->blueprints->ressource,'get']);

    $this->prepare( $_SERVER['PATH_INFO'] );
  }

  public function restRouting()
  {
    $this->blueprints->ressource = '\ressources\physical\\'.$this->blueprints->ressource;

    $this->add($this->route."/" , [$this->blueprints->ressource,'index']);
    $this->add($this->route , [$this->blueprints->ressource,'index']);
    $this->add($this->route.'/:id' , [$this->blueprints->ressource,$this->blueprints->restMethod]);

    $this->prepare( $_SERVER['PATH_INFO'] );
  }

  public function prepare($path)
  {
    $this->route = $this->dispatch($path);
  }

  public function execute()
  {
    Executor::execute($this->route);
  }
}