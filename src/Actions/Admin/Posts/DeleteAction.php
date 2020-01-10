<?php

namespace App\Actions\Admin\Posts;

use App\Domain\Blog\Repository\PostRepository;
use App\Domain\Common\Exception\NotFoundRecordsException;
use App\Domain\Common\Router\Interfaces\RouterInterface;
use App\Domain\Common\Session\Interfaces\FlashBagInterface;
use App\Responder\RedirectResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class DeleteAction
{
    /** @var PostRepository */
    private $postRepository;

    /** @var RouterInterface */
    private $router;

    /** @var FlashBagInterface */
    private $flashBag;

    /**
     * DeleteAction constructor.
     * @param PostRepository $postRepository
     * @param RouterInterface $router
     * @param FlashBagInterface $flashBag
     */
    public function __construct(
        PostRepository $postRepository,
        RouterInterface $router,
        FlashBagInterface $flashBag
    ) {
        $this->postRepository = $postRepository;
        $this->router = $router;
        $this->flashBag = $flashBag;
    }

    /**
     * Delete Post action
     *
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     * @throws NotFoundRecordsException
     */
    public function delete(ServerRequestInterface $request): ResponseInterface
    {
        $post = $this->postRepository->find($request->getAttribute('id'));

        if (\is_null($post)) {
            throw new NotFoundRecordsException(
                sprintf(
                    'Not found post with id : %s',
                    $request->getAttribute('id')
                )
            );
        }

        $this->postRepository->delete((int)$post->id);
        $this->flashBag->add('success', 'L\'article a bien été supprimé');
        return new RedirectResponse($this->router->generateUri('admin.posts.index'));
    }
}
