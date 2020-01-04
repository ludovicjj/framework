<?php

namespace App\Domain\Common\Renderer\Interfaces;

interface TwigRendererInterface
{
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
    public function addPath(string $path, string $namespace): void;

    /**
     * Add parameters to all templates
     *
     * @param string $key
     * @param mixed $value
     */
    public function addGlobal(string $key, $value): void;

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
    public function render(string $template, array $params = []): string;
}
