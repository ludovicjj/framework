<?php

namespace App\Actions\Blog;

use App\Domain\Blog\Repository\PostRepository;
use App\Domain\Common\Exception\NotFoundException;
use App\Domain\Common\Renderer\Interfaces\TwigRendererInterface;
use Psr\Http\Message\ServerRequestInterface;

class ListAction
{
    /** @var PostRepository  */
    private $postRepository;

    /** @var TwigRendererInterface */
    private $twig;

    /**
     * BlogListAction constructor.
     * @param TwigRendererInterface $twig
     * @param PostRepository $postRepository
     */
    public function __construct(
        TwigRendererInterface $twig,
        PostRepository $postRepository
    ) {
        $this->twig = $twig;
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

        return $this->twig->render(
            '/blog/index.html.twig',
            [
                'posts' => $posts
            ]
        );
    }
}
