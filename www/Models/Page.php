<?php

namespace App\Models;

use App\Core\Database;
use App\Core\Helpers;
use App\Core\FormBuilder;
use App\Core\Traits\ModelsTrait;

use JsonSerializable;

class Page extends Database implements JsonSerializable
{
    use ModelsTrait;

	private $id = null;
	protected $title;
	protected $slug;
	protected $position;
	protected $state;
	protected $titleSeo;
	protected $descriptionSeo;
	protected $publicationDate;
	protected $createdAt;
	protected $updatedAt;
	protected $deletedAt;

    private $actions;
    private $actionsDeletedPages;

	public function __construct(){
		parent::__construct();
          $this->actions = [
            ['name' => 'Modifier', 'action' => 'modify', 'class' => "update", 'url' => Helpers::callRoute('page_update', ['id' => $this->id])],
            ['name' => 'Supprimer', 'action' => 'delete', 'class' => "delete", 'url' => Helpers::callRoute('page_delete', ['id' => $this->id]), 'role' => 'admin'],
        ];

        $this->actionsDeletedPages = [
            ['name' => 'Supprimer définitivement', 'action' => 'delete', 'class' => 'delete', 'url' => Helpers::callRoute('page_delete', ['id' => $this->id]), 'role' => 'admin'],
            ['name' => 'Restaurer en tant que brouillon', 'action' => 'state-to-draft', 'url' => Helpers::callRoute('page_update_state', ['state' => 'draft', 'id' => $this->id]), 'role' => 'admin'],
            ['name' => 'Restaurer en tant que publiée', 'action' => 'state-to-hidden', 'url' => Helpers::callRoute('page_update_state', ['state' => 'hidden', 'id' => $this->id]), 'role' => 'admin'],
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
     * @param array
     */
    public function setActions($actions): void
    {
        $this->actions = $actions;
    }
    /**
     * @return array
     */
    public function getActionsDeletedPages()
    {
        return $this->actionsDeletedPages;
    }

    /**
    * @return array
    */
    public function getActions()
    {
        return $this->actions;
    }

    public function cleanPublicationDate() {
        $this->setPublicationDate(date("d/m/Y", strtotime($this->getPublicationDate())));
    }

    public function setStateToPublished()
    {
        $this->setState("published");
        $this->setPublicationDate(Helpers::getCurrentTimestamp());
        $this->setDeletedAt(null);
    }

    public function setStateToPublishedHidden()
    {
        $this->setState("hidden");
        $this->setPublicationDate(null);
        $this->setDeletedAt(null);
    }

    public function setStateToScheduled($publicationDate)
    {
        $this->setState("scheduled");
        $this->setPublicationDate($publicationDate);
        $this->setDeletedAt(null);
    }

    public function setStateToDraft()
    {
        $this->setState("draft");
        $this->setDeletedAt(null);
        $this->setPublicationDate(null);
    }

    public function jsonSerialize(): array
    {
        return [
            "id" => $this->getId(),
            "title" => $this->getTitle(),
            "slug" => $this->getSlug(),
            "position" => $this->getPosition(),
            "state" => $this->getState(),
            "titleSeo" => $this->getTitleSeo(),
            "descriptionSeo" => $this->getDescriptionSeo(),
            "publicationDate" => $this->getPublicationDate(),
            "createdAt" => $this->getCreatedAt(),
            "updatedAt" => $this->getUpdatedAt(),
            "deletedAt" => $this->getDeletedAt()
        ];
    }

    public function checkState() {
        if(
            $this->getState() != 'draft' &&
            $this->getState() != 'scheduled' &&
            $this->getState() != 'published' &&
            $this->getState() != 'hidden' &&
            $this->getState() != 'deleted'
          )
            return false;
        else
            return true;
    }

    public function formBuilderRegister() 
	{

        $today = date("Y-m-d\TH:i");
        $todayText = date("Y-m-d H:i");

		return [
			"config"=>[
				"method"=>"POST",
				"action"=>"",
				"class"=>"form_control form-add-page",
				"id"=>"form_register",
				"submit"=>"Ajout d'une page",
                "required_inputs"=>5,
                "referer" => '/admin/pages/creation'

			],
			"fields"=>[
				"title" => [
                    "type" => "text",
                    "placeholder" => "Animées",
                    "label" => "Titre *",
                    "class" => "search-bar",
                    "error" => "Votre titre doit faire entre 1 et 100 caractères",
                    "required" => true,
                    "minLength" => 1,
                    "maxLength" => 100
                ],
				"slug"=>[
                    "type" => "text",
                    "placeholder" => "meilleures-animees",
                    "label" => "Slug",
                    "class" => "search-bar",
                    "error" => "Votre slug doit mal formé et doit faire entre 1 et 100 caractères",
                    "minLength" => 1,
                    "maxLength" => 100,
                    "regex" => "/^[a-z0-9]+(?:-[a-z0-9]+)*$/", // correct slug
                ],
				"position"=>[
                    "type" => "number",
                    "placeholder" => "3",
                    "label" => "Position * ",
                    "class" => "search-bar",
                    "error" => "Le champs position est vide",
                    "min" => 1,
                    "required" => true,
                ],
				"titleSEO"=>[
                    "type"=>"text",
                    "placeholder" => "Titre pour le référencement",
                    "label" => "Titre SEO",
                    "class" => "search-bar",
                ],
				"descriptionSEO"=>[
                    "type"=>"text",
                    "label" => "Description SEO",
                    "placeholder" => "Description de la page",
                    "class"=>"search-bar",
                ],
              
                "state" => [
                    "type" => "radio",
                    "label" => "État *",
                    "class" => "",
                    "required" => true,
                    "error" => "Le champs état est vide",
                    "options" => [
                        [
                            "value" => "draft",
                            "text" => "Brouillon"
                        ],
                        [
                            "value" => "published",
                            "text" => "Publier maintenant"
                        ],
                        [
                            "value" => "scheduled",
                            "text" => "Planifier"
                        ]
                    ],
                ],
                "publicationDate" => [
                    "type" => "datetime-local",
                    "label" => "Date de la planification",
                    "class" => "search-bar",
                    "error" => "Votre date de publication doit être au minimum " . $todayText ,
                    "min" => $today,
                ],
                "csrfToken" => [
                    "type"=>"hidden",
                    "value"=> FormBuilder::generateCSRFToken(),
                ]
			]
		];
	}

    public function formBuilderUpdate()
    {

        $today = date("Y-m-d");

        return [
            "config" => [
                "method" => "POST",
                "action" => "",
                "class" => "form_control form-add-page",
                "id" => "form_update",
                "submit" => "Modifier une page",
                "required_inputs" => 5
            ],
            "fields" => [
                "title" => [
                    "type" => "text",
                    "placeholder" => "Critiques de séries",
                    "label" => "Titre * :",
                    "class" => "search-bar",
                    "error" => "Le titre doit contenir entre 2 et 25 caractères",
                ],
                "slug" => [
                    "type" => "text",
                    "placeholder" => "critiques-de-series",
                    "label" => "Slug :",
                    "class" => "search-bar",
                    "error" => "Le slug doit contenir entre 2 et 15 caractères",
                ],
                "position" => [
                    "type" => "text",
                    "placeholder" => "3",
                    "label" => "Position dans le menu* :",
                    "class" => "search-bar",
                    "error" => "La position doit être comprise entre 1 et 4",
                ],
                "titleSeo" => [
                    "type" => "text",
                    "placeholder" => "Nos critiques des meilleures séries TV",
                    "label" => "Meta-title :",
                    "class" => "search-bar",
                    "error" => "Le meta-title contenir entre 2 et 50 caractères"
                ],
                "descriptionSeo" => [
                    "type" => "text",
                    "placeholder" => "Retrouvez nos dernières critiques sur les meilleures séries du moment !",
                    "label" => "Meta-description :",
                    "class" => "search-bar",
                    "error" => "La meta-description doit contenir entre 2 et 255 caractères"
                ],
                "state" => [
                    "type" => "radio",
                    "label" => "État * :",
                    "class" => "",
                    "error" => "Erreur test",
                    "options" => [
                        [
                            "value" => "draft",
                            "text" => "Brouillon",
                        ],
                        [
                            "value" => "published",
                            "text" => "Publier maintenant",
                        ]
                    ],
                ],
                "publicationDate" => [
                    "type" => "datetime-local",
                    "placeholder" => "publication",
                    "label" => "Ou plus tard : ",
                    "class" => "search-bar",
                    "error" => "Votre date de publication doit être entre " . $today . " et 31-12-2030",

                ],
                "csrfToken" => [
                    "type" => "hidden",
                    "value" => FormBuilder::generateCSRFToken(),
                ]
            ]
        ];
    }
}