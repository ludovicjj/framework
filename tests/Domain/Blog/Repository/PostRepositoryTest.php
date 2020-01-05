<?php

namespace Tests\Domain\Blog\Repository;

use App\Domain\Blog\Entity\PostEntity;
use App\Domain\Blog\Repository\PostRepository;
use Tests\DatabaseTestCase;

class PostRepositoryTest extends DatabaseTestCase
{
    /**
     * @var PostRepository
     */
    private $postRepository;

    public function setUp(): void
    {
        parent::setUp();
        $this->postRepository = new PostRepository($this->pdo);
    }

    public function testFind()
    {
        $this->seedDatabase();
        $post = $this->postRepository->find(1);
        $this->assertInstanceOf(PostEntity::class, $post);
    }

    public function testFindNotFoundRecord()
    {
        $post = $this->postRepository->find(1000);
        $this->assertNull($post);
    }
}
