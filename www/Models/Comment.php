<?php

namespace App\Models;

use App\Core\Helpers;
use App\Core\Database;
use App\Core\FormBuilder;
use App\Core\Request;
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

    private ?array $actions = [];
    private ?array $actionsdeletedcomment = [];

    //foreign properties
    public Article $article;
    public Person $person;

    public function __construct() {
        parent::__construct();
        $this->article = new Article();
        $this->person = new Person();
        $this->actions = [
            ['name' => 'Supprimer', 'action' => 'delete', 'class' => "delete", 'url' => Helpers::callRoute('comments_delete', ['id' => $this->id])],
            ['name' => "Voir l'article", 'action' => 'go_to', 'url' => Helpers::callRoute('display_article', ['article' => $this->getArticle()->getSlug()])],
        ];

        $this->actionsdeletedcomment = [
            ['name' => 'Supprimer', 'action' => 'delete', 'class' => "delete", 'url' => Helpers::callRoute('users_delete', ['id' => $this->id]), 'role' => 'moderator'],
            ['name' =>'Restaurer', 'action'=> 'update-state', 'class' => 'state-hidden', 'url' => Helpers::callRoute('users_update_state', ['id' => $this->id]), 'role' => 'moderator'],
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

    public function getActionsDeletedComment(): ?array {
        return $this->actionsdeletedcomment;
    }

    public function setActions(?array $actions): void {
        $this->actions = $actions;
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

    public function createCommentForm($articleSlug) {
        
        return [
            "config" => [
                "method" => "POST",
                "action" => "",
                "class" => "form_control",
                "id" => "form_create_comment",
                "submit" => "Publier",
                "referer" => Helpers::callRoute('display_article', ["article" => $articleSlug]),
            ],
            "fields" => [
                "csrfToken" => [
                    "type" => "hidden",
                    "value" => FormBuilder::generateCSRFToken()
                ],
                "comment" => [
                    "type" => "textarea",
                    "placeholder" => "Contenu de votre commentaire...",
                    "minLength" => 3,
                    "maxLength" => 255,
                    "class" => "new-comment textarea-comment",
                    "error" => "Le longueur du titre doit être comprise entre 3 et 255 caractères",
                    "required" => true
                ]
            ]
        ];
    }

}