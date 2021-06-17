<?php

namespace App\Models;

use App\Core\Database;


class ProductionPerson extends Database
{
    private ?int $id = null;
    protected int $personId;
    protected int $productionId;
    protected ?string $department;
    protected ?string $character;

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

    public function getPersonId(): int
    {
        return $this->personId;
    }

    public function setPersonId(int $personId): void
    {
        $this->personId = $personId;
    }

    public function getProductionId(): int
    {
        return $this->productionId;
    }

    public function setProductionId(int $productionId): void
    {
        $this->productionId = $productionId;
    }

    public function getDepartment(): ?string
    {
        return $this->department;
    }

    public function setDepartment(?string $department): void
    {
        $this->department = $department;
    }

    public function getCharacter(): ?string
    {
        return $this->character;
    }

    public function setCharacter(?string $character): void
    {
        $this->character = $character;
    }

}