<?php

namespace App\Domain\Blog\Repository;

use App\Domain\Blog\Entity\PostEntity;
use App\Domain\Common\Pagination\PaginationQuery;
use Pagerfanta\Pagerfanta;

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
     * @return PostEntity|null
     */
    public function find(int $id): ?PostEntity
    {
        $query = $this->pdo->prepare('SELECT * FROM posts WHERE posts.id = :id');
        $query->execute(['id' => $id]);
        $query->setFetchMode(\PDO::FETCH_CLASS, PostEntity::class);
        $result = $query->fetch();
        if ($result === false) {
            return null;
        }

        return $result;
    }

    public function findPaginated(int $perPage, int $currentPage): Pagerfanta
    {
        $query = $this->makePaginationQuery();

        return (new Pagerfanta($query))
            ->setMaxPerPage($perPage)
            ->setCurrentPage($currentPage);
    }

    public function getNbPage(int $perPage): int
    {
        $query = $this->makePaginationQuery();

        return (new Pagerfanta($query))
            ->setMaxPerPage($perPage)
            ->setCurrentPage(1)
            ->getNbPages();
    }

    private function makePaginationQuery(): PaginationQuery
    {
        return new PaginationQuery(
            $this->pdo,
            'SELECT * FROM posts ORDER BY created_at DESC',
            'SELECT COUNT(id) FROM posts',
            PostEntity::class
        );
    }
}
