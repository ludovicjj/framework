<?php

namespace App\Blog\Actions;

use Framework\Renderer\RendererInterface;
use Psr\Http\Message\ServerRequestInterface;

class BlogShowAction
{
    /** @var RendererInterface */
    private $renderer;

    public function __construct(RendererInterface $renderer)
    {
        $this->renderer = $renderer;
    }

    public function __invoke(ServerRequestInterface $request)
    {
        return $this->renderer->render(
            '@blog/show.html.twig',
            [
                'slug' => $request->getAttribute('slug')
            ]
        );
    }
}
