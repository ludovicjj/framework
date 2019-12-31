<?php

namespace Framework\Renderer;

use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class TwigRenderer implements RendererInterface
{
    /** @var Environment */
    private $twig;

    /** @var FilesystemLoader */
    private $loader;

    /**
     * Defined default path is initialized in "public/index.php"
     * Default path is directory "./views/" at the root level
     *
     * TwigRenderer constructor.
     * @param string $path
     */
    public function __construct(string $path)
    {
        $this->loader = new FilesystemLoader($path);
        $this->twig = new Environment($this->loader, []);
    }

    /**
     * Add path to render templates
     *
     * $this->addPath(__DIR__.'/views', 'blog')
     *
     * @param string $path
     * @param string $namespace
     * @throws \Twig\Error\LoaderError
     */
    public function addPath(string $path, string $namespace): void
    {
        $this->loader->addPath($path, $namespace);
    }

    /**
     * Add parameters to all templates
     *
     * @param string $key
     * @param mixed $value
     */
    public function addGlobal(string $key, $value): void
    {
        $this->twig->addGlobal($key, $value);
    }

    /**
     * Render template with or without namespace
     * Namespace must begin by "@" in method render()
     *
     * $this->render('@blog/index.php')
     * $this->render(index.php')
     *
     * @param string $template
     * @param array $params
     * @return string
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function render(string $template, array $params = []): string
    {
        return $this->twig->render($template, $params);
    }
}
