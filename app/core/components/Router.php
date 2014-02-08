<?php

namespace core\components;

use Pux\Mux;
use Pux\Executor;

class RouterException extends \Exception
{
}

;

class Router extends Mux
{
    private $route;
    private $class;
    private $blueprints;

    public function __construct(Blueprint $blueprints)
    {
        $this->blueprints = $blueprints;
    }

    public function logicRouting()
    {
        $routePatern = '/' . strtolower(str_replace('Controller', '', $this->blueprints->controller));
        $this->class = '\controllers\\' . $this->blueprints->controller;

        $this->add($routePatern, [$this->class, 'show']);
        $this->add($routePatern . "/", [$this->class, 'show']);

        $this->prepare($_SERVER['PATH_INFO']);
    }

    public function subLogicRouting()
    {
        $routePatern = $this->blueprints->route;
        $this->class = '\controllers\\' . $this->blueprints->controller;

        $this->add($routePatern, [$this->class, $this->blueprints->method]);

        $this->prepare($_SERVER['PATH_INFO']);
    }

    public function restRouting()
    {
        $routePatern = '/' . strtolower(str_replace('Controller', '', $this->blueprints->controller));
        $this->class = '\controllers\\' . $this->blueprints->controller;

        $this->get($routePatern . "/", [$this->class, 'index']);
        $this->get($routePatern, [$this->class, 'index']);
        $this->get($routePatern . '/:id', [$this->class, $this->blueprints->restMethod]);
        $this->post($routePatern, [$this->class, $this->blueprints->restMethod]);
        $this->put($routePatern . '/:id', [$this->class, $this->blueprints->restMethod]);
        $this->delete($routePatern . '/:id', [$this->class, $this->blueprints->restMethod]);


        $this->prepare($_SERVER['PATH_INFO']);
    }

    public function complexeRouting()
    {
        $this->class = '\controllers\\' . $this->blueprints->controller;
        $this->add('/complexe-wxx45wx4',[$this->class,'complexe']);

        $this->prepare('/complexe-wxx45wx4');
    }

    public function beforeRouting()
    {
        $this->add('/before-wxx45wx4', [$this->class, 'before']);
        try {
            Executor::execute($this->dispatch('/before-wxx45wx4'));
        } catch (\Exception $e) {
        }
        $this->add('/before:main-wxx45wx4', [$this->class, 'beforeMain']);
        try {
            Executor::execute($this->dispatch('/before:main-wxx45wx4'));
        } catch (\Exception $e) {
        }
    }

    public function afterRouting()
    {
        $this->add('/after-wxx45wx4', [$this->class, 'after']);
        try {
            Executor::execute($this->dispatch('/after-wxx45wx4'));
        } catch (\Exception $e) {
        }
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
        if (empty($this->route))
            throw new RouterException('bad route');

        $class = $this->route[2][0];
        $controller = new $class();

        if (method_exists($controller, $this->route[2][1])) {
            $this->beforeRouting();
            Executor::execute($this->route);
            $this->afterRouting();
        } else {
            throw new RouterException('missing methode');
        }

    }
}