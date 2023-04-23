<?php

namespace Core;

use Core\Contracts\Http\Controller;
use Core\Contracts\Http\Middleware;
use Core\Contracts\Http\Request;
use Core\Contracts\Router as RouterContract;
use Core\Exceptions\NotFoundException;
use RuntimeException;

class Router implements RouterContract
{
    protected array      $routes       = [];
    protected array|null $currentRoute = null;

    public function __construct(protected Request $request)
    {
    }

    public function get(
         string $path,
         array|string|callable $callback,
         array $conditions = [],
         array $middlewares = []
    ): void {
        $this->defineRoute('get', $path, $callback, $conditions, $middlewares);
    }

    public function post(
         string $path,
         array|string|callable $callback,
         array $conditions = [],
         array $middlewares = []
    ): void {
        $this->defineRoute('post', $path, $callback, $conditions, $middlewares);
    }

    public function put(
         string $path,
         array|string|callable $callback,
         array $conditions = [],
         array $middlewares = []
    ): void {
        $this->defineRoute('put', $path, $callback, $conditions, $middlewares);
    }

    protected function defineRoute(
         string $method,
         string $path,
         array|string|callable $callback,
         array $conditions = [],
         array $middlewares = []
    ): void {
        $path                                        = $this->trimUrl($path);
        $this->routes[$method][$path]['callback']    = $callback;
        $this->routes[$method][$path]['conditions']  = $conditions;
        $this->routes[$method][$path]['middlewares'] = $middlewares;
    }

    /**
     * Get all routes for current request method
     *
     * @param string $method
     *
     * @return array
     */
    protected function getRoutesOfMethod(string $method): array
    {
        return $this->routes[$method] ?? [];
    }

    /**
     * @throws NotFoundException
     */
    public function resolve(): mixed
    {
        $callback = $this->getCallback();
        if (!$callback) {
            throw new NotFoundException();
        }
        if (is_array($callback)) {
            $callback = $this->resolveController($callback);
        }

        $this->executeMiddlewares();

        return $callback($this->request);
    }

    protected function getCallback(): string|array|callable|null
    {
        // check if route does not have params
        $callback = $this->getCallbackWithoutParams();
        if ($callback) {
            return $callback;
        }

        return $this->getCallableThatHasParams();
    }

    protected function trimUrl(string $url): string
    {
        return trim($url, '/');
    }

    protected function getCallbackWithoutParams(): mixed
    {
        $method             = $this->request->getMethod();
        $url                = $this->trimUrl($this->request->getUrl());
        $this->currentRoute = $this->getRoutesOfMethod($method)[$url] ?? null;
        return $this->currentRoute['callback'] ?? null;
    }

    protected function getCallableThatHasParams(): string|array|callable|null
    {
        $method = $this->request->getMethod();
        $url    = $this->trimUrl($this->request->getUrl());
        $routes = $this->getRoutesOfMethod($method);
        // Start iterating register routes
        foreach ($routes as $path => $route) {
            $conditions = $route['conditions'];

            //-----------match by regex------------------------
            preg_match_all("/{(\w+)}/", $path, $paramsMatches);
            $braceParams     = $paramsMatches[0] ?? [];
            $params          = $paramsMatches[1] ?? [];
            $regexConditions = [];
            foreach ($params as $param) {
                $regexConditions[$param] = "(?<$param>" . ($conditions[$param] ?? "\w+") . ")";
            }
            $routeRegex = str_replace("/", "\/", $path);
            $routeRegex = "/^" . str_replace($braceParams, array_values($regexConditions), $routeRegex) . "$/";

            preg_match_all($routeRegex, $url, $routeMatches);
            //-----------end match by regex------------------------

            if ($routeMatches[0]) {
                $routeParams = [];
                foreach ($params as $param) {
                    $routeParams[$param] = reset($routeMatches[$param]);
                }
                $this->request->setRouteParams($routeParams);
                $this->currentRoute = $route;
                return $route['callback'];
            }
        }
        return null;
    }

    protected function resolveController(array $callback): array
    {
        $class = $callback[0];
        if (!class_exists($class) || !is_subclass_of($class, Controller::class)) {
            throw new RuntimeException("Invalid Controller!");
        }

        // callback[0] is controller class name
        // callback[1] is current method name
        $callback[0] = new $class();
        return $callback;
    }

    protected function executeMiddlewares(): void
    {
        $middlewares = $this->currentRoute['middlewares'] ?? [];
        foreach ($middlewares as $middleware) {
            if (!class_exists($middleware) || !is_subclass_of($middleware, Middleware::class)) {
                throw new RuntimeException("Invalid Middleware!");
            }
            $middleware = new $middleware;
            $middleware->handle($this->request);
        }
    }
}
