<?php

namespace Core;

use Core\Contracts\View as ViewContract;
use RuntimeException;

class View implements ViewContract
{
    protected array  $blocks = [];
    protected string $viewPath;
    protected string $cachePath;
    protected bool   $cacheable;

    public function __construct(array $config, string $basePath)
    {
        $this->cachePath = merge_paths($basePath, $config['cache_path']) . '/';
        $this->viewPath  = merge_paths($basePath, $config['path']) . '/';
        $this->cacheable = $config['cacheable'] ?? false;
    }

    public function view(string $view, array $data = []): bool|string
    {
        $view     = $this->getViewFullPath($view);
        $compiled = $this->readFromCache($view);
        if (!$compiled) {
            $compiled = $this->compile($view);
            $this->writeToCache($view, $compiled);
        }
        return $this->executeView($compiled, $data);
    }

    protected function readFromCache(string $view): string|null
    {
        $cachedFile = $this->getCacheFileName($view);
        if (
             !$this->cacheable ||
             !file_exists($cachedFile) ||
             filemtime($cachedFile) < filemtime($this->viewPath . $view)
        ) {
            return null;
        }
        return file_get_contents($cachedFile);
    }


    protected function getCacheFileName(string $view): string
    {
        if (
             !file_exists($this->cachePath) &&
             !mkdir($concurrentDirectory = $this->cachePath, 0744) &&
             !is_dir($concurrentDirectory)
        ) {
            throw new RuntimeException("Directory $concurrentDirectory was not created");
        }
        return $this->cachePath . str_replace('/', '_', $view);
    }

    protected function writeToCache(string $view, string $compiled): void
    {
        if ($this->cacheable) {
            $cachedFile = $this->getCacheFileName($view);
            file_put_contents($cachedFile, $compiled);
        }
    }

    protected function compile(string $view): string
    {
        $view = $this->includeFiles($view);
        $view = $this->compileBlock($view);
        $view = $this->compileYield($view);
        $view = $this->compileEscapedEchos($view);
        $view = $this->compileEchos($view);
        $view = $this->compilePHP($view);
        return $this->compileMultiLinePHP($view);
    }

    /**
     * Compile includes and extends
     *
     * @param string $view
     *
     * @return string
     */
    protected function includeFiles(string $view): string
    {
        $pattern  = '~@(extends|include)\((.*)\)~i';
        $contents = file_get_contents($this->viewPath . $view);
        preg_match_all($pattern, $contents, $matches, PREG_SET_ORDER);
        foreach ($matches as $value) {
            $includedCode = $this->includeFiles($value[2]);
            $contents     = str_replace($value[0], $includedCode, $contents);
        }
        return preg_replace($pattern, '', $contents);
    }

    /**
     * Compile Blocks save them to blocks property
     *
     * @param string $view
     *
     * @return string
     */
    protected function compileBlock(string $view): string
    {
        preg_match_all('~@block\((.*?)\)(.*?)@endblock~is', $view, $matches, PREG_SET_ORDER);
        foreach ($matches as $value) {
            $this->blocks[$value[1]] ??= '';
            $this->blocks[$value[1]] = $value[2];
            $view                    = str_replace($value[0], '', $view);
        }
        return $view;
    }

    /**
     * Fill yields with blocks property
     *
     * @param string $view
     *
     * @return string
     */
    protected function compileYield(string $view): string
    {
        foreach ($this->blocks as $block => $value) {
            $view = preg_replace("~@yield\($block\)~", $value, $view);
        }
        return preg_replace('~@yield\((.*?)\)~i', '', $view);
    }

    /**
     * This used for avoiding HTML injections
     *
     * @param string $view
     *
     * @return string
     */
    protected function compileEscapedEchos(string $view): string
    {
        return preg_replace('~{{\s*(.+?)\s*}}~is', '<?php echo htmlentities($1, ENT_QUOTES, \'UTF-8\') ?>', $view);
    }

    protected function compilePHP(string $view): string
    {
        return preg_replace('~{%\s*(.+?)\s*%}~i', '<?php $1 ?>', $view);
    }

    protected function compileMultiLinePHP(string $view): string
    {
        return preg_replace('~@php\s*(.*)\s*@endphp~is', '<?php $1 ?>', $view);
    }

    protected function compileEchos(string $view): string
    {
        return preg_replace('~{!!\s*(.+?)\s*!!}~is', '<?php echo $1 ?>', $view);
    }

    protected function getViewFullPath(string $view): string
    {
        return str_ends_with($view, '.php') ? $view : "$view.php";
    }

    protected function executeView(string $compiled, array $data): string|false
    {
        $path = tempnam(sys_get_temp_dir(), 'view');
        file_put_contents($path, $compiled);
        extract($data, EXTR_SKIP);
        ob_start();
        require $path;
        return ob_get_clean();
    }

    public function clearCache(): void
    {
        foreach (glob($this->cachePath . '*') as $file) {
            unlink($file);
        }
    }
}
