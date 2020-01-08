<?php

namespace App\Domain\Admin\Handler\Posts;

use App\Domain\Blog\Entity\PostEntity;
use App\Domain\Blog\Repository\PostRepository;
use Psr\Http\Message\ServerRequestInterface;

class EditHandler extends AbstractHandler
{
    /** @var PostRepository */
    private $postRepository;

    /**
     * EditHandler constructor.
     * @param PostRepository $postRepository
     */
    public function __construct(PostRepository $postRepository)
    {
        $this->postRepository = $postRepository;
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

            $this->postRepository->update(
                (int)$post->id,
                $params
            );

            return true;
        }
        return false;
    }
}
