<?php

namespace App\Actions\Admin\Posts;

use App\Domain\Blog\Repository\PostRepository;
use App\Domain\Common\Exception\NotFoundException;
use App\Domain\Common\Renderer\Interfaces\TwigRendererInterface;
use Psr\Http\Message\ServerRequestInterface;

class IndexAction
{
    /** @var TwigRendererInterface */
    private $renderer;

    /** @var PostRepository */
    private $postRepository;

    /**
     * IndexAction constructor.
     * @param TwigRendererInterface $renderer
     * @param PostRepository $postRepository
     */
    public function __construct(
        TwigRendererInterface $renderer,
        PostRepository $postRepository
    ) {
        $this->renderer = $renderer;
        $this->postRepository = $postRepository;
    }

    /**
     * Dashboard admin Post action
     *
     * @param ServerRequestInterface $request
     * @return string
     * @throws NotFoundException
     */
    public function index(ServerRequestInterface $request)
    {
        $page = $request->getAttribute('page', 1);
        $nbPage = $this->postRepository->getNbPage(12);

        if ($page < 1 || $page > $nbPage) {
            throw new NotFoundException('page ' . $page . ' not found');
        }

        $posts = $this->postRepository->findPaginated(12, $page);
        return $this->renderer->render(
            'admin/posts/index.html.twig',
            [
                'posts' => $posts
            ]
        );
    }
}
