<?php

namespace App\Blog\Actions;

use App\Blog\Repository\PostRepository;
use Framework\Renderer\Interfaces\TwigRendererInterface;

class BlogListAction
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
     * Show all posts
     *
     * @return string
     */
    public function __invoke(): string
    {
        $posts = $this->postRepository->findPaginated();

        return $this->twig->render(
            '@blog/index.html.twig',
            [
                'posts' => $posts
            ]
        );
    }
}
