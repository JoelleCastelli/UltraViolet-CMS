<?php

namespace App\Models;

use App\Core\Database;
use App\Core\FormBuilder;
use App\Core\Helpers;
use App\Core\Traits\ModelsTrait;

class Media extends Database
{
    use ModelsTrait;

    private ?int $id = null;
    protected ?string $title;
    // protected ?string $title = null;
    protected ?string $path = null;
    private ?string $tmdbPosterPath = null;
    protected bool $video = false;
    private string $createdAt;
    private ?string $updatedAt;
    protected ?string $deletedAt = null;
    private ?array $actions;

    public function __construct()
    {
        parent::__construct();
        $this->actions = [
            ['name' => 'Supprimer', 'action' => 'delete', 'url' => Helpers::callRoute(''), 'role' => 'admin'],
        ];
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle($title): void
    {
        $this->title = $title;
    }

    public function getPath(): ?string
    {
        return $this->path;
    }

    public function setPath(?string $path): void
    {
        $this->path = $path;
    }

    public function getTmdbPosterPath(): ?string
    {
        return $this->tmdbPosterPath;
    }

    public function setTmdbPosterPath(?string $tmdbPosterPath): void
    {
        $this->tmdbPosterPath = $tmdbPosterPath;
    }

    public function getVideo(): int
    {
        return $this->video;
    }

    public function setVideo(int $video): void
    {
        $this->video = $video;
    }

    public function getActions(): ?array
    {
        return $this->actions;
    }

    public function setActions(?array $actions): void
    {
        $this->actions = $actions;
    }

    public function getCreatedAt(): string
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): ?string
    {
        return $this->updatedAt;
    }

    public function getDeletedAt(): ?string
    {
        return $this->deletedAt;
    }

    public function setDeletedAt($deletedAt): void
    {
        $this->deletedAt = $deletedAt;
    }

    public function formBuilderUpload(): array
    {
        return [
            "config" => [
                "method" => "POST",
                "action" => '',
                "referer" => Helpers::callRoute('media_list'),
                "enctype" => "multipart/form-data"
            ],
            "fields" => [
                "media[]" => [
                    "id" => "mediaSelector",
                    "type" => "file",
                    "classLabel" => "btn",
                    "class" => "hiddenInputFile",
                    "accept" => ".jpg, .jpeg, .png",
                    "label" => "Ajouter un fichier",
                    "required" => true,
                    "multiple" => true,
                ],
                "csrfToken" => [
                    "type"=>"hidden",
                    "value"=> FormBuilder::generateCSRFToken(),
                ]
            ]
        ];
    }

    public function getCleanCreatedAtDate() {
        if ($this->getCreatedAt() != '') {
            return date("d/m/Y", strtotime($this->getCreatedAt()));
        } else {
            return "-";
        }
    }

    public function getMediaByTitle($title) {
        $media = $this->select("id")->where("title", $title)->first(0);
        return !empty($media) ? $media : -1;
    }
}