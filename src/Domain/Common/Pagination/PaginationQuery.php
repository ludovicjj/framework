<?php

namespace App\Domain\Common\Pagination;

use Pagerfanta\Adapter\AdapterInterface;

class PaginationQuery implements AdapterInterface
{
    /** @var \PDO $pdo */
    private $pdo;

    /** @var string $query */
    private $query;

    /** @var string $queryCount */
    private $queryCount;

    /** @var string */
    private $entity;

    /**
     * PaginationQuery constructor.
     * @param \PDO $pdo
     * @param string $query Query for get X items
     * @param string $queryCount Query for get nb of all items
     * @param string $entity
     */
    public function __construct(
        \PDO $pdo,
        string $query,
        string $queryCount,
        string $entity
    ) {
        $this->pdo = $pdo;
        $this->query = $query;
        $this->queryCount = $queryCount;
        $this->entity = $entity;
    }

    /**
     * Returns the number of results.
     *
     * @return integer The number of results.
     */
    public function getNbResults(): int
    {
        return $this->pdo->query($this->queryCount)->fetchColumn();
    }

    /**
     * Returns an slice of the results.
     *
     * @param integer $offset The offset.
     * @param integer $length The length.
     *
     * @return array|\Traversable The slice.
     */
    public function getSlice($offset, $length): array
    {
        $query = $this->pdo->prepare($this->query . ' LIMIT :offset, :length');
        $query->bindParam('offset', $offset, \PDO::PARAM_INT);
        $query->bindParam('length', $length, \PDO::PARAM_INT);
        $query->setFetchMode(\PDO::FETCH_CLASS, $this->entity);
        $query->execute();
        return $query->fetchAll();
    }
}
