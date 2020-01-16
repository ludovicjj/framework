<?php

namespace App\Domain\Entity;

class PostEntity
{
    private $id;

    private $name;

    private $slug;

    private $content;

    public $created_at;

    public $updated_at;

    /**
     * PostEntity constructor.
     * @throws \Exception
     */
    public function __construct()
    {
        if ($this->created_at) {
            $this->created_at = new \DateTime($this->created_at);
        }
        if ($this->updated_at) {
            $this->updated_at = new \DateTime($this->updated_at);
        }
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getSlug(): string
    {
        return $this->slug;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function getCreatedAt(): \DateTime
    {
        return $this->created_at;
    }

    public function getUpdatedAt(): \DateTime
    {
        return $this->updated_at;
    }

    /**
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @param string $slug
     */
    public function setSlug(string $slug): void
    {
        $this->slug = $slug;
    }

    /**
     * @param string $content
     */
    public function setContent(string $content): void
    {
        $this->content = $content;
    }
}
