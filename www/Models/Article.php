<?php

namespace App\Models;

use App\Core\Database;
use App\Core\Traits\ModelsTrait;
use App\Core\FormBuilder;

class Article extends Database
{
    use ModelsTrait;

    private $id = null;
    protected $title;
    protected $description;
    protected $content;
    protected $rating;
    protected $slug;
    protected $state;
    protected $totalViews;
    protected $titleSeo;
    protected $descriptionSeo;
    protected $contentUpdatedAt;

    public $media;
    public $person;

    public function __construct() {
        parent::__construct();
        $this->media = new Media;
        $this->person = new Person;
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
    public function getState()
    {
        return $this->state;
    }

    /**
     * @param mixed $state
     */
    public function setState($state): void
    {
        $this->state = $state;
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
        return $this->person;
    }

    /**
     * @param Person $person
     */
    public function setPerson(Person $person): void
    {
        $this->person = $person;
    }

    public function findAll() {
        return parent::findAll();
    }

    public function selectWhere($column, $value) {
        return parent::selectWhere($column, $value);
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
                "referer" => '/creer-un-article'
            ],
            "fields" => [
                "csrf_token" => [
                    "type" => "hidden",
                    "value" => FormBuilder::generateCSRFToken()
                ],
                "title" => [
                    "type" => "text",
                    "placeholder" => "Titre de l article",
                    "minLength" => 2,
                    "maxLength" => 100,
                    "class" => "input",
                    "error" => "Le longueur du titre doit être comprise entre 2 et 100 caractères",
                    // "regex" => "/^[a-zA-ZàáâäãåąčćęèéêëėįìíîïłńòóôöõøùúûüųūÿýżźñçčšžÀÁÂÄÃÅĄĆČĖĘÈÉÊËÌÍÎÏĮŁŃÒÓÔÖÕØÙÚÛÜŲŪŸÝŻŹÑßÇŒÆČŠŽ∂ð -]+$/u",
                    "required" => true,
                ],
                "description" => [
                    "type" => "text",
                    "placeholder" => "Description de l article",
                    "minLength" => 2,
                    "maxLength" => 255,
                    "class" => "input",
                    "error" => "Le longueur du titre doit être comprise entre 2 et 255 caractères",
                    "required" => true,
                ],
                "content" => [
                    "type" => "text",
                    "placeholder" => "Contenu de l article",
                    "minLength" => 2,
                    // "maxLength" => 255,
                    "class" => "input",
                    "error" => "Le longueur du titre doit être comprise entre 2 et 255 caractères",
                    "required" => true,
                ],
                "state"=>[
                    "type"=>"radio",
                    "label"=>"État :",
                    "class"=>"",
                    "error"=>"Erreur test",
                    "required" => true,
                    "options" => [
                        [
                            "value"=>"draft",
                            "text"=>"Brouillon",
                        ],
                        [
                            "value"=>"scheduled",
                            "text"=>"Planifié",
                        ],
                        [
                            "value"=>"published",
                            "text"=>"Publié",
                        ],
                    ],
                ],
            ]
        ];
    }

}