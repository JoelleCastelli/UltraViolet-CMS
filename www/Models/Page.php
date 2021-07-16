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
	protected $descriptionSeo;
	protected $publicationDate;
    protected $content;
	private $createdAt;
	private $updatedAt;
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
            ['name' => 'Restaurer en tant que brouillon', 'action' => 'update-state', 'class' => 'state-draft', 'url' => Helpers::callRoute('page_update_state'), 'role' => 'admin'],
            ['name' =>'Restaurer en tant que publiée', 'action'=> 'update-state', 'class' => 'state-hidden', 'url' => Helpers::callRoute('page_update_state'), 'role' => 'admin'],
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
        $this->setPublicationDate(date("Y-m-d\TH:i", strtotime($this->getPublicationDate())));
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

    public static function getStaticPages()
    {
        $page = new Page;
        $pages = $page->select()->where('deletedAt', 'NULL')->andWhere('state', 'published')->orderBy('position')->orderBy('title')->get();
        return $pages;
    }


    public function jsonSerialize(): array
    {
        return [
            "id" => $this->getId(),
            "title" => $this->getTitle(),
            "slug" => $this->getSlug(),
            "position" => $this->getPosition(),
            "state" => $this->getState(),
            "descriptionSeo" => $this->getDescriptionSeo(),
            "publicationDate" => $this->getPublicationDate(),
            "content" => $this->getContent(),
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
                "referer" => Helpers::callRoute('page_creation')
			],
			"fields"=>[
				"title" => [
                    "type" => "text",
                    "placeholder" => "Nous contacter",
                    "label" => "Titre *",
                    "class" => "search-bar",
                    "error" => "Votre titre doit faire entre 1 et 100 caractères",
                    "required" => true,
                    "minLength" => 1,
                    "maxLength" => 100
                ],
				"slug"=>[
                    "type" => "text",
                    "placeholder" => "nous-contacter",
                    "label" => "Slug",
                    "class" => "search-bar",
                    "error" => "Votre slug est incorrect et doit faire entre 1 et 100 caractères",
                    "minLength" => 1,
                    "maxLength" => 100,
                    "regex" => "/^[a-z0-9]+(?:-[a-z0-9]+)*$/", // correct slug
                ],
				"position"=>[
                    "type" => "number",
                    "placeholder" => "3",
                    "label" => "Position * ",
                    "class" => "search-bar",
                    "error" => "Le champs position est vide et doit être supérieur à 0",
                    "min" => 1,
                    "max" => 127,
                    "required" => true,
                ],
				"descriptionSeo"=>[
                    "type"=>"textarea",
                    "label" => "Description SEO",
                    "maxLength" => 160,
                    "error" => "La description ne peut pas contenir plus de 160 caractères",
                    "placeholder" => "Description de la page vue par les moteurs de recherche",
                    "class"=>"search-bar",
                ],
                "content" => [
                    "id" => "articleContent",
                    "type" => "textarea",
                    "class" => "input",
                ],
                "state" => [
                    "type" => "radio",
                    "label" => "État *",
                    "class" => "state",
                    "required" => true,
                    "error" => "Le champs Etat est vide",
                    "options" => [
                        [
                            "value" => "draft",
                            "class" => "stateDraft",
                            "text" => "Brouillon"
                        ],
                        [
                            "value" => "published",
                            "class" => "statePublished",
                            "text" => "Publier maintenant"
                        ],
                        [
                            "value" => "scheduled",
                            "class" => "stateScheduled",
                            "text" => "Planifier"
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
                "csrfToken" => [
                    "type"=>"hidden",
                    "value"=> FormBuilder::generateCSRFToken(),
                ]
			]
		];
	}

    public function formBuilderUpdate($id): array
    {
        $today = date("Y-m-d\TH:i");
        $todayText = date("Y-m-d H:i");

        return [
            "config" => [
                "method" => "POST",
                "action" => "",
                "class" => "form_control",
                "id" => "",
                "submit" => "Modifier la page",
                "referer" => Helpers::callRoute('page_update', ['id' => $id])
            ],

            "fields" => [
                "title" => [
                    "type" => "text",
                    "placeholder" => "Nous contacter",
                    "label" => "Titre *",
                    "class" => "search-bar",
                    "error" => "Votre titre doit faire entre 1 et 100 caractères",
                    "required" => true,
                    "minLength" => 1,
                    "maxLength" => 100
                ],
                "slug" => [
                    "type" => "text",
                    "placeholder" => "nous-contacter",
                    "label" => "Slug",
                    "class" => "search-bar",
                    "error" => "Votre slug est incorrect et doit faire entre 1 et 100 caractères",
                    "minLength" => 1,
                    "maxLength" => 100,
                    "regex" => "/^[a-z0-9]+(?:-[a-z0-9]+)*$/", // correct slug
                ],
                "position" => [
                    "type" => "number",
                    "placeholder" => "3",
                    "label" => "Position * ",
                    "class" => "search-bar",
                    "error" => "Le champs position est vide et doit être supérieur à 0",
                    "min" => 1,
                    "max" => 127,
                    "required" => true,
                ],
                "descriptionSeo" => [
                    "type" => "text",
                    "label" => "Description SEO",
                    "placeholder" => "Description de la page vue par les moteurs de recherche",
                    "maxLength" => 160,
                    "error" => "La description ne peut pas contenir plus de 160 caractères",
                    "class" => "search-bar",
                ],
                "content" => [
                    "id" => "articleContent",
                    "type" => "textarea",
                    "class" => "input",
                ],
                "state" => [
                    "type" => "radio",
                    "label" => "État *",
                    "class" => "",
                    "required" => true,
                    "error" => "Le champs état est vide",
                    "options" => [
                        [
                            "id" => "draft",
                            "value" => "draft",
                            "class" => "stateDraft",
                            "text" => "Brouillon"
                        ],
                        [
                            "id" => "published",
                            "value" => "published",
                            "class" => "statePublished",
                            "text" => "Publier maintenant"
                        ],
                        [
                            "id" => "scheduled",
                            "value" => "scheduled",
                            "class" => "stateScheduled",
                            "text" => "Planifier"
                        ],
                        [
                            "id" => "hidden",
                            "value" => "hidden",
                            "class" => "statePublishedHidden",
                            "text" => "Publier mais cacher"
                        ]
                    ],
                ],
                "publicationDate" => [
                    "type" => "datetime-local",
                    "label" => "Date de la planification",
                    "class" => "search-bar publicationDateInput",
                    "error" => "Votre date de publication doit être au minimum " . $todayText,
                    "min" => $today,
                ],
                "csrfToken" => [
                    "type" => "hidden",
                    "value" => FormBuilder::generateCSRFToken(),
                ]
            ]
        ];
    }
}