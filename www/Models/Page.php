<?php

namespace App\Models;

use App\Core\Database;

class Page extends Database
{

	private $id = null;
	protected $title;
	protected $slug;
	protected $position;
	protected $draft;
	protected $published;
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
    public function getDraft()
    {
        return $this->draft;
    }

    /**
     * @param mixed $draft
     */
    public function setDraft($draft): void
    {
        $this->draft = $draft;
    }

    /**
     * @return mixed
     */
    public function getPublished()
    {
        return $this->published;
    }

    /**
     * @param mixed $published
     */
    public function setPublished($published): void
    {
        $this->published = $published;
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

	public function formBuilderRegister() 
	{

		return [
			"config"=>[
				"method"=>"POST",
				"action"=>"",
				"class"=>"form_control",
				"id"=>"form_register",
				"submit"=>"Ajout d'une page"
			],
			"inputs"=>[
				"title" => [
				    "type"=>"text",
                    "placeholder"=>"Page concernant les animées sous-coté",
                    "label"=>"Votre Titre",
                    "required"=>true,
                    "class"=>"form_input",
                    "minLength"=>2,
                    "maxLength"=>50,
                    "error"=>"Votre titre doit faire entre 2 et 50 caractères"
                ],
				"slug"=>[
                    "type"=>"text",
                    "placeholder"=>"/animées-sous-coté",
                    "label"=>"Votre lien",
                    "required"=>true,
                    "class"=>"form_input",
                    "minLength"=>2,
                    "maxLength"=>100,
                    "error"=>"Votre lien doit faire entre 2 et 50 caractères"
                ],
				"position"=>[
                    "type"=>"text",
                    "placeholder"=>"3",
                    "label"=>"Position",
                    "required"=>true,
                    "class"=>"form_input",
                    "minLength"=>1,
                    "maxLength"=>1,
                    "error"=>"Votre position doit étre entre 1 et 4"
                ],
				"titleSEO"=>[
                    "type"=>"text",
                    "placeholder"=>"Titre pour le référencement",
                    "label"=>"titleSEO",
                    "class"=>"form_input",
                    "minLength"=>2,
                    "maxLength"=>50,
                    "error"=>"Votre position doit étre entre 1 et 4"
                ],
				"descriptionSEO"=>[
                    "type"=>"text",
                    "placeholder"=>"3",
                    "label"=>"META description",
                    "class"=>"form_input",
                    "minLength"=>2,
                    "maxLength"=>255,
                    "error"=>"Votre position doit étre entre 1 et 4"
                ],
				"draft"=>[
                    "type"=>"checkbox",
                    "label"=>"Brouillon",
                    "class"=>"form_input",
                    "value"=>"true",
                    "name"=>"check"
                ]
			]
		];
	}
}