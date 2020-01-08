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
     * Update post in database
     *
     * @param int $id
     * @param array $params
     * @return bool
     */
    public function update(int $id, array $params): bool
    {
        $queryField = $this->buildFieldQuery($params);

        // add ID into params
        $params['id'] = $id;

        $query = $this->pdo->prepare("UPDATE posts SET $queryField WHERE id = :id");
        return $query->execute($params);
    }

    /**
     * Create Post
     * @param array $params
     * @return bool
     */
    public function insert(array $params): bool
    {
        $fields = array_keys($params);

        $values = join(', ', array_map(function ($value) {
            return ":$value";
        }, $fields));

        $fields = join(', ', $fields);

        $query = $this->pdo->prepare("INSERT INTO posts ($fields) VALUES ($values)");
        return $query->execute($params);
    }

    /**
     * Delete one Post by ID
     *
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool
    {
        $query = $this->pdo->prepare('DELETE FROM posts WHERE id = :id');
        return $query->execute(['id' => $id]);
    }

    /**
     * Helper to build field query.
     * Exemple :  "name = :name, content = :content, ..."
     *
     * @param array $params
     * @return string
     */
    private function buildFieldQuery(array $params): string
    {
        return join(', ', array_map(function ($keyField) {
            return "$keyField = :$keyField";
        }, array_keys($params)));
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
