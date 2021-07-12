<?php

namespace App\Models;

use App\Core\Helpers;
use App\Core\Database;
use App\Core\Traits\ModelsTrait;

class Comment extends Database {

    use ModelsTrait;

    private ?int $id = null;
    protected string $content;
    protected bool $visible = true;
    protected ?string $updatedAt;

    // Foreign keys
    protected int $articleId;
    protected int $personId;

    //foreign properties
    public Article $article;
    public Person $person;

    public function __construct()
    {
        parent::__construct();
        $this->article = new Article();
        $this->person = new Person();
        $this->actions = [
            ['name' => 'Modifier', 'action' => 'modify', 'url' => Helpers::callRoute('comments_update', ['id' => $this->id]), 'role' => 'moderator'],
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
     * @return bool
     */
    public function isVisible(): bool
    {
        return $this->visible;
    }

    /**
     * @param bool $visible
     */
    public function setVisible(bool $visible): void
    {
        $this->visible = $visible;
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
     * @return mixed
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
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
    public function setpersonId(int $personId): void
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

}