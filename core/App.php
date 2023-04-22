<?php

namespace Core;


use Core\Contracts\App as ContractApp;
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
        echo $this->get(Router::class)->resolve();
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

        throw new Exception("Cannot resolve $contract");
    }

    protected function build(callable|object|array $callable): mixed
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

        return $callable;
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
