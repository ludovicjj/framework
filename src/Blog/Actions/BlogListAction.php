<?php

namespace App\Blog\Actions;

use Framework\Renderer\RendererInterface;

class BlogListAction
{
    /** @var RendererInterface */
    private $renderer;

    public function __construct(
        RendererInterface $renderer
    ) {
        $this->renderer = $renderer;
    }

    public function __invoke()
    {
        return $this->renderer->render('@blog/index.html.twig');
    }
}
