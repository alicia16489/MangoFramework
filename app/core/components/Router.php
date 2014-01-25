<?php

namespace core\components;
use Pux\Mux;
use Pux\Executor;

class Router extends Mux
{
  private $route;
  private $ressource;
  private $method;
  private $options;

  public function __construct(Blueprints $blueprints)
  {
    $this->route = '/'.strtolower($blueprints->ressource);
    $this->ressource = strtolower($blueprints->ressource);
    $this->method = $blueprints->method;
    $this->options = $blueprints->options;
  }

  public function restRouting()
  {
    $this->ressource = '\ressources\physical\\'.$this->ressource;

    if(!empty($this->options['id']))
      $this->route .= "/:id";

  }

  public function execute()
  {
    $this->add($this->route, [$this->ressource,$this->method]);

    $route = $this->dispatch( $_SERVER['PATH_INFO'] );
    Executor::execute($route);
  }
}