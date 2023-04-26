<?php
/** @noinspection ForgottenDebugOutputInspection */

namespace Tests\Unit\Core\Http;

use Core\Contracts\View;
use Core\Http\Response;
use PHPUnit\Framework\MockObject\Exception;
use Tests\TestCase;

class ResponseTest extends TestCase
{
    public function testSend(): void
    {
        $content    = ['foo' => 'bar'];
        $statusCode = 200;
        $viewMock   = $this->createMock(View::class);
        $response   = new Response($viewMock);
        $response->setContent($content)
                 ->setStatusCode($statusCode)
                 ->setHeader('Content-Type', 'application/json');

        ob_start();
        $response->send();
        $actualOutput = ob_get_clean();

        $expectedOutput = json_encode($content);

        $this->assertEquals($expectedOutput, $actualOutput);
        $this->assertEquals($statusCode, http_response_code());
        $this->assertContains('Content-Type: application/json', xdebug_get_headers());
    }

    public function testRedirect(): void
    {
        $url        = 'https://example.com';
        $statusCode = 302;

        $viewMock   = $this->createMock(View::class);
        $response   = new Response($viewMock);
        $response->redirect($url, $statusCode);

        $this->assertEquals($url, $response->header('Location'));
        $this->assertEquals($statusCode, $response->statusCode());
    }

    public function testWithJson(): void
    {
        $data       = ['foo' => 'bar'];
        $statusCode = 200;

        $viewMock   = $this->createMock(View::class);
        $response   = new Response($viewMock);
        $response->withJson($data, $statusCode);

        $this->assertEquals('application/json', $response->header('Content-Type'));
        $this->assertEquals($data, $response->content());
        $this->assertEquals($statusCode, $response->statusCode());
    }

    public function testWithHtml(): void
    {
        $html       = '<html lang="en"><body>hello world</body></html>';
        $statusCode = 200;

        $viewMock   = $this->createMock(View::class);
        $response   = new Response($viewMock);
        $response->withHtml($html, $statusCode);

        $this->assertEquals('text/html', $response->header('Content-Type'));
        $this->assertEquals($html, $response->content());
        $this->assertEquals($statusCode, $response->statusCode());
    }

    /**
     * @throws Exception
     */
    public function testWithView()
    {
        $viewMock = $this->createMock(View::class);
        $viewMock->expects($this->once())
                 ->method('view')
                 ->with('test-view', ['foo' => 'bar'])
                 ->willReturn('<h1>Hello, World!</h1>');

        $response = new Response($viewMock);
        $response->withView('test-view', ['foo' => 'bar']);

        $this->assertEquals('<h1>Hello, World!</h1>', $response->content());
        $this->assertEquals(200, $response->statusCode());
        $this->assertEquals(['Content-Type' => 'text/html'], $response->headers());
    }
}
