<?php

namespace App\Domain\Admin\Handler\Posts;

use App\Domain\Blog\Repository\PostRepository;
use App\Domain\Common\Session\Interfaces\FlashBagInterface;
use Psr\Http\Message\ServerRequestInterface;

class CreateHandler extends AbstractHandler
{
    /** @var PostRepository */
    private $postRepository;

    /** @var FlashBagInterface */
    private $flashBag;

    /**
     * CreateHandler constructor.
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
     * Create post.
     *
     * @param ServerRequestInterface $request
     * @return bool
     */
    public function handle(ServerRequestInterface $request): bool
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
            $this->flashBag->add('success', 'L\'article a bien été ajouté');

            return true;
        }
        return false;
    }
}
