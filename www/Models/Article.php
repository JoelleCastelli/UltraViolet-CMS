<?php

namespace App\Models;

use App\Core\Database;
use App\Core\Helpers;
use App\Core\Traits\ModelsTrait;
use App\Core\FormBuilder;
use JsonSerializable;

class Article extends Database implements JsonSerializable
{
    use ModelsTrait;

    private $id = null;
    protected $title;
    protected $description;
    protected $content;
    protected $rating;
    protected $slug;
    protected $totalViews = 0;
    protected $titleSeo;
    protected $descriptionSeo;
    protected $contentUpdatedAt;
    protected $publicationDate;
    protected $mediaId;
    protected $personId;
    private $createdAt;
    private $updatedAt;
    protected $deletedAt;

    public $media;
    public $person;

    private $actions;

    public function __construct() {
        parent::__construct();
        $this->media = new Media;
        $this->person = new Person;
        $this->actions = [
            ['name' => 'Modifier', 'action' => 'modify', 'url' => Helpers::callRoute('article_update', ['id' => $this->id])],
            ['name' => 'Supprimer', 'action' => 'delete', 'class' => "delete", 'url' => Helpers::callRoute('article_delete', ['id' => $this->id]), 'role' => 'admin'],
        ];
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
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param mixed $title
     */
    public function setTitle($title): void
    {
        $this->title = $title;
    }

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param mixed $description
     */
    public function setDescription($description): void
    {
        $this->description = $description;
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
    public function getRating()
    {
        return $this->rating;
    }

    /**
     * @param mixed $rating
     */
    public function setRating($rating): void
    {
        $this->rating = $rating;
    }

    /**
     * @return mixed
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * @param mixed $slug
     */
    public function setSlug($slug): void
    {
        $this->slug = $slug;
    }


    /**
     * @return mixed
     */
    public function getTotalViews()
    {
        return $this->totalViews;
    }

    /**
     * @param mixed $totalViews
     */
    public function setTotalViews($totalViews): void
    {
        $this->totalViews = $totalViews;
    }

    /**
     * @return mixed
     */
    public function getTitleSeo()
    {
        return $this->titleSeo;
    }

    /**
     * @param mixed $titleSeo
     */
    public function setTitleSeo($titleSeo): void
    {
        $this->titleSeo = $titleSeo;
    }

    /**
     * @return mixed
     */
    public function getPersonId()
    {
        return $this->personId;
    }

    /**
     * @param mixed $titleSeo
     */
    public function setPersonId($personId): void
    {
        $this->personId = $personId;
    }

    /**
     * @return mixed
     */
    public function getDescriptionSeo()
    {
        return $this->descriptionSeo;
    }

    /**
     * @param mixed $descriptionSeo
     */
    public function setDescriptionSeo($descriptionSeo): void
    {
        $this->descriptionSeo = $descriptionSeo;
    }

    /**
     * @return mixed
     */
    public function getContentUpdatedAt()
    {
        return $this->contentUpdatedAt;
    }

    /**
     * @param mixed $contentUpdatedAt
     */
    public function setContentUpdatedAt($contentUpdatedAt): void
    {
        $this->contentUpdatedAt = $contentUpdatedAt;
    }

    /**
     * @return mixed $publicationDate
     */
    public function getPublicationDate()
    {
        return $this->publicationDate;
    }

    /**
     * @param mixed $publicationDate
     */
    public function setPublicationDate($publicationDate): void
    {
        $this->publicationDate = $publicationDate;
    }

    /**
     * @return Media
     */
    public function getMedia(): Media
    {
        return $this->media;
    }

    /**
     * @param Media $media
     */
    public function setMedia(Media $media): void
    {
        $this->media = $media;
    }

    /**
     * @return Person
     */
    public function getPerson(): Person
    {
        if (!empty($this->personId) && is_numeric($this->personId))
            $this->person->setId($this->personId);
        return $this->person;
    }

    /**
     * @param Person $person
     */
    public function setPerson(Person $person): void
    {
        $this->person = $person;
    }

    /**
     * @return mixed
     */
    public function getMediaId()
    {
        return $this->mediaId;
    }

    /**
     * @param mixed $mediaId
     */
    public function setMediaId($mediaId): void
    {
        $this->mediaId = $mediaId;
    }

    /**
     * @return mixed
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @param mixed $createdAt
     */
    public function setCreatedAt($createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    /**
     * @return mixed
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * @param mixed $updatedAt
     */
    public function setUpdatedAt($updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }

    /**
     * @return mixed
     */
    public function getDeletedAt()
    {
        return $this->deletedAt;
    }

    /**
     * @param mixed $deletedAt
     */
    public function setDeletedAt($deletedAt): void
    {
        $this->deletedAt = $deletedAt;
    }

    /**
     * @return array
     */
    public function getActions()
    {
        return $this->actions;
    }

    public function jsonSerialize(): array
    {
        return [
            "id" => $this->getId(),
            "title" => $this->getTitle(),
            "description" => $this->getDescription(),
            "content" => $this->getContent(),
            "rating" => $this->getRating(),
            "slug" => $this->getSlug(),
            "totalViews" => $this->getTotalViews(),
            "titleSeo" => $this->getTitleSeo(),
            "descriptionSeo" => $this->getDescriptionSeo(),
            "contentUpdatedAt" => $this->getContentUpdatedAt(),
            "createdAt" => $this->getCreatedAt(),
            "updatedAt" => $this->getUpdatedAt(),
            "deletedAt" => $this->getDeletedAt(),
        ];
    }

    // TODO : Voir plus tard SLUG et STATE et aussi avec la jointure de media(pour la photo) et l'auteur
    public function formBuilderCreateArticle() {
        return [
            "config" => [
                "method" => "POST",
                "action" => "",
                "class" => "form_control",
                "id" => "form_create_article",
                "submit" => "Créer un article",
                "referer" => Helpers::callRoute('article_creation'),
            ],
            "fields" => [
                "csrfToken" => [
                    "type" => "hidden",
                    "value" => FormBuilder::generateCSRFToken()
                ],
                "title" => [
                    "type" => "text",
                    "placeholder" => "Titre de l'article",
                    "minLength" => 2,
                    "maxLength" => 100,
                    "class" => "input",
                    "error" => "Le longueur du titre doit être comprise entre 2 et 100 caractères",
                    // "regex" => "/^[a-zA-ZàáâäãåąčćęèéêëėįìíîïłńòóôöõøùúûüųūÿýżźñçčšžÀÁÂÄÃÅĄĆČĖĘÈÉÊËÌÍÎÏĮŁŃÒÓÔÖÕØÙÚÛÜŲŪŸÝŻŹÑßÇŒÆČŠŽ∂ð -]+$/u",
                    "required" => true,
                ],
                "description" => [
                    "type" => "text",
                    "placeholder" => "Description de l'article",
                    "minLength" => 2,
                    "class" => "input",
                    "error" => "La longeur doit être de plus de 2 caracrtères",
                    "required" => true,
                ],
                 "content" => [
                     "type" => "textarea",
                     "placeholder" => "Contenu de l article",
                     "minLength" => 2,
                     "class" => "input",
                     "error" => "Le longueur du titre doit être comprise entre 2 et 255 caractères",
                     "required" => false,
                 ],
                // State radio was here
            ]
        ];
    }

    // TODO : Voir plus tard SLUG et STATE et aussi avec la jointure de media(pour la photo) et l'auteur
    public function formBuilderUpdateArticle($id) {
        return [
            "config" => [
                "method" => "POST",
                "action" => "",
                "class" => "form_control",
                "id" => "form_create_article",
                "submit" => "Valider les modifications",
                "referer" => Helpers::callRoute('article_update', ['id' => $id]),
            ],
            "fields" => [
                "csrfToken" => [
                    "type" => "hidden",
                    "value" => FormBuilder::generateCSRFToken()
                ],
                "title" => [
                    "type" => "text",
                    "placeholder" => "Titre de l'article",
                    "minLength" => 2,
                    "maxLength" => 100,
                    "class" => "input",
                    "error" => "Le longueur du titre doit être comprise entre 2 et 100 caractères",
                    // "regex" => "/^[a-zA-ZàáâäãåąčćęèéêëėįìíîïłńòóôöõøùúûüųūÿýżźñçčšžÀÁÂÄÃÅĄĆČĖĘÈÉÊËÌÍÎÏĮŁŃÒÓÔÖÕØÙÚÛÜŲŪŸÝŻŹÑßÇŒÆČŠŽ∂ð -]+$/u",
                    "required" => true,
                ],
                "description" => [
                    "type" => "text",
                    "placeholder" => "Description de l'article",
                    "minLength" => 2,
                    "class" => "input",
                    "error" => "La longeur doit être de plus de 2 caracrtères",
                    "required" => true,
                ],
                "content" => [
                    "type" => "textarea",
                    "placeholder" => "Contenu de l article",
                    "minLength" => 2,
                    // "maxLength" => 255,
                    "class" => "input",
                    "error" => "Le longueur du titre doit être comprise entre 2 et 255 caractères",
                    "required" => false,
                ],
                // State radio was here
            ]
        ];
    }

}