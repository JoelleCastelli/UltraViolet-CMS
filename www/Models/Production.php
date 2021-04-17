<?php

namespace App\Models;

use App\Core\Database;

class Production extends Database
{
    private $id = null;
    protected $tmdbId;
    protected $title;
    protected $originalTitle;
    protected $releaseDate;
    protected $type;
    protected $overview;
    protected $runtime;
    protected $number;
    protected $createdAt;
    protected $updatedAt;
    protected $deletedAt;

    public function __construct()
    {
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
     * @param null $id
     */
    public function setId($id): void
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getTmdbId()
    {
        return $this->tmdbId;
    }

    /**
     * @param mixed $tmdbId
     */
    public function setTmdbId($tmdbId): void
    {
        $this->tmdbId = $tmdbId;
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
    public function getOriginalTitle()
    {
        return $this->originalTitle;
    }

    /**
     * @param mixed $originalTitle
     */
    public function setOriginalTitle($originalTitle): void
    {
        $this->originalTitle = $originalTitle;
    }

    /**
     * @return mixed
     */
    public function getReleaseDate()
    {
        return $this->releaseDate;
    }

    /**
     * @param mixed $releaseDate
     */
    public function setReleaseDate($releaseDate): void
    {
        $this->releaseDate = $releaseDate;
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param mixed $type
     */
    public function setType($type): void
    {
        $this->type = $type;
    }

    /**
     * @return mixed
     */
    public function getOverview()
    {
        return $this->overview;
    }

    /**
     * @param mixed $overview
     */
    public function setOverview($overview): void
    {
        $this->overview = $overview;
    }

    /**
     * @return mixed
     */
    public function getRuntime()
    {
        return $this->runtime;
    }

    /**
     * @param mixed $runtime
     */
    public function setRuntime($runtime): void
    {
        $this->runtime = $runtime;
    }

    /**
     * @return mixed
     */
    public function getNumber()
    {
        return $this->number;
    }

    /**
     * @param mixed $number
     */
    public function setNumber($number): void
    {
        $this->number = $number;
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

    public function cleanReleaseDate() {
        $this->setReleaseDate(date("d/m/Y", strtotime($this->getReleaseDate())));
    }

    public function translateType() {
        switch ($this->getType()) {
            case 'movie':
                $this->setType('Film');
                break;
            case 'season':
                $this->setType('Saison');
                break;
            case 'series':
                $this->setType('Série');
                break;
            case 'episode':
                $this->setType('Episode');
                break;
        }
    }

    public function cleanRuntime() {
        if ($this->getRuntime() >= 60) {
            $hours = floor($this->getRuntime() / 60);
            $minutes = floor($this->getRuntime() % 60);
            $this->setRuntime($hours."h");
            if($minutes) {
                if($minutes < 10) {
                    $minutes = "0".$minutes;
                }
                $this->setRuntime($this->getRuntime().$minutes);
            }
        } else {
            $this->setRuntime($this->getRuntime()." minutes");
        }
    }

    public function formBuilderAddProduction(){
        return [
            "config" => [
                "method" => "POST",
                "action" => "",
                "class" => "form_control",
                "id" => "formAddProduction",
                "submit" => "Valider"
            ],
            "inputs" => [
                "type" => [
                    "type" => "select",
                    "label" => "Type",
                    "required" => true,
                    "class" => "form_input",
                    "error" => "Un type de production est nécessaire"
                ],
                "title" => [
                    "type" => "text",
                    "placeholder" => "",
                    "label" => "Titre",
                    "required" => true,
                    "class" => "form_input",
                    "minLength" => 1,
                    "maxLength" => 100,
                    "error" => "Le titre doit faire entre 1 et 100 caractères"
                ],
                "originalTitle" => [
                    "type" => "text",
                    "placeholder" => "",
                    "label" => "Titre original",
                    "class" => "form_input",
                    "maxLength" => 100,
                    "error" => "Le titre doit faire entre 1 et 100 caractères"
                ],
                "releaseDate" => [
                    "type" => "date",
                    "label" => "Date de sortie",
                    "class" => "form_input",
                    "minLength" => 10,
                    "maxLength" => 10,
                    "error" => "Le format de la date est incorrect"
                ],
                "overview" => [
                    "type" => "text",
                    "placeholder" => "",
                    "label" => "Résumé",
                    "class" => "form_input",
                    "maxLength" => 1000,
                    "error" => "Le résumé ne peut pas dépasser 1000 caractères"
                ],
                "runtime" => [
                    "type" => "number",
                    "placeholder" => "",
                    "label" => "Durée (du film ou d'un épisode)",
                    "class" => "form_input",
                    "error" => "Le résumé ne peut pas dépasser 1000 caractères"
                ],
                "number" => [
                    "type" => "number",
                    "placeholder" => "",
                    "label" => "Numéro",
                    "class" => "form_input",
                ],
            ]

        ];
    }

    public function formBuilderAddProductionTmdb(){
        return [
            "config" => [
                "method" => "POST",
                "action" => "tmdb-request",
                "class" => "form_control",
                "id" => "formAddProductionTmdb",
                "submit" => "Valider"
            ],
            "inputs" => [
                "type" => [
                    "type" => "text",
                    "label" => "Type",
                    "class" => "form_input",
                    "error" => "Le type doit être film, série, saison ou épisode"
                ],
                "productionID" => [
                    "type" => "text",
                    "label" => "ID du film ou de la série",
                    "class" => "form_input",
                    "error" => "Un ID est nécessaire"
                ],
                "seasonNb" => [
                    "type" => "text",
                    "label" => "Numéro de la saison",
                    "class" => "form_input",
                    "error" => "Un type de production est nécessaire"
                ],
                "episodeNb" => [
                    "type" => "text",
                    "label" => "Numéro de l'épisode",
                    "class" => "form_input",
                    "error" => "Un type de production est nécessaire"
                ],
                "productionPreviewRequest" => [
                    "type" => "button",
                    "label" => "Preview",
                    "class" => "form_input"
                ],
            ]
        ];

    }

}