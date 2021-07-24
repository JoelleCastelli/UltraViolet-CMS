<?php

namespace App\Models;

use App\Controller\Production;
use App\Core\Database;
use App\Core\Helpers;
use App\Core\Traits\ModelsTrait;
use App\Core\FormBuilder;
use JsonSerializable;
use App\Models\Media as MediaModel;
use App\Models\Category as CategoryModel; 
use App\Models\CategoryArticle as CategoryArticleModel;
use App\Models\ProductionArticle as ProductionArticleModel;
use App\Models\Production as ProductionModel;



class Article extends Database implements JsonSerializable
{
    use ModelsTrait;

    private $id = null;
    protected $title;
    protected $description;
    protected $content;
    protected $slug;
    protected $contentUpdatedAt;
    protected $publicationDate;
    protected $mediaId;
    protected $personId;
    private $createdAt;
    private $updatedAt;
    protected $deletedAt;

    private $categories = [];
    private $productions = [];

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
            ['name' => 'Supprimer', 'action' => 'delete', 'class' => "delete", 'url' => Helpers::callRoute('article_delete', ['id' => $this->id])],
        ];
        if($this->publicationDate && $this->publicationDate <= date('Y-m-d H:i:s') && $this->deletedAt == null) {
            $this->actions[] = ['name' => 'Consulter', 'action' => 'go_to', 'url' => Helpers::callRoute('display_article', ['article' => $this->slug])];
        }
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

    public function getCategories() : array {
        return $this->categories;
    }
    
    public function getProductions() : array {
        return $this->productions;
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

    public function getCleanCreatedAt() {
        if (!is_null($this->getCreatedAt())) {
            return date("d/m/Y à H:i", strtotime($this->getCreatedAt()));
        } else {
            return "";
        }
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
      
        $this->categories = $categories;
        return $categories;
    }

    public function getProductionsRelated() {
        $productionArticle = new ProductionArticleModel();
        $production = new ProductionModel();

        $productionsId =  $productionArticle->select("productionId")->where("articleId", $this->id)->get(false);
        $productions = $production->select()->whereIn("id", $productionsId)->get();

        $this->productions = $productions;
        return $productions;
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
            "slug" => $this->getSlug(),
            // "totalViews" => $this->getTotalViews(),
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
                "class" => "form_control card",
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
                    "minLength" => 2,
                    "maxLength" => 100,
                    "class" => "input search-bar",
                    "error" => "La longueur du titre doit être comprise entre 2 et 100 caractères",
                    "required" => true,
                ],
                "description" => [
                    "type" => "textarea",
                    "label" => "Description de l'article *",
                    "minLength" => 2,
                    "maxLength" => 255,
                    "class" => "input search-bar",
                    "error" => "La longueur de la description doit être comprise entre 2 et 100 caractères",
                    "required" => true,
                ],
                "production" => [
                    "type" => "text",
                    "label" => "Associer prod à article",
                    "class" => "search-bar",
                    "readonly" => true
                ],
                "state" => [
                    "type" => "radio",
                    "label" => "État *",
                    "class" => "state",
                    "required" => true,
                    "error" => "Le champs Etat est vide",
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
                    "label" => "Illustration de l'article",
                    "class" => "search-bar",
                    "readonly" => true
                ],
                "categories" => [
                    "type" => "checkbox",
                    "label" => "Catégorie de l'article *",
                    "class" => "form_select",
                    "options" => $categoryOptions,
                    "multiple" => true,
                    "error" => "Vous devez sélectionner au moins une catégorie"
                ],
                 "content" => [
                     "id" => "articleContent",
                     "type" => "textarea",
                     "label" => "Contenu de l'article",
                     "minLength" => 2,
                     "class" => "input",
                     "error" => "Le contenu de l'article doit comprendre au minimum 2 caractères",
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
        $productionArticleModel  = new ProductionArticleModel();
        $productionModel = new ProductionModel();

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

        $productionId = $productionArticleModel->select("productionId")->where("articleId", $articleId)->first(0);
        $productionName = $productionModel->select("title")->where("id", $productionId)->first(0);

        return [
            "config" => [
                "method" => "POST",
                "action" => "",
                "class" => "form_control card",
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
                    "label" => "Titre de l'article *",
                    "minLength" => 2,
                    "maxLength" => 100,
                    "class" => "input search-bar",
                    "error" => "La longueur du titre doit être comprise entre 2 et 100 caractères",
                    "required" => true,
                ],
                "description" => [
                    "type" => "textarea",
                    "label" => "Description de l'article *",
                    "minLength" => 2,
                    "maxLength" => 255,
                    "class" => "input search-bar",
                    "error" => "La longueur de la description doit être comprise entre 2 et 100 caractères",
                    "required" => true,
                ],
                "production" => [
                    "type" => "text",
                    "label" => "Associer prod à article",
                    "class" => "search-bar",
                    "readonly" => true,
                    "value" => $productionName
                ],
                "state" => [
                    "type" => "radio",
                    "label" => "État *",
                    "class" => "state",
                    "error" => "Le champs Etat est vide",
                    "options" => [
                        [
                            "value" => "published",
                            "class" => "statePublished",
                            "text" => "Republier maintenant",
                        ],
                        [
                            "value" => "scheduled",
                            "class" => "stateScheduled",
                            "text" => "Planifier"
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
                    "label" => "Illustration de l'article",
                    "class" => "search-bar",
                    "readonly" => true,
                    "value" => $mediaTitle,
                ],
                "categories" => [
                    "type" => "checkbox",
                    "label" => "Catégorie de l'article *",
                    "class" => "form_select",
                    "options" => $categoryOptions,
                    "multiple" => true,
                    "error" => "Vous devez sélectionner au moins une catégorie"
                ],
                "content" => [
                    "id" => "articleContent",
                    "type" => "textarea",
                    "label" => "Contenu de l'article",
                    "minLength" => 2,
                    "class" => "input",
                    "error" => "Le contenu de l'article doit comprendre au minimum 2 caractères",
                ],
            ]
        ];
    }
}