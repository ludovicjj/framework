<?php

namespace App\Actions\Admin\Posts;

use App\Domain\Blog\Repository\PostRepository;
use App\Domain\Common\Router\Interfaces\RouterInterface;
use App\Responder\RedirectResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class DeleteAction
{
    /** @var PostRepository */
    private $postRepository;

    /** @var RouterInterface */
    private $router;

    /**
     * DeleteAction constructor.
     * @param PostRepository $postRepository
     * @param RouterInterface $router
     */
    public function __construct(
        PostRepository $postRepository,
        RouterInterface $router
    ) {
        $this->postRepository = $postRepository;
        $this->router = $router;
    }

    /**
     * Delete Post action
     *
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     */
    public function delete(ServerRequestInterface $request): ResponseInterface
    {
        $this->postRepository->delete((int)$request->getAttribute('id'));
        return new RedirectResponse($this->router->generateUri('admin.posts.index'));
    }
}
