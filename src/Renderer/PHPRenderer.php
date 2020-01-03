<?php

namespace Framework\Renderer;

use Framework\Renderer\Interfaces\RendererInterface;

class PHPRenderer implements RendererInterface
{
    const DEFAULT_NAMESPACE = '__MAIN';

    /** @var array */
    private $paths = [];

    /** @var array */
    private $global = [];

    /**
     * Renderer constructor.
     * @param string|null $defaultPath
     */
    public function __construct(?string $defaultPath = null)
    {
        if (!\is_null($defaultPath)) {
            $this->addPath($defaultPath);
        }
    }

    /**
     * Add path for render template
     * You can use namespace or not
     * $this->addPath(__DIR__.'/views')
     * $this->addPath(__DIR__.'/views', 'blog')
     *
     * If you put your template in './views' directory at the root level
     * you don't need to use method addPath().
     * Default path is already adding in ./public/index.php
     *
     *
     * @param string $path
     * @param string $namespace
     */
    public function addPath(string $path, string $namespace = self::DEFAULT_NAMESPACE): void
    {
        if ($namespace === self::DEFAULT_NAMESPACE) {
            $this->paths[self::DEFAULT_NAMESPACE] = $path;
        } else {
            $this->paths[$namespace] = $path;
        }
    }

    /**
     * Add parameters to all templates
     *
     * @param string $key
     * @param mixed $value
     */
    public function addGlobal(string $key, $value): void
    {
        $this->global[$key] = $value;
    }

    /**
     * Render template
     * You can add namespace by method addPath()
     * Your namespace must begin by "@" in method render()
     * $this->render('@blog/index.php')
     * $this->render(index.php')
     *
     * @param string $template
     * @param array $params
     * @return string
     */
    public function render(string $template, array $params = []): string
    {
        if ($this->hasNamespace($template)) {
            $view = $this->replaceNamespace($template);
        } else {
            $view = $this->paths[self::DEFAULT_NAMESPACE] . DIRECTORY_SEPARATOR . $template;
        }

        ob_start();
        $renderer = $this;
        extract($this->global);
        extract($params);
        require($view);
        return ob_get_clean();
    }

    private function hasNamespace(string $template): bool
    {
        return $template[0] === "@";
    }

    private function getNamespace(string $template): string
    {
        return substr($template, 1, strpos($template, '/') -1);
    }

    private function replaceNamespace(string $template): string
    {
        $namespace = $this->getNamespace($template);
        return str_replace('@'.$namespace, $this->paths[$namespace], $template);
    }
}
