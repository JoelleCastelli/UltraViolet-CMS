<?php


namespace App\Models;

use App\Core\Database;

class ProductionMedia extends Database
{
    private ?int $id = null;
    protected int $mediaId;
    protected int $productionId;
    protected ?bool $keyArt = false;

    public function __construct() {
        parent::__construct();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    public function getMediaId(): int
    {
        return $this->mediaId;
    }

    public function setMediaId(int $mediaId): void
    {
        $this->mediaId = $mediaId;
    }

    public function getProductionId(): int
    {
        return $this->productionId;
    }

    public function setProductionId(int $productionId): void
    {
        $this->productionId = $productionId;
    }

    public function getKeyArt(): ?bool
    {
        return $this->keyArt;
    }

    public function setKeyArt(?bool $keyArt): void
    {
        $this->keyArt = $keyArt;
    }

}