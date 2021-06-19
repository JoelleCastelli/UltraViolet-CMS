<?php

namespace App\Models;

use App\Core\Database;
use App\Core\Helpers;
use App\Core\FormBuilder;
use App\Core\Traits\ModelsTrait;

use JsonSerializable;

class Page_Article extends Database implements JsonSerializable
{

    use ModelsTrait;

    private $id = null;
    protected int $articleId;
    protected int $pageId;

    // Foreign properties
    public Page $page;
    public Article $article;

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
    public function getPageId()
    {
        return $this->pageId;
    }

    /**
     * @param mixed $pageId
     */
    public function setPageId($pageId): void
    {
        $this->pageId = $pageId;
    }

    /**
     * @return mixed
     */
    public function getArticleId()
    {
        return $this->articleId;
    }

    /**
     * @param mixed $deletedAt
     */
    public function setArticleId($articleId): void
    {
        $this->articleId = $articleId;
    }

    public function jsonSerialize(): array
    {
        return [
            "page" => $this->getId(),
            "article" => $this->getTitle(),
        ];
    }
}
 