<?php

namespace Tests\Unit\Core;

use Core\View;
use PHPUnit\Framework\TestCase;

class ViewTest extends TestCase
{
    protected View   $view;
    protected string $basePath;
    protected string $viewDir;
    protected string $cacheDir;

    protected function setUp(): void
    {
        parent::setUp();
        $this->basePath = merge_paths(sys_get_temp_dir(), mt_rand());
        $this->viewDir  = merge_paths($this->basePath, 'views');
        $this->cacheDir = merge_paths($this->viewDir, 'cache');

        //make sure the cache directory exists
        if (!is_dir($this->cacheDir)) {
            mkdir($this->cacheDir, 0777, true);
        }

        $this->view = new View([
             'path'       => 'views',
             'cache_path' => 'views/cache',
             'cacheable'  => true,
        ], $this->basePath);
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        //remove the base path
        if (is_dir($this->basePath)) {
            remove_directory($this->basePath);
        }
    }

    public function testCompileIncludeMethodIncludesOtherViews(): void
    {
        $viewContent  = "This is the parent view. @include(child)";
        $childContent = "This is the child view. Hello, {{ \$name }}!";
        $expected     = "This is the parent view. This is the child view. Hello, Alice!";
        $this->putViewFile('parent', $viewContent);
        $this->putViewFile('child', $childContent);
        $result = $this->view->render('parent', ['name' => 'Alice']);
        $this->assertEquals($expected, $result);
    }

    public function testCompileYieldMethodFillsYieldsWithBlocks(): void
    {
        $viewContent = "@block(content)<p>Hello, world!</p>@endblock<div>@yield(content)</div>";
        $expected    = "<div><p>Hello, world!</p></div>";

        $this->putViewFile('test', $viewContent);

        $result = $this->view->render('test');

        $this->assertEquals($expected, $result);
    }

    public function testCompileEchoMethodCompilesCorrectly(): void
    {
        $viewContent = "<p>{{ 'Hello, world!' }}</p>";
        $expected    = "<p>Hello, world!</p>";

        $this->putViewFile('test', $viewContent);

        $result = $this->view->render('test');

        $this->assertEquals($expected, $result);
    }

    public function testCompileRawEchosMethodCompilesCorrectly(): void
    {
        $viewContent = "<p>{{ '<span>Hello</span>' }}</p>";
        $expected    = "<p>&lt;span&gt;Hello&lt;/span&gt;</p>";
        $this->putViewFile('test', $viewContent);
        $this->assertSame($expected, $this->view->render('test'));
    }

    public function testCompileHtmlEchosMethodCompilesCorrectly(): void
    {
        $viewContent = "<p>{!! '<span>Hello</span>' !!}</p>";
        $expected    = "<p><span>Hello</span></p>";
        $this->putViewFile('test', $viewContent);
        $this->assertSame($expected, $this->view->render('test'));
    }


    public function testCompileIncludeMethodCompilesCorrectly(): void
    {
        $viewContent = "@include(partials/header)";
        $expected    = "<header><h1>Page Title</h1></header>";
        $this->putViewFile('test', $viewContent);
        $this->putViewFile('partials/header', $expected);
        $this->assertSame($expected, $this->view->render('test', ['title' => 'Page Title']));
    }

    public function testCompileCache(): void
    {
        $viewContent  = "<p>{{ 'Hello, world!' }}</p>";
        $expected     = "<p>Hello, world!</p>";
        $cacheFile    = merge_paths($this->cacheDir, 'test');
        $cacheContent = "<p><?php echo htmlentities('Hello, world!', ENT_QUOTES, 'UTF-8') ?></p>";
        $this->putViewFile('test', $viewContent);
        $this->assertSame($expected, $this->view->render('test'));
        $this->assertFileExists($cacheFile);
        $this->assertSame($cacheContent, file_get_contents($cacheFile));
    }

    private function putViewFile(string $view, string $viewContent): void
    {
        $view = explode('/', $view);
        foreach ($view as $key => $value) {
            if ($key === count($view) - 1) {
                break;
            }
            $this->viewDir .= "/$value";
            if (!is_dir($this->viewDir)) {
                mkdir($this->viewDir, 0777, true);
            }
        }
        $view     = end($view);
        $viewFile = merge_paths($this->viewDir, "$view.php");
        file_put_contents($viewFile, $viewContent);
    }

}
