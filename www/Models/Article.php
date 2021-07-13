<?php

namespace App\Models;

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
    protected $titleSeo;
    protected $descriptionSeo;
    protected $contentUpdatedAt;
    protected $publicationDate;
    protected $mediaId;
    protected $personId;
    private $createdAt;
    private $updatedAt;
    protected $deletedAt;

    public Media $media;
    public Person $person;
    private array $comments = [];

    private array $actions;

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
        if (!empty($this->mediaId) && is_numeric($this->mediaId))
            $this->media->setId($this->mediaId);

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
     * @return array
     */
    public function getComments(): array
    {
        $comments = new Comment();
        $this->comments = $comments->select()->where('articleId', $this->id)->get();
        return $this->comments;
    }

    /**
     * @param array $comments
     */
    public function setComments(array $comments): void
    {
        $this->comments = $comments;
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
    public function getActions(): array
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
            ->where("publicationDate", $now, ">")
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

    public function getArticleState() : string {
        if (!empty($this->getDeletedAt())) 
            return "removed";

        if (empty($this->getDeletedAt()) && empty($this->getPublicationDate()))
            return "draft";

        if (empty($this->getDeletedAt()) && (strtotime($this->getPublicationDate()) <= strtotime("now"))) 
            return "published";

        if (empty($this->getDeletedAt()) && (strtotime($this->getPublicationDate()) > strtotime("now")))
            return "scheduled";

        return false;
    }

    public function getCleanPublicationDate() {
        if (!is_null($this->getPublicationDate())) {
            return date("d/m/Y à H:i", strtotime($this->getPublicationDate()));
        } else {
            return "";
        }
    }

    public function hasDuplicateSlug($title, $id = null) : bool {
        $slug = Helpers::slugify($title);
        if (empty($id))
            $DBslug = $this->select("slug")->where("slug", $slug)->first(0);
        else 
            $DBslug = $this->select("slug")->where("slug", $slug)->andWhere("id", $id, "!=")->first(0);

        return !empty($DBslug);
    }

    public function setDefaultPicture() {
        if(file_exists(getcwd().PATH_TO_IMG.'default_article.png')) {
            $media = new Media();
            $defaultImage = $media->findOneBy('path', PATH_TO_IMG.'default_article.png');
            if($defaultImage) {
                $this->setMediaId($defaultImage->getId());
            } else {
                die("Article default image is not in database");
            }
        } else {
            die('Default image '.getcwd().PATH_TO_IMG.'default_article.png does not exist');
        }
    }

    public function getCategoriesRelated()
    {
        $categoryArticleModel = new CategoryArticleModel;
        $categoryModel = new CategoryModel;

        $categoriesId = $categoryArticleModel->select('categoryId')->where('articleId', $this->id)->get(false);
        $categories = $categoryModel->select()->whereIn('id', $categoriesId)->get();
      
        return $categories;
    }

    public function setToPublished() {
        $today = date("Y-m-d\TH:i");

        $this->setPublicationDate($today);
        $this->setDeletedAt(null);

        if (!empty($this->getId())) {
            $this->toggleComments();
        }
    }

    public function setToScheduled($publicationDate) {
        $this->setPublicationDate(htmlspecialchars($publicationDate));
        $this->setDeletedAt(null);

        if (!empty($this->getId())) {
            $this->toggleComments();
        }
    }

    public function setToDraft() {
        $this->setPublicationDate(null);
        $this->setDeletedAt(null);

        if (!empty($this->getId())) {
            $this->toggleComments();
        }
    }

    public function articleSoftDelete() {
        $this->toggleComments("hide");
        $this->delete();
    }

    // state == display / hide
    public function toggleComments($state = "display") {
        if (!($state == "display" || $state == "hide")) return;

        $comments = $this->getComments();
        foreach ($comments as $comment) {
            if ($state == "hide") {
                $comment->delete();
            } else {
                $comment->setDeletedAt(null);
                $comment->save();
            }
        }
    }

    public function articleHardDelete() {
        $categoryArticle = new CategoryArticleModel();
        $articleId = $this->getId();

        $entries = $categoryArticle->select()->where("articleId", $articleId)->get();
        foreach ($entries as $entry) {
            $entry->hardDelete()->execute();
        }

        $comments = $this->getComments();
        foreach ($comments as $comment) {
            $comment->hardDelete()->execute();
        }

        $this->hardDelete()->where("id", $articleId)->execute();
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
            // "totalViews" => $this->getTotalViews(),
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

    public function formBuilderCreateArticle(): array
    {

        $today = date("Y-m-d\TH:i");
        $todayText = date("Y-m-d H:i");

        $category = new CategoryModel();     

        $categories = $category->findAll();
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
                    "readonly" => true,
                ],
                "media" => [
                    "type" => "text",
                    "label" => "Media utilisé pour la cover de l'article",
                    "class" => "search-bar",
                    "readonly" => true
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

    public function formBuilderUpdateArticle($articleId): array
    {

        $today = date("Y-m-d\TH:i");
        $todayText = date("Y-m-d H:i");

        $media = new MediaModel();
        $category = new CategoryModel();
        $categoryArticle = new CategoryArticleModel();
        $this->setId($articleId);
        
        $mediaId = $this->select("MediaId")->where("id", $articleId)->first(0);
        $mediaTitle = $media->select("title")->where("id", $mediaId)->first(0);

        $categories = $category->findAll();
        $categoriesByArticle = $categoryArticle->select()->where("articleId", $articleId, "=")->get();
        $categoryOptions = [];

        // Get all categories and check its checkboxes if necessary
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
            $this->getCleanPublicationDate();
        }

        $state = $this->getArticleState();

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
                    "error" => "Le champs état est vide",
                    "options" => [
                        [
                            "value" => "published",
                            "class" => "statePublished",
                            "text" => "Republier maintenant",
                        ],
                        [
                            "value" => "scheduled",
                            "class" => "stateScheduled",
                            "text" => "Re planifier à plus tard"
                        ],
                        [
                            "value" => "draft",
                            "class" => "stateDraft",
                            "text" => "Brouillon",
                            "checked" => $state === "draft"
                        ],
                        [
                            "value" => "removed",
                            "text" => "Supprimer",
                        ],
                        [
                            "value" => "nothing",
                            "text" => "Ne rien changer",
                            "checked" => $state !== "draft"

                        ]
                    ],
                ],
                "publicationDate" => [
                    "type" => "datetime-local",
                    "label" => "Date de la planification",
                    "class" => "search-bar publicationDateInput",
                    "error" => "Votre date de publication doit être au minimum " . $todayText ,
                    "min" => $today,
                    "value" => $this->getPublicationDate(),
                    "readonly" => $state !== "scheduled"
                ],
                "media" => [
                    "type" => "text",
                    "label" => "Media utilisé pour la cover de l'article",
                    "class" => "search-bar",
                    "readonly" => true,
                    "value" => $mediaTitle,
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