<?php

namespace App\Models;

use App\Core\Database;
use App\Core\Traits\ModelsTrait;

class Comment extends Database {

    use ModelsTrait;

    private $id = null;
    protected string $content;
    protected bool $visible;
    protected string $updatedAt;

    // Foreign keys
    protected int $articleId;
    protected int $authorId;

    //foreign properties
    public Article $article;
    public Person $author;

    public function __construct()
    {
        parent::__construct();
        $this->article = new Article();
        $this->author = new Person();
    }

    /**
     * @return null
     */
    public function getId()
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
     * @return string
     */
    public function getUpdatedAt(): string
    {
        return $this->updatedAt;
    }

    /**
     * @param string $updatedAt
     */
    public function setUpdatedAt(string $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
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
     * @param int $authorId
     */
    public function setAuthorId(int $authorId): void
    {
        $this->authorId = $authorId;
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
    public function getAuthor(): Person
    {
        if(!empty($this->authorId) && is_numeric($this->authorId))
            $this->author->setId($this->authorId);
        return $this->author;
    }

    /**
     * @param Person $author
     */
    public function setAuthor(Person $author): void
    {
        $this->author = $author;
    }

}