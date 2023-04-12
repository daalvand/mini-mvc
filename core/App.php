<?php

namespace Core;


use Core\Contracts\App as AppContract;
use Core\Contracts\Router;

class App implements AppContract
{
    protected static self $instance;
    protected array       $callbacks; // services that can instantiated
    protected array       $instantiated;// instantiated services

    public function __construct(protected string $basePath, protected array $configs)
    {
        static::$instance = $this;
        (new AppServiceProvider(app: $this))->register();
    }

    public static function getInstance(): static
    {
        return static::$instance;
    }

    public function run(): void
    {
        echo $this->get(Router::class)->resolve();
    }

    public function set(string $contract, callable|object $callable): void
    {
        unset($this->instantiated[$contract]);
        if (is_callable($callable)) {
            $this->callbacks[$contract] = $callable;
        } else {
            $this->instantiated[$contract] = $callable;
        }
    }

    public function get(string $contract): mixed
    {
        if (isset($this->instantiated[$contract])) {
            return $this->instantiated[$contract];
        }
        if (isset($this->callbacks[$contract])) {
            $this->instantiated[$contract] = $this->callbacks[$contract]($this);
            return $this->instantiated[$contract];
        }
        return new $contract;
    }
}
