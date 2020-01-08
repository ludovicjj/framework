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

    public function testUpdate()
    {
        $this->seedDatabase();
        $this->postRepository->update(1, ['name' => 'demo', 'slug' => 'demo-slug']);
        $post = $this->postRepository->find(1);
        $this->assertEquals('demo', $post->name);
        $this->assertEquals('demo-slug', $post->slug);
    }

    public function testInsert()
    {
        $this->postRepository->insert(['name' => 'demo', 'slug' => 'demo-slug']);
        $post = $this->postRepository->find(1);
        $this->assertInstanceOf(PostEntity::class, $post);
        $this->assertEquals('demo', $post->name);
        $this->assertEquals('demo-slug', $post->slug);
    }

    public function testDelete()
    {
        $this->postRepository->insert(['name' => 'demo1', 'slug' => 'demo-slug-1']);
        $this->postRepository->insert(['name' => 'demo2', 'slug' => 'demo-slug-2']);
        $count = $this->pdo->query("SELECT COUNT(id) FROM posts")->fetchColumn();
        $this->assertEquals(2, $count);

        $this->postRepository->delete($this->pdo->lastInsertId());
        $count = $this->pdo->query("SELECT COUNT(id) FROM posts")->fetchColumn();
        $this->assertEquals(1, $count);
    }
}
