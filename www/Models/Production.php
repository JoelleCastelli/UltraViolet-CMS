<?php

namespace App\Models;

use App\Core\Database;
use App\Core\FormBuilder;
use App\Core\Helpers;

class Production extends Database
{
    private ?int $id = null;
    protected ?int $tmdbId = null;
    protected string $title;
    protected ?string $originalTitle;
    protected ?string $releaseDate;
    protected string $type;
    protected ?string $overview;
    protected ?int $runtime;
    protected ?int $number;
    protected ?string $deletedAt = null;
    private string $createdAt;
    private ?string $updatedAt;
    private ?array $actions = [];

    public function __construct()
    {
        parent::__construct();
        $this->actions = [
            ['name' => 'Modifier', 'action' => 'modify', 'url' => Helpers::callRoute('production_update', ['id' => $this->id])],
            ['name' => 'Supprimer', 'action' => 'delete', 'url' => Helpers::callRoute('production_delete', ['id' => $this->id]), 'role' => 'admin'],
        ];
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId($id): void
    {
        $this->id = $id;
    }

    public function getTmdbId(): ?int
    {
        return $this->tmdbId;
    }

    public function setTmdbId($tmdbId): void
    {
        $this->tmdbId = $tmdbId;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle($title): void
    {
        $this->title = $title;
    }

    public function getOriginalTitle(): ?string
    {
        return $this->originalTitle;
    }

    public function setOriginalTitle($originalTitle): void
    {
        $this->originalTitle = $originalTitle;
    }

    public function getReleaseDate()
    {
        return $this->releaseDate;
    }

    public function setReleaseDate($releaseDate): void
    {
        $this->releaseDate = $releaseDate;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function setType($type): void
    {
        $this->type = $type;
    }

    public function getOverview(): ?string
    {
        return $this->overview;
    }

    public function setOverview($overview): void
    {
        $this->overview = $overview;
    }

    public function getRuntime(): ?int
    {
        return $this->runtime;
    }

    public function setRuntime($runtime): void
    {
        $this->runtime = $runtime;
    }

    public function getNumber(): ?int
    {
        return $this->number;
    }

    public function setNumber($number): void
    {
        $this->number = $number;
    }

    public function getCreatedAt(): string
    {
        return $this->createdAt;
    }

    public function getDeletedAt(): ?string
    {
        return $this->deletedAt;
    }

    public function setDeletedAt($deletedAt): void
    {
        $this->deletedAt = $deletedAt;
    }

    public function getActions(): ?array {
        return $this->actions;
    }

    public function setActions(?array $actions): void {
        $this->actions = $actions;
    }

    public function cleanReleaseDate() {
        $this->setReleaseDate(date("d/m/Y", strtotime($this->getReleaseDate())));
    }

    public function getCleanReleaseDate() {
        if ($this->getReleaseDate() != '') {
            return date("d/m/Y", strtotime($this->getReleaseDate()));
        } else {
            return "-";
        }
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

    public function getCleanRuntime() {
        if($this->getRuntime() != '') {
            return $this->getRuntime()." minutes";
        } else {
            return "-";
        }
    }

    public function getCleanCreatedAt(): string
    {
        return date("d/m/Y",strtotime($this->getCreatedAt()));
    }

    public function formBuilderAddProduction(){
        return [
            "config" => [
                "method" => "POST",
                "action" => "",
                "class" => "form_control",
                "id" => "formAddProduction",
                "submit" => "Valider",
                "referer" => '/admin/productions/creation'
            ],
            "fields" => [
                "type" => [
                    "type" => "select",
                    "label" => "Type",
                    "required" => true,
                    "class" => "form_input",
                    "error" => "Un type de production est nécessaire",
                    "options" => [
                        [
                            "value"=>"movie",
                            "text"=>"Film",
                        ],
                        [
                            "value"=>"series",
                            "text"=>"Série",
                        ]
                    ],
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
                "csrfToken" => [
                    "type"=>"hidden",
                    "value"=> FormBuilder::generateCSRFToken(),
                ]
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
            "fields" => [
                "productionType" => [
                    "type" => "radio",
                    "options" => [
                        [
                            "value"=>"movie",
                            "text"=>"Film",
                            "checked"=>true
                        ],
                        [
                            "value"=>"series",
                            "text"=>"Série"
                        ]
                    ],
                    "label" => "Type",
                    "class" => "form_select",
                    "error" => "Le type doit être film, série, saison ou épisode"
                ],
                "productionID" => [
                    "type" => "number",
                    "label" => "ID du film ou de la série",
                    "class" => "form_input",
                    "error" => "Un ID est nécessaire"
                ],
                "seasonNb" => [
                    "type" => "number",
                    "label" => "Numéro de la saison",
                    "class" => "form_input",
                    "error" => "Un type de production est nécessaire",
                ],
                "episodeNb" => [
                    "type" => "number",
                    "label" => "Numéro de l'épisode",
                    "class" => "form_input",
                    "error" => "Un type de production est nécessaire"
                ],
                "productionPreviewRequest" => [
                    "type" => "button",
                    "label" => "Preview",
                    "class" => "form_input",
                    "value" => "Preview"
                ],
                "csrfToken" => [
                    "type"=>"hidden",
                    "value"=> FormBuilder::generateCSRFToken(),
                ]
            ],
        ];

    }

}