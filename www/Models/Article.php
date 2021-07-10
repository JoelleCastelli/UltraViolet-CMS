<?php

namespace App\Models;

use App\Controller\Category;
use App\Core\Database;
use App\Core\Helpers;
use App\Core\Traits\ModelsTrait;
use App\Core\FormBuilder;
use JsonSerializable;
use App\Models\Media as MediaModel;
use App\Models\Category as CategoryModel; 
use App\Models\CategoryArticle as CategoryArticleModel;

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

    // MODEL-BASED FUNCTIONS

    public function getArticlesBySate($state) : array {
        $now = date('Y-m-d H:i:s');

        if ($state == "published") {
           return $this->select()
           ->where("publicationDate", $now, "<=")
           ->andWhere("deletedAt", "NULL", "=")
           ->get();
        } 
        
        if ($state == "scheduled") {
            return $this->select()
            ->where("publicationDate", $now, ">=")
            ->andWhere("deletedAt", "NULL")
            ->get();
        } 
        
        if ($state == "draft") {
            return $this->select()
            ->where("publicationDate", "NULL")
            ->andWhere("deletedAt", "NULL")
            ->get();
        } 
        
        if ($state == "removed") {
            return $this->select()->where("deletedAt", "NOT NULL")->get();
        }

        return [];

    }

    public function cleanPublicationDate() {
        $this->setPublicationDate(date("Y-m-d\TH:i", strtotime($this->getPublicationDate())));
    }

    // JSON FORMAT

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
            "publicationDate" => $this->getPublicationDate(),
            "contentUpdatedAt" => $this->getContentUpdatedAt(),
            "createdAt" => $this->getCreatedAt(),
            "updatedAt" => $this->getUpdatedAt(),
            "deletedAt" => $this->getDeletedAt(),
        ];
    }

    // FORMS

    public function formBuilderCreateArticle() {

        $today = date("Y-m-d\TH:i");
        $todayText = date("Y-m-d H:i");

        $media = new MediaModel();
        $category = new CategoryModel();

        $medias = $media->findAll();
        $categories = $category->findAll();

        $mediaOptions = [];

        foreach ($medias as $media) {
           array_push($mediaOptions, [
                "value" => $media->getId(),
                "text" => $media->getTitle()
           ]);
        }

        $categoryOptions = [];

        foreach ($categories as $category) {
            array_push($categoryOptions, [
                 "value" => $category->getId(),
                 "text" => $category->getName()
            ]);
         }

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
                    "label" => "Titre de l'article *",
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
                    "label" => "Description de l'article *",
                    "placeholder" => "Description de l'article",
                    "minLength" => 2,
                    "class" => "input",
                    "error" => "La longeur doit être de plus de 2 caracrtères",
                    "required" => true,
                ],
                "state" => [
                    "type" => "radio",
                    "label" => "État *",
                    "class" => "state",
                    "required" => true,
                    "error" => "Le champs état est vide",
                    "options" => [
                        [
                            "value" => "published",
                            "class" => "statePublished",
                            "text" => "Publier maintenant",
                            "checked" => true,
                        ],
                        [
                            "value" => "scheduled",
                            "class" => "stateScheduled",
                            "text" => "Planifier"
                        ],
                        [
                            "value" => "draft",
                            "class" => "stateDraft",
                            "text" => "Brouillon"
                        ],
                    ],
                ],
                "publicationDate" => [
                    "type" => "datetime-local",
                    "label" => "Date de la planification",
                    "class" => "search-bar publicationDateInput",
                    "error" => "Votre date de publication doit être au minimum " . $todayText ,
                    "min" => $today,
                ],
                "media" => [
                    "type" => "select",
                    "label" => "Image de cover *",
                    "class" => "search-bar",
                    "options" => $mediaOptions,
                    "required" => true,
                ],
                "categories" => [
                    "type" => "checkbox",
                    "label" => "Categorie de l'article *",
                    "class" => "form_select",
                    "options" => $categoryOptions,
                    "multiple" => true,
                    "error" => "Vous devez selectionner au moins une catégories."
                ],
                 "content" => [
                     "type" => "textarea",
                     "label" => "Contenu de l'article",
                     "placeholder" => "Contenu de l article",
                     "minLength" => 2,
                     "class" => "input",
                     "error" => "Le longueur du titre doit être comprise entre 2 et 255 caractères",
                     "required" => false,
                 ],
            ]
        ];
    }

    public function formBuilderUpdateArticle($articleId) {

        $today = date("Y-m-d\TH:i");
        $todayText = date("Y-m-d H:i");

        $media = new MediaModel();
        $category = new CategoryModel();
        $categoryArticle = new CategoryArticleModel();
        $this->setId($articleId);
        
        $medias = $media->findAll();
        $mediaOptions = [];

        // Get all media and select one of them is the article is using it
        foreach ($medias as $media) {
            $mediaIsAlreadySelected = false;
            if ($this->getMediaId() == $media->getId()) {
                $mediaIsAlreadySelected = true;
            }
            $options = [
                "value" => $media->getId(),
                "text" => $media->getTitle(),
                "selected" => $mediaIsAlreadySelected,
            ];
           array_push($mediaOptions, $options);
        }

        $categories = $category->findAll();
        $categoriesByArticle = $categoryArticle->select()->where("articleId", $articleId, "=")->get();
        $categoryOptions = [];

        // Get all categories and check its checboxes if necessary
        foreach ($categories as $category) {
            $categoryIsAlreadySelected = false;
            foreach ($categoriesByArticle as $categoryArticle) {
                if ($categoryArticle->getCategoryId() == $category->getId()) {
                    $categoryIsAlreadySelected = true;
                }
            }
            $options = [
                "value" => $category->getId(),
                "text" => $category->getName(),
                "checked" => $categoryIsAlreadySelected,
            ];
            array_push($categoryOptions, $options);
         }

         if ($this->getPublicationDate()) {
             $this->cleanPublicationDate();
         }

        return [
            "config" => [
                "method" => "POST",
                "action" => "",
                "class" => "form_control",
                "id" => "form_create_article",
                "submit" => "Valider les modifications",
                "referer" => Helpers::callRoute('article_update', ['id' => $articleId]),
            ],
            "fields" => [
                "csrfToken" => [
                    "type" => "hidden",
                    "value" => FormBuilder::generateCSRFToken()
                ],
                "title" => [
                    "type" => "text",
                    "placeholder" => "Titre de l'article",
                    "label" => "Titre de l'article *",
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
                    "label" => "Description de l'article *",
                    "minLength" => 2,
                    "class" => "input",
                    "error" => "La longeur doit être de plus de 2 caracrtères",
                    "required" => true,
                ],
                "state" => [
                    "type" => "radio",
                    "label" => "État *",
                    "class" => "state",
                    "required" => true,
                    "error" => "Le champs état est vide",
                    "options" => [
                        [
                            "value" => "published",
                            "class" => "statePublished",
                            "text" => "Publier maintenant",
                            "checked" => true,
                        ],
                        [
                            "value" => "scheduled",
                            "class" => "stateScheduled",
                            "text" => "Planifier"
                        ],
                        [
                            "value" => "draft",
                            "class" => "stateDraft",
                            "text" => "Brouillon"
                        ],
                    ],
                ],
                "publicationDate" => [
                    "type" => "datetime-local",
                    "label" => "Date de la planification",
                    "class" => "search-bar publicationDateInput",
                    "error" => "Votre date de publication doit être au minimum " . $todayText ,
                    "min" => $today,
                    "value" => $this->getPublicationDate()
                ],
                "media" => [
                    "type" => "select",
                    "label" => "Image de cover *",
                    "class" => "search-bar",
                    "options" => $mediaOptions,
                    "required" => true,
                    "error" => "Vous devez sélectionner un media."
                ],
                "categories" => [
                    "type" => "checkbox",
                    "label" => "Categorie de l'article *",
                    "class" => "form_select",
                    "options" => $categoryOptions,
                    "multiple" => true,
                    "error" => "Vous devez selectionner au moins une catégories."
                ],
                "content" => [
                    "type" => "textarea",
                    "placeholder" => "Contenu de l article",
                    "label" => "Contenu de l'article",
                    "minLength" => 2,
                    "class" => "input",
                    "error" => "Le longueur du titre doit être comprise entre 2 et 255 caractères",
                    "required" => false,
                ],
            ]
        ];
    }

}