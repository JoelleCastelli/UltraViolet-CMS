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

}