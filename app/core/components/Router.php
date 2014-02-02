<?php

namespace core\components;
use Pux\Mux;
use Pux\Executor;

class RouterException extends \Exception{};

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
    $this->add('/error/:status', ['\core\components\Error', 'run']);
  }

  public function logicRouting()
  {
    $routePatern = '/' . strtolower($this->blueprints->ressource);
    $this->blueprints->ressource = '\ressources\logic\\' . $this->blueprints->ressource;

    $this->add($routePatern, [$this->blueprints->ressource, 'get']);
    $this->add($routePatern . "/", [$this->blueprints->ressource, 'get']);

    $this->prepare($_SERVER['PATH_INFO']);
  }

  public function subLogicRouting()
  {
    $routePatern = $this->blueprints->route;
    $this->blueprints->ressource = '\ressources\logic\\' . $this->blueprints->ressource;

    $this->add($routePatern, [$this->blueprints->ressource, $this->blueprints->getMethod()]);

    $this->prepare($_SERVER['PATH_INFO']);
  }

  public function restRouting()
  {
    $routePatern = '/' . strtolower($this->blueprints->ressource);
    $this->blueprints->ressource = '\ressources\physical\\' . $this->blueprints->ressource;

    $this->get($routePatern . "/", [$this->blueprints->ressource, 'index']);
    $this->get($routePatern, [$this->blueprints->ressource, 'index']);
    $this->get($routePatern . '/:id', [$this->blueprints->ressource, $this->blueprints->restMethod]);
    $this->post($routePatern, [$this->blueprints->ressource, $this->blueprints->restMethod]);
    $this->put($routePatern . '/:id', [$this->blueprints->ressource, $this->blueprints->restMethod]);
    $this->delete($routePatern . '/:id', [$this->blueprints->ressource, $this->blueprints->restMethod]);


    $this->prepare($_SERVER['PATH_INFO']);
  }

  public function beforeRouting(){
    $this->add('/before-wxx45wx4',[$this->blueprints->ressource,'before']);
    try
    {
      Executor::execute($this->dispatch('/before-wxx45wx4'));
    }
    catch(\Exception $e){}
  }

  public function afterRouting(){
    $this->add('/after-wxx45wx4',[$this->blueprints->ressource,'after']);
    try
    {
      Executor::execute($this->dispatch('/after-wxx45wx4'));
    }
    catch(\Exception $e){}
  }

  public function prepare($path)
  {
    $prepare = $this->dispatch($path);
    if (!empty($prepare)) {
      $this->route = $this->dispatch($path);
    }
  }

  public function execute()
  {
    $ressource = new $this->blueprints->ressource();
    if(method_exists($ressource,$this->route[2][1])){
      $this->beforeRouting();

      if(!empty($this->route))
        Executor::execute($this->route);

      $this->afterRouting();
    }
    else{
      throw new RouterException('');
    }

  }
}