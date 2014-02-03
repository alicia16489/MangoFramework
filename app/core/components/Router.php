<?php

namespace core\components;
use Pux\Mux;
use Pux\Executor;

class RouterException extends \Exception{};

class Router extends Mux
{
  private $route;
  private $class;
  private $blueprints;

  public function __construct(Blueprint $blueprints)
  {
    $this->blueprints = $blueprints;
  }

  public function errorRouting()
  {
    $this->add('/error/:status', ['\core\components\Error', 'run']);
  }

  public function logicRouting()
  {
    $routePatern = '/' . str_replace('ressource','',strtolower($this->blueprints->ressource));
    $this->class = '\ressources\logic\\' . $this->blueprints->ressource;

    $this->add($routePatern, [$this->class, 'get']);
    $this->add($routePatern . "/", [$this->class, 'get']);

    $this->prepare($_SERVER['PATH_INFO']);
  }

  public function subLogicRouting()
  {
    $routePatern = $this->blueprints->route;
    $this->class = '\ressources\logic\\' . $this->blueprints->ressource;

    $this->add($routePatern, [$this->class, $this->blueprints->method]);

    $this->prepare($_SERVER['PATH_INFO']);
  }

  public function restRouting()
  {
    $routePatern = '/' . str_replace('ressource','',strtolower($this->blueprints->ressource));
    $this->class = '\ressources\physical\\' . $this->blueprints->ressource;

    $this->get($routePatern . "/", [$this->class, 'index']);
    $this->get($routePatern, [$this->class, 'index']);
    $this->get($routePatern . '/:id', [$this->class, $this->blueprints->restMethod]);
    $this->post($routePatern, [$this->class, $this->blueprints->restMethod]);
    $this->put($routePatern . '/:id', [$this->class, $this->blueprints->restMethod]);
    $this->delete($routePatern . '/:id', [$this->class, $this->blueprints->restMethod]);


    $this->prepare($_SERVER['PATH_INFO']);
  }

  public function beforeRouting(){
    $this->add('/before-wxx45wx4',[$this->class,'before']);
    try
    {
      Executor::execute($this->dispatch('/before-wxx45wx4'));
    }
    catch(\Exception $e){}
  }

  public function afterRouting(){
    $this->add('/after-wxx45wx4',[$this->class,'after']);
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
    if(empty($this->route))
      throw new RouterException('bad route');

    $class = $this->route[2][0];
    $ressource = new $class();

    if(method_exists($ressource,$this->route[2][1])){
      $this->beforeRouting();
      Executor::execute($this->route);
      $this->afterRouting();
    }
    else{
      throw new RouterException('missing methode');
    }

  }
}