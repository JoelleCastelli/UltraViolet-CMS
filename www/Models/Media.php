<?php

namespace App\Models;

use App\Core\Database;
use App\Core\Traits\ModelsTrait;

class Media extends Database
{
    use ModelsTrait;

    private $id = null;
    private $createdAt;
    private $updatedAt;
    protected $title;
    protected $path;
    protected $video = 0;
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
    public function getPath()
    {
        return $this->path;
    }

    /**
     * @param mixed $path
     */
    public function setPath($path): void
    {
        $this->path = $path;
    }

    /**
     * @return int
     */
    public function getVideo(): int
    {
        return $this->video;
    }

    /**
     * @param int $video
     */
    public function setVideo(int $video): void
    {
        $this->video = $video;
    }

    /**
     * @return mixed
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
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

    public function formBuilderUpload()
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