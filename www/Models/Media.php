<?php

namespace App\Models;

use App\Core\Database;
use App\Core\Traits\ModelsTrait;

class Media extends Database
{
    use ModelsTrait;

    private ?int $id = null;
    protected string $title;
    protected string $path;
    private ?string $tmdbPosterPath = null;
    protected bool $video = false;
    private string $createdAt;
    private ?string $updatedAt;
    protected ?string $deletedAt = null;

    public function __construct()
    {
        parent::__construct();
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

    public function getPath(): string
    {
        return $this->path;
    }

    public function setPath($path): void
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
                "action" => "medias/chargement",
                "referer" => 'admin/medias',
                "enctype" => "multipart/form-data"
            ],
            "fields" => [

                "media[]" => [
                    "type" => "file",
                    "classLabel" => "btn",
                    "class" => "hiddenInputFile",
                    "label" => "Cliquez pour ajouter un document",
                    "required" => true,
                    "multiple" => true,
                ],

              /*  "test" => [
                    "type" => "hidden",
                    "value" => "here",
                    "required" => true,
                ],*/
            ]
        ];
    }
}