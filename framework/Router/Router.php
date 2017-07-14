<?php

namespace Sheer\Router;

use Sheer\Request;
use Sheer\Support\Collection;
use Exception;

/**
 * Class Router
 * @package App\Core
 */
class Router
{
    /**
     * @var null
     */
    protected $routes = [];
    /**
     * @var Request|null
     */
    protected $request = null;
    /**
     * @var null
     */
    protected $route = null;

    /**
     * Router constructor.
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        // Update the current request.
        $this->request = $request;

        $this->routes = new Collection($this->routes);

        $this->updateRoutes();
    }

    /**
     * Get the routes from routes file.
     */
    public function updateRoutes()
    {
        require '../routes/routes.php';
    }

    /**
     * Match the Request with the routes dictionary
     *
     * @param Request $request
     * @return bool
     */
    public function match(Request $request)
    {
        return $this->matchRoute();
    }

    /**
     * Match the route path, method and target Controller
     *
     * @return bool
     */
    protected function matchRoute()
    {
        return $this->routeExists() &&
            $this->requestTargetExists();
    }

    /**
     * Check if the route path exists or not.
     *
     * @return bool
     *
     * @throws Exception
     */
    private function routeExists()
    {
        $matched = $this->routes
            ->where('path', $this->request->getPath())
            ->where('method', $this->request->getRequestMethod())
            ->first();

        if (!$matched) {

            $matched = $this->routes->where('path', $this->request->getPath())->first();

            if( $matched ){
                throw new Exception('Method not allowed.');
            }

            return false;
        }

        $this->updateCurrentRoute($matched);

        return true;
    }

    /**
     * Update the current route for further application usage.
     *
     * @param array $route
     */
    private function updateCurrentRoute(array $route)
    {
        $this->route = $route;
    }

    /**
     * Checks if the method of the request matched with the method in the dictionary
     *
     * @return bool
     */
    private function methodMatched()
    {
        return $this->route['method'] === $this->request->getRequestMethod();
    }

    /**
     * Checks if the target controller exists or not.
     *
     * @return bool
     */
    private function requestTargetExists()
    {
        $controller = $this->route['target'];

        $controller = $this->normalizeController($controller);

        list($controller, $action) = explode('@', $controller);

        return class_exists($controller); //&& method_exists(new $controller(), $action)
    }

    /**
     * Get the target Controller and Action method.
     * @return array
     */
    public function getTarget()
    {
        list($controller, $action) = explode('@', $this->route['target']);
        return [
            $this->normalizeController($controller), $action
        ];
    }

    /**
     * Add a GET path to router collection
     *
     * @param $path
     * @param $target
     */
    public function get($path, $target)
    {
        $this->routes->add(['method' => 'GET', 'path' => $path, 'target' => $target]);
    }

    /**
     * Add a POST path to router collection
     *
     * @param $path
     * @param $target
     */
    public function post($path, $target)
    {
        $this->routes->add(['method' => 'POST', 'path' => $path, 'target' => $target]);
    }

    /**
     * Add a PUT path to the router collection
     *
     * @param $path
     * @param $target
     */
    public function put($path, $target)
    {
        $this->routes->add(['method' => 'PUT', 'path' => $path, 'target' => $target]);
    }

    /**
     * Add a DELETE path to the router collection
     * @param $path
     * @param $target
     */
    public function delete($path, $target)
    {
        $this->routes->add(['method' => 'DELETE', 'path' => $path, 'target' => $target]);
    }

    /**
     * Normalize the controller
     * by adding the base path of the controller
     * @param $controller
     * @return string
     */
    protected function normalizeController($controller)
    {
        return "App\\Controllers\\{$controller}";
    }
}