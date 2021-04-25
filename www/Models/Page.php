<?php

namespace App\Models;

use App\Core\Database;
use App\Core\FormBuilder;

class Page extends Database
{

	private $id = null;
	protected $title;
	protected $slug;
	protected $position;
	protected $state;
	protected $titleSeo;
	protected $descriptionSeo;
	protected $publictionDate;
	protected $createdAt;
	protected $updatedAt;
	protected $deletedAt;

	public function __construct(){
		parent::__construct();
	}

    /**
     * @return null
     */
    public function getId()
    {
        return $this->id;
    }

	/**
	 * @param mixed $id
	 */
	public function setId($id): void {
	    $this->id = $id;
        $this->findOneById($this->id);
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
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * @param mixed $position
     */
    public function setPosition($position): void
    {
        $this->position = $position;
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
    public function getPublictionDate()
    {
        return $this->publictionDate;
    }

    /**
     * @param mixed $publictionDate
     */
    public function setPublictionDate($publictionDate): void
    {
        $this->publictionDate = $publictionDate;
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

    public function findAll() {
        return parent::findAll();
    }

    public function selectWhere($column, $value) {
        return parent::selectWhere($column, $value);
    }

    public function cleanPublictionDate() {
        $this->setPublictionDate(date("d/m/Y", strtotime($this->getPublictionDate())));
    }

	public function formBuilderRegister() 
	{

        $today = date("Y-m-d");

		return [
			"config"=>[
				"method"=>"POST",
				"action"=>"",
				"class"=>"form_control",
				"id"=>"form_register",
				"submit"=>"Ajout d'une page",
                "required_inputs"=>5
			],
			"fields"=>[
				"title" => [
				    "type"=>"text",
                    "placeholder"=>"Animées",
                    "label"=>"Votre Titre :",
                    "class"=>"search-bar",
                    "minLength"=>2,
                    "maxLength"=>25,
                    "error"=>"Votre titre doit faire entre 2 et 25 caractères",
                    "required" => true
                ],
				"slug"=>[
                    "type"=>"text",
                    "placeholder"=>"meilleure-serie",
                    "label"=>"Votre slug :",
                    "class"=>"search-bar",
                    "minLength"=>2,
                    "maxLength"=>15,
                    "error"=>"Votre slug doit faire entre 2 et 15 caractères",
                    "required" => true
                ],
				"position"=>[
                    "type"=>"text",
                    "placeholder"=>"3",
                    "label"=>"Position :",
                    "class"=>"search-bar",
                    "minLength"=>1,
                    "maxLength"=>1,
                    "error"=>"Votre position doit étre entre 1 et 4",
                    "required"=>true,
                ],
				"titleSEO"=>[
                    "type"=>"text",
                    "placeholder"=>"Titre pour le référencement",
                    "label"=>"titleSEO :",
                    "class"=>"search-bar",
                    "minLength"=>2,
                    "maxLength"=>50,
                    "error"=>"Votre titleSEO doit étre entre 2 et 50"
                ],
				"descriptionSEO"=>[
                    "type"=>"text",
                    "placeholder"=>"META description",
                    "label"=>"META description :",
                    "class"=>"search-bar",
                    "minLength"=>2,
                    "maxLength"=>255,
                    "error"=>"Votre descriptionSEO doit étre entre 2 et 255"
                ],
                "publictionDate"=>[
                    "type"=>"date",
                    "placeholder"=>"publication",
                    "label"=>"Date de publication :",
                    "class"=>"search-bar",
                    "min"=>$today,
                    "max"=>"2030-12-31",
                    "error"=>"Votre date de publication doit être entre".$today." et 31-12-2030",
                    "required" => true

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
                            "value"=>"published",
                            "text"=>"Publier",
                        ]
                    ],
                ],
                "csrfToken" => [
                    "type"=>"hidden",
                    "value"=> FormBuilder::generateCSRFToken(),
                ]
			]
		];
	}
}