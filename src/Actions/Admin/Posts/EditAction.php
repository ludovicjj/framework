<?php

namespace App\Actions\Admin\Posts;

use App\Domain\Admin\Handler\Posts\EditHandler;
use App\Domain\Blog\Repository\PostRepository;
use App\Domain\Common\Exception\NotFoundRecordsException;
use App\Domain\Common\Renderer\Interfaces\TwigRendererInterface;
use App\Domain\Common\Router\Interfaces\RouterInterface;
use App\Responder\RedirectResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class EditAction
{
    /** @var TwigRendererInterface */
    private $renderer;

    /** @var PostRepository */
    private $postRepository;

    /** @var RouterInterface */
    private $router;

    /** @var EditHandler */
    private $handler;

    /**
     * EditAction constructor.
     * @param TwigRendererInterface $renderer
     * @param PostRepository $postRepository
     * @param RouterInterface $router
     * @param EditHandler $handler
     */
    public function __construct(
        TwigRendererInterface $renderer,
        PostRepository $postRepository,
        RouterInterface $router,
        EditHandler $handler
    ) {
        $this->renderer = $renderer;
        $this->postRepository = $postRepository;
        $this->router = $router;
        $this->handler = $handler;
    }

    /**
     * Edit Post action
     *
     * @param ServerRequestInterface $request
     * @return string|ResponseInterface
     * @throws NotFoundRecordsException
     */
    public function edit(ServerRequestInterface $request)
    {
        $post = $this->postRepository->find($request->getAttribute('id'));

        if (is_null($post)) {
            throw new NotFoundRecordsException(
                sprintf('Not find entity with id : %s', $request->getAttribute('id'))
            );
        }

        if ($this->handler->handle($request, $post)) {
            return new RedirectResponse($this->router->generateUri('admin.posts.index'));
        }

        return $this->renderer->render(
            'admin/posts/edit.html.twig',
            [
                'post' => $post
            ]
        );
    }
}
