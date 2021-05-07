<?php

namespace App\Models;

use App\Core\Database;
use App\Core\Traits\ModelsTrait;

class Comment extends Database {

    use ModelsTrait;

    private $id = null;
    protected $content;
    protected $visible;
    protected $updateAt;

    //foreign key
    protected $uvtr_article_id;
    protected $uvtr_person_id;

    //foreign properties
    public $article;
    public $person;

    public function __construct()
    {
        $this->article = new Article();
        $this->person = new Person();
    }

    /**
     * @return null
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @param mixed $content
     */
    public function setContent($content): void
    {
        $this->content = $content;
    }

    /**
     * @return mixed
     */
    public function getVisible()
    {
        return $this->visible;
    }

    /**
     * @param mixed $visible
     */
    public function setVisible($visible): void
    {
        $this->visible = $visible;
    }

    /**
     * @return mixed
     */
    public function getUpdateAt()
    {
        return $this->updateAt;
    }

    /**
     * @param mixed $updateAt
     */
    public function setUpdateAt($updateAt): void
    {
        $this->updateAt = $updateAt;
    }

    /**
     * @return mixed
     */
    public function getUvtrArticleId()
    {
        return $this->uvtr_article_id;
    }

    /**
     * @param mixed $uvtr_article_id
     */
    public function setUvtrArticleId($uvtr_article_id): void
    {
        $this->uvtr_article_id = $uvtr_article_id;
    }

    /**
     * @return mixed
     */
    public function getUvtrPersonId()
    {
        return $this->uvtr_person_id;
    }

    /**
     * @param mixed $uvtr_person_id
     */
    public function setUvtrPersonId($uvtr_person_id): void
    {
        $this->uvtr_person_id = $uvtr_person_id;
    }

    /**
     * @return Article
     */
    public function getArticle(): Article
    {
        if(!empty($this->uvtr_article_id) && is_numeric($this->uvtr_article_id))
            $this->article->setId($this->uvtr_article_id);
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

        if(!empty($this->uvtr_person_id) && is_numeric($this->uvtr_person_id))
            $this->person->setId($this->uvtr_person_id);
        return $this->person;
    }

    /**
     * @param Person $person
     */
    public function setPerson(Person $person): void
    {
        $this->person = $person;
    }

}