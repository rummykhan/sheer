<?php

namespace Sheer\Router;

use Sheer\Support\Collection;

class RouteCollection
{
    protected $routes = [];

    public function __construct(array $routes = [])
    {
        $this->routes = new Collection($routes);
    }

    public function add($route)
    {
        if (!$this->validate($route)) {
            throw new \Exception("Invalid route submitted for log.");
        }

        $route = $this->createRoute($route);
        $routes = $this->routes->toArray();
        $routes[] = $route;
        $this->routes = new Collection($routes);
    }

    protected function createRoute($route)
    {
        $route['route'] = new Route($route['method'], $route['uri'], $route['type']);

        return $route;
    }

    public function getRoutes() : Collection
    {
        return $this->routes;
    }
}