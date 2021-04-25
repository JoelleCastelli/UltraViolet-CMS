<?php

namespace App\Models;

use App\Core\Database;

class Article extends Database
{
    private $id = null;
    protected $title;
    protected $description;
    protected $content;
    protected $rating;
    protected $slug;
    protected $state;
    protected $totalViews;
    protected $titleSeo;
    protected $descriptionSeo;
    protected $contentUpdatedAt;

    public function __construct() {
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
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param mixed $description
     */
    public function setDescription($description): void
    {
        $this->description = $description;
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
    public function getRating()
    {
        return $this->rating;
    }

    /**
     * @param mixed $rating
     */
    public function setRating($rating): void
    {
        $this->rating = $rating;
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
    public function getTotalViews()
    {
        return $this->totalViews;
    }

    /**
     * @param mixed $totalViews
     */
    public function setTotalViews($totalViews): void
    {
        $this->totalViews = $totalViews;
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
    public function getContentUpdatedAt()
    {
        return $this->contentUpdatedAt;
    }

    /**
     * @param mixed $contentUpdatedAt
     */
    public function setContentUpdatedAt($contentUpdatedAt): void
    {
        $this->contentUpdatedAt = $contentUpdatedAt;
    }

    public function findAll() {
        return parent::findAll();
    }

    public function selectWhere($column, $value) {
        return parent::selectWhere($column, $value);
    }

}