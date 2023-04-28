<?php

namespace Tests\Unit\Core\Http;

use Core\Http\Request;
use Tests\TestCase;

class RequestTest extends TestCase
{
    protected Request $request;

    protected function setUp(): void
    {
        parent::setUp();
        $this->request = new Request([
             'query'   => ['name' => 'John', 'age' => 30],
             'body'    => ['email' => 'john@example.com'],
             'cookies' => ['sessionId' => '123456789'],
             'server'  => ['REQUEST_METHOD' => 'GET', 'REQUEST_URI' => '/users'],
             'headers' => ['Accept-Language' => 'en-US,en;q=0.9'],
        ]);
    }

    public function test_all(): void
    {
        $this->assertSame([
             'name'  => 'John',
             'age'   => 30,
             'email' => 'john@example.com',
        ], $this->request->all());
    }

    public function test_method(): void
    {
        $this->assertSame('get', $this->request->method());
    }

    public function test_url(): void
    {
        $this->assertSame('/users', $this->request->url());
    }

    public function test_is_get(): void
    {
        $this->assertTrue($this->request->isGet());
        $this->assertFalse($this->request->isPost());
    }

    public function test_is_post(): void
    {
        $postRequest = new Request(['server' => ['REQUEST_METHOD' => 'POST']]);
        $this->assertTrue($postRequest->isPost());
        $this->assertFalse($postRequest->isGet());
    }

    public function test_body(): void
    {
        $this->assertSame(['email' => 'john@example.com'], $this->request->body());
    }

    public function test_get(): void
    {
        $this->assertSame('John', $this->request->get('name'));
        $this->assertSame(30, $this->request->get('age'));
        $this->assertNull($this->request->get('address'));
        $this->assertSame('USA', $this->request->get('country', 'USA'));
    }

    public function test_post(): void
    {
        $this->assertSame('john@example.com', $this->request->post('email'));
        $this->assertNull($this->request->post('password'));
        $this->assertSame('default', $this->request->post('gender', 'default'));
    }

    public function test_set_route_params(): void
    {
        $this->request->setRouteParams(['id' => 1, 'slug' => 'foo']);
        $this->assertSame(['id' => 1, 'slug' => 'foo'], $this->request->routeParams());
    }

    public function test_route_param(): void
    {
        $this->request->setRouteParams(['id' => 1, 'slug' => 'foo']);
        $this->assertSame(1, $this->request->routeParam('id'));
        $this->assertSame('foo', $this->request->routeParam('slug'));
        $this->assertNull($this->request->routeParam('name'));
    }

    public function test_cookies(): void
    {
        $this->assertSame(['sessionId' => '123456789'], $this->request->cookies());
    }

    public function test_cookie(): void
    {
        // Set up test data
        $cookieName           = 'testCookie';
        $cookieValue          = 'testValue';
        $_COOKIE[$cookieName] = $cookieValue;

        // Create a new Request instance
        $request = new Request(['cookies' => $_COOKIE]);

        // Test that the cookie method returns the expected value
        $this->assertEquals($cookieValue, $request->cookie($cookieName));
    }
}
