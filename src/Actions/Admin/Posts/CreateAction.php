<?php

namespace App\Actions\Admin\Posts;

use App\Domain\Admin\Handler\Posts\CreateHandler;
use App\Domain\Common\Renderer\Interfaces\TwigRendererInterface;
use App\Domain\Common\Router\Interfaces\RouterInterface;
use App\Responder\RedirectResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class CreateAction
{
    /** @var TwigRendererInterface */
    private $renderer;

    /** @var CreateHandler */
    private $handler;

    /** @var RouterInterface */
    private $router;

    /**
     * CreateAction constructor.
     * @param TwigRendererInterface $renderer
     * @param CreateHandler $handler
     * @param RouterInterface $router
     */
    public function __construct(
        TwigRendererInterface $renderer,
        CreateHandler $handler,
        RouterInterface $router
    ) {
        $this->renderer = $renderer;
        $this->handler = $handler;
        $this->router = $router;
    }

    /**
     * Create Post action
     *
     * @param ServerRequestInterface $request
     * @return string|ResponseInterface
     */
    public function create(ServerRequestInterface $request)
    {
        if ($this->handler->handle($request)) {
            return new RedirectResponse($this->router->generateUri('admin.posts.index'));
        }
        return $this->renderer->render('admin/posts/create.html.twig');
    }
}
