<?php

namespace App\Models;

use App\Core\Helpers;
use App\Core\Database;
use App\Core\Traits\ModelsTrait;

class Comment extends Database {

    use ModelsTrait;

    private ?int $id = null;
    protected string $content;
    private string $createdAt;
    private ?string $updatedAt;
    protected ?string $deletedAt = null;

    // Foreign keys
    protected int $articleId;
    protected int $personId;

    //foreign properties
    public Article $article;
    public Person $person;

    private array $actions;

    public function __construct()
    {
        parent::__construct();
        $this->article = new Article();
        $this->person = new Person();
        $this->actions = [
            
            ['name' => 'Supprimer', 'action' => 'delete', 'url' => Helpers::callRoute('comments_delete', ['id' => $this->id]), 'role' => 'moderator'],
        ];
        
    }

    /**
     * @return null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getContent(): string
    {
        return $this->content;
    }

    /**
     * @param string $content
     */
    public function setContent(string $content): void
    {
        $this->content = $content;
    }

    /**
     * @return string
     */
    public function getCreatedAt(): string
    {
        return $this->createdAt;
    }

    /**
     * @return string|null
     */
    public function getUpdatedAt(): ?string
    {
        return $this->updatedAt;
    }

    /**
     * @param string|null $updatedAt
     */
    public function setUpdatedAt(?string $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }

    /**
     * @return string|null
     */
    public function getDeletedAt(): ?string
    {
        return $this->deletedAt;
    }

    /**
     * @param string|null $deletedAt
     */
    public function setDeletedAt(?string $deletedAt): void
    {
        $this->deletedAt = $deletedAt;
    }

    /**
     * @return int
     */
    public function getArticleId(): int
    {
        return $this->articleId;
    }

    /**
     * @param int $articleId
     */
    public function setArticleId(int $articleId): void
    {
        $this->articleId = $articleId;
    }

    /**
     * @return int
     */
    public function getPersonId(): int
    {
        return $this->personId;
    }

    /**
     * @param int $personId
     */
    public function setPersonId(int $personId): void
    {
        $this->personId = $personId;
    }


    /**
     * @return mixed
     */
    public function getActions(): ?array {
        return $this->actions;
    }

    /**
     * @return Article
     */
    public function getArticle(): Article
    {
        if(!empty($this->articleId) && is_numeric($this->articleId))
            $this->article->setId($this->articleId);
        return $this->article;
    }

    /**
     * @param Article $article
     */
    public function setArticle(Article $article): void
    {
        $this->article = $article;
    }

    /**
     * @return Person
     */
    public function getPerson(): Person
    {
        if(!empty($this->personId) && is_numeric($this->personId))
            $this->person->setId($this->personId);
        return $this->person;
    }

    /**
     * @param Person person
     */
    public function setPerson(Person $person): void
    {
        $this->person = $person;
    }

    public function getCleanCreationDate() {
        if (!is_null($this->getCreatedAt())) {
            return date("d/m/Y à H:i", strtotime($this->getCreatedAt()));
        } else {
            return "";
        }
    }

}