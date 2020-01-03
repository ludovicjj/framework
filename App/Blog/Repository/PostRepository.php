<?php

namespace App\Blog\Repository;

class PostRepository
{
    /** @var \PDO */
    private $pdo;

    public function __construct(\PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * @param int $id
     * @return mixed
     */
    public function find(int $id)
    {
        $query = $this->pdo->prepare('SELECT * FROM posts WHERE posts.id = :id');
        $query->execute(['id' => $id]);
        return $query->fetch();
    }

    public function findPaginated(): array
    {
        return $this->pdo
            ->query('SELECT * FROM posts ORDER BY posts.created_at DESC LIMIT 10')
            ->fetchAll();
    }
}
