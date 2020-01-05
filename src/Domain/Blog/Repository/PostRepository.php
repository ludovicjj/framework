<?php

namespace App\Domain\Blog\Repository;

use App\Domain\Blog\Entity\PostEntity;
use App\Domain\Common\Pagination\PaginationQuery;
use Pagerfanta\Pagerfanta;
use PDO;

class PostRepository
{
    /** @var PDO */
    private $pdo;

    /**
     * PostRepository constructor.
     * @param PDO $pdo
     */
    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * Find post by ID
     *
     * @param int $id
     * @return PostEntity|null
     */
    public function find(int $id): ?PostEntity
    {
        $query = $this->pdo->prepare('SELECT * FROM posts WHERE posts.id = :id');
        $query->execute(['id' => $id]);
        $query->setFetchMode(PDO::FETCH_CLASS, PostEntity::class);
        return $query->fetch() ?: null;
    }

    /**
     * Paginate Posts
     *
     * @param int $perPage
     * @param int $currentPage
     * @return Pagerfanta
     */
    public function findPaginated(int $perPage, int $currentPage): Pagerfanta
    {
        $query = $this->makePaginationQuery();

        return (new Pagerfanta($query))
            ->setMaxPerPage($perPage)
            ->setCurrentPage($currentPage);
    }

    /**
     * Get the nb of page
     *
     * @param int $perPage
     * @return int
     */
    public function getNbPage(int $perPage): int
    {
        $nbPosts = $this->pdo->query('SELECT COUNT(id) FROM posts')->fetchColumn();
        return ceil($nbPosts / $perPage);
    }

    /**
     * @return PaginationQuery
     */
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
