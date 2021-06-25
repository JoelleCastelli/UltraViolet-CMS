<?php

namespace App\Models;

use App\Core\Database;

class ProductionArticle extends Database
{
    private ?int $id = null;
    protected int $articleId;
    protected int $productionId;

    public function __construct() {
        parent::__construct();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getArticleId(): int
    {
        return $this->articleId;
    }

    public function setArticleId(int $articleId): void
    {
        $this->articleId = $articleId;
    }

    public function getProductionId(): int
    {
        return $this->productionId;
    }

    public function setProductionId(int $productionId): void
    {
        $this->productionId = $productionId;
    }

}