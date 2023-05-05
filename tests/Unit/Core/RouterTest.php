<?php

namespace Tests\Unit\Core;

use Core\Contracts\Http\Middleware;
use Core\Contracts\Http\Request as ContractRequest;
use PHPUnit\Framework\MockObject\Exception;
use RuntimeException;
use Tests\TestCase;

use Core\Router;
use Core\Http\Request;
use Core\Exceptions\NotFoundException;

class RouterTest extends TestCase
{

    /**
     * @throws NotFoundException
     */
    public function test_get_route(): void
    {
        $router = new Router(new Request([
             'server' => [
                  'REQUEST_METHOD' => 'GET',
                  'REQUEST_URI'    => '/test',
             ],
        ]));


        $router->get('/test', function () {
            return 'test';
        });
        $response = $router->resolve();

        $this->assertEquals('test', $response);
    }

    public function test_not_found_route(): void
    {
        $router = new Router(new Request([
             'server' => [
                  'REQUEST_METHOD' => 'GET',
                  'REQUEST_URI'    => '/not-found',
             ],
        ]));

        $this->expectException(NotFoundException::class);
        $router->get('/test', function () {
            return 'test';
        });
        $router->resolve();
    }

    /**
     * @throws NotFoundException
     */
    public function test_resolve_route_with_params(): void
    {
        $request = new Request([
             'server' => [
                  'REQUEST_METHOD' => 'GET',
                  'REQUEST_URI'    => '/foo/123',
             ],
        ]);
        $router  = new Router($request);
        $router->get('/foo/{id}', function (Request $request) {
            return 'ID is ' . $request->routeParam('id');
        });

        $response = $router->resolve();

        $this->assertEquals('ID is 123', $response);
    }

    /**
     * @throws NotFoundException
     * @throws Exception
     */
    public function test_resolve_route_with_middleware(): void
    {
        //create a mock middleware
        $middleware = $this->createMock(Middleware::class);
        $middleware->method('handle')->willReturnCallback(function (ContractRequest $request) {
            echo 'Middleware on route ' . $request->url();
        });

        $middlewareClass = get_class($middleware);

        $this->app->bind($middlewareClass, function () use ($middleware) {
            return $middleware;
        });


        $request = new Request([
             'server' => [
                  'REQUEST_METHOD' => 'GET',
                  'REQUEST_URI'    => '/foo',
             ],
        ]);
        $router  = new Router($request);

        $router->get('/foo', function () {
            return 'Hello, world!';
        }, [], [$middlewareClass]);

        $this->expectOutputString('Middleware on route /foo');
        $router->resolve();
    }

    /**
     * @throws NotFoundException
     */
    public function test_resolve_route_with_invalid_controller(): void
    {
        $request = new Request([
             'server' => [
                  'REQUEST_METHOD' => 'GET',
                  'REQUEST_URI'    => '/foo',
             ],
        ]);
        $router  = new Router($request);

        $router->get('/foo', ['InvalidController', 'index']);

        $this->expectException(RuntimeException::class);
        $router->resolve();
    }

    /**
     * @throws NotFoundException
     */
    public function test_resolve_route_with_invalid_middleware(): void
    {
        $request = new Request([
             'server' => [
                  'REQUEST_METHOD' => 'GET',
                  'REQUEST_URI'    => '/foo',
             ],
        ]);
        $router  = new Router($request);

        $router->get('/foo', function () {
        }, [], ['InvalidMiddleware']);

        $this->expectException(RuntimeException::class);
        $router->resolve();
    }


}
