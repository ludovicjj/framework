<?php

namespace App\Domain\Common\Renderer;

use App\Domain\Common\Renderer\Interfaces\TwigRendererInterface;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class TwigRenderer implements TwigRendererInterface
{
    /** @var Environment */
    private $twig;

    /**
     * TwigRenderer constructor.
     * @param Environment $twig
     */
    public function __construct(Environment $twig)
    {
        $this->twig = $twig;
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
        /** @var FilesystemLoader $loader */
        $loader = $this->twig->getLoader();
        $loader->addPath($path, $namespace);
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
