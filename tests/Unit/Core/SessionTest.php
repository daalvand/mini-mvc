<?php

namespace Tests\Unit\Core;

use Exception;
use Tests\TestCase;

class SessionTest extends TestCase
{
    public function test_set_and_get(): void
    {
        session()->set('foo', 'bar');
        $this->assertEquals('bar', session()->get('foo'));
    }

    public function test_get_non_existent(): void
    {
        $this->assertNull(session()->get('baz'));
    }

    public function test_remove(): void
    {
        session()->set('foo', 'bar');
        session()->remove('foo');
        $this->assertNull(session()->get('foo'));
    }

    public function test_remove_temp(): void
    {
        session()->setTemp('foo', 'bar');
        session()->removeTemp('foo');
        $this->assertNull(session()->getTemp('foo'));
    }

    /**
     * @throws Exception
     */
    public function test_csrf_token(): void
    {
        $token = session()->csrfToken();
        $this->assertNotNull($token);
        $this->assertIsString($token);
        $this->assertEquals($token, session()->csrfToken());
    }

    public function test_regenerate(): void
    {
        $sessionId = session_id();
        session()->regenerate();
        $this->assertNotEquals($sessionId, session_id());
    }

    public function test_destroy(): void
    {
        session()->set('foo', 'bar');
        session()->setTemp('foo', 'bar');
        session()->destroy();
        $this->assertNull(session()->get('foo'));
        $this->assertNull(session()->getTemp('foo'));
        $this->assertNull(session()->csrfToken());
    }

    public function test_set_and_get_temp(): void
    {
        session()->setTemp('foo', 'bar');
        $this->assertEquals('bar', session()->getTemp('foo'));
    }

    public function test_get_non_existent_temp(): void
    {
        $this->assertNull(session()->getTemp('baz'));
    }

    public function test_set_and_get_with_expire_time(): void
    {
        session()->set('foo', 'bar', time() + 3600);
        $this->assertEquals('bar', session()->get('foo'));
    }

    public function test_get_expired(): void
    {
        session()->set('foo', 'bar', time() - 3600);
        $this->assertNull(session()->get('foo'));
    }
}
