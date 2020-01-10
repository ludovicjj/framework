<?php

namespace App\Domain\Admin\Handler\Posts;

use App\Domain\Blog\Entity\PostEntity;
use App\Domain\Blog\Repository\PostRepository;
use App\Domain\Common\Session\Interfaces\FlashBagInterface;
use Psr\Http\Message\ServerRequestInterface;

class EditHandler extends AbstractHandler
{
    /** @var PostRepository */
    private $postRepository;

    /** @var FlashBagInterface */
    private $flashBag;

    /**
     * EditHandler constructor.
     * @param PostRepository $postRepository
     * @param FlashBagInterface $flashBag
     */
    public function __construct(
        PostRepository $postRepository,
        FlashBagInterface $flashBag
    ) {
        $this->postRepository = $postRepository;
        $this->flashBag = $flashBag;
    }

    /**
     * Check method from request,
     * filter request body by method getFilterParams() from abstract class AbstractHandler.
     * Update post.
     *
     * @param ServerRequestInterface $request
     * @param PostEntity $post
     * @return bool
     */
    public function handle(ServerRequestInterface $request, PostEntity $post): bool
    {
        if ($request->getMethod() === 'POST') {
            $params = $this->getFilterParams($request);

            $params['updated_at'] = date('Y-m-d H:i:s');

            $this->postRepository->update((int)$post->id, $params);

            $this->flashBag->add('success', 'L\'article a bien été modifié');

            return true;
        }
        return false;
    }
}
