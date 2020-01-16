<?php

namespace App\Actions\Blog;

use App\Domain\Repository\PostRepository;
use App\Domain\Common\Exception\NotFoundException;
use App\Domain\Common\Renderer\Interfaces\TwigRendererInterface;
use Psr\Http\Message\ServerRequestInterface;

class ListAction
{
    /** @var PostRepository  */
    private $postRepository;

    /** @var TwigRendererInterface */
    private $renderer;

    /**
     * BlogListAction constructor.
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
     * Display all posts
     *
     * @param ServerRequestInterface $request
     * @return string
     * @throws NotFoundException
     */
    public function __invoke(ServerRequestInterface $request): string
    {
        $page = $request->getAttribute('page', 1);
        $nbPage = $this->postRepository->getNbPage(12);

        if ($page < 1 || $page > $nbPage) {
            throw new NotFoundException('page ' . $page . ' not found');
        }

        $posts = $this->postRepository->findPaginated(12, $page);

        return $this->renderer->render(
            'blog/index.html.twig',
            [
                'posts' => $posts
            ]
        );
    }
}
