<?php

namespace App\Domain\Admin\Handler\Posts;

use App\Domain\Blog\Repository\PostRepository;
use Psr\Http\Message\ServerRequestInterface;

class CreateHandler extends AbstractHandler
{
    /** @var PostRepository */
    private $postRepository;

    /**
     * CreateHandler constructor.
     * @param PostRepository $postRepository
     */
    public function __construct(PostRepository $postRepository)
    {
        $this->postRepository = $postRepository;
    }

    /**
     * Check method from request,
     * filter request body by method getFilterParams() from abstract class AbstractHandler.
     * Create post.
     *
     * @param ServerRequestInterface $request
     * @return bool
     */
    public function handler(ServerRequestInterface $request): bool
    {
        if ($request->getMethod() === 'POST') {
            $params = $this->getFilterParams($request);
            $params = array_merge(
                $params,
                [
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s')
                ]
            );

            $this->postRepository->insert($params);

            return true;
        }
        return false;
    }
}
