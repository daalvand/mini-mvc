<?php

namespace Core;

use Core\Contracts\Http\Controller;
use Core\Contracts\Router as RouterContract;
use Core\Exceptions\NotFoundException;
use RuntimeException;

class Router implements RouterContract
{
    protected array      $routes       = [];
    protected array|null $currentRoute = null;

    public function __construct() {
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

        if(is_array($callback)) {
            $callback = $this->resolveController($callback);
        }

        return $callback();
    }

    protected function getCallback(): string|array|callable|null
    {
        // check if route does not have params
        $callback = $this->getCallbackWithoutParams();
        if ($callback) {
            return $callback;
        }

        return null;
    }

    protected function trimUrl(string $url): string
    {
        return trim($url, '/');
    }

    protected function getCallbackWithoutParams(): mixed
    {
        $method             = strtolower($_SERVER['REQUEST_METHOD']);// todo implement request class
        $url                = $this->trimUrl($this->getUrl());// todo implement request class
        $this->currentRoute = $this->getRoutesOfMethod($method)[$url] ?? null;
        return $this->currentRoute['callback'] ?? null;
    }

    private function getUrl(): string
    {
        $path     = $_SERVER['REQUEST_URI'];
        $position = strpos($path, '?');
        if ($position !== false) {
            $path = substr($path, 0, $position);
        }
        return $path;
    }

    private function resolveController(array $callback): array
    {
        $class = $callback[0];
        if (!class_exists($class) || !is_subclass_of($class, Controller::class)) {
            throw new RuntimeException("Invalid Controller!");
        }

        $callback[0] = new $class;
        return $callback;
    }
}
