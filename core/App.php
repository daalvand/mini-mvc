<?php

namespace Core;


use Core\Contracts\App as ContractApp;
use Core\Contracts\Http\Response;
use Core\Contracts\Router;
use Exception;

class App implements ContractApp
{
    protected static self $instance;
    protected array       $callbacks;
    protected array       $singletonCallbacks;
    protected array       $singletonInstantiated;

    public function __construct(protected string $basePath, protected array $configs)
    {
        static::$instance = $this;
        (new AppServiceProvider(app: $this))->register();
    }

    public static function getInstance(): static
    {
        return static::$instance;
    }

    /**
     * @throws Exception
     */
    public function run(): void
    {
        $response = $this->get(Router::class)->resolve();
        if ($response instanceof Response) {
            $response->send();
        } elseif (is_string($response)) {
            echo $response;
        } elseif (is_array($response) || is_object($response)) {
            echo json_encode($response);
        } else {
            throw new Exception('Invalid response');
        }
    }

    public function singleton(string $contract, callable|object|array $callable): void
    {
        $this->singletonCallbacks[$contract] = $callable;
    }

    public function bind(string $contract, callable|object|array $callable): void
    {
        $this->callbacks[$contract] = $callable;
    }

    /**
     * @throws Exception
     */
    public function get(string $contract): mixed
    {
        if (isset($this->singletonInstantiated[$contract])) {
            return $this->singletonInstantiated[$contract];
        }

        if (isset($this->singletonCallbacks[$contract])) {
            return $this->singletonInstantiated[$contract] = $this->build($this->singletonCallbacks[$contract]);
        }

        if (isset($this->callbacks[$contract])) {
            return $this->build($this->callbacks[$contract]);
        }

        return $this->callbacks[$contract] = $this->build($contract);
    }

    /**
     * @throws Exception
     */
    protected function build(callable|object|array|string $callable): mixed
    {
        if (is_callable($callable)) {
            return $callable($this);
        }

        if (is_array($callable)) {
            $class  = $callable[0];
            $method = $callable[1];
            $args   = array_slice($callable, 2);
            return (new $class)->$method(...$args);
        }

        if (is_string($callable) && class_exists($callable)) {
            return new $callable;
        }

        if (is_object($callable)) {
            return $callable;
        }

        throw new Exception("Cannot build $callable");
    }


    public function getConfigs(): array
    {
        return $this->configs;
    }

    public function getConfig(string $key): mixed
    {
        return $this->configs[$key] ?? null;
    }

    public function basePath(): string
    {
        return $this->basePath;
    }
}
