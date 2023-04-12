<?php

namespace Core;

use Core\Contracts\View as ViewContract;

class View implements ViewContract
{
    protected string $viewPath;
    protected mixed  $data;

    //todo add extends, section, yield, etc
    //todo add cache
    public function __construct(array $config, string $basePath)
    {
        $this->viewPath = merge_paths($basePath, $config['path']) . '/';
    }

    public function view(string $view, array $data = []): bool|string
    {
        $view     = $this->getViewFullPath($view);
        $compiled = $this->compile($view);
        return $this->executeView($compiled, $data);
    }

    protected function compile(string $content): string
    {
        $content = $this->getContent($content);
        $content = $this->compileEscapedEchos($content);
        return $this->compileEchos($content);
    }

    protected function compileEscapedEchos(string $content): string
    {
        return preg_replace('~{{\s*(.+?)\s*}}~is', '<?php echo htmlentities($1, ENT_QUOTES, \'UTF-8\') ?>', $content);
    }

    protected function compileEchos(string $content): string
    {
        return preg_replace('~{!!\s*(.+?)\s*!!}~is', '<?php echo $1 ?>', $content);
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

    /**
     * @param string $view
     *
     * @return false|string
     */
    public function getContent(string $view): string|false
    {
        $pattern  = '~@(include)\((.*)\)~i';
        $contents = file_get_contents($this->viewPath . $view);
        preg_match_all($pattern, $contents, $matches, PREG_SET_ORDER);
        foreach ($matches as $value) {
            $includedCode = $this->getContent($value[2]);
            $contents     = str_replace($value[0], $includedCode, $contents);
        }
        return preg_replace($pattern, '', $contents);
    }
}
