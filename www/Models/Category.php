<?php


namespace App\Models;


use App\Core\Database;
use App\Core\Helpers;

class Category extends Database
{
    private ?int $id = null;
    protected string $name;
    protected int $position;
    protected string $createdAt;
    private ?string $updatedAt;
    private ?array $actions;

    public function __construct()
    {
        parent::__construct();
        $this->actions = [
            ['name' => 'Modifier', 'action' => 'modify', 'url' => Helpers::callRoute('production_update', ['id' => $this->id]), 'role' => 'admin'],
            ['name' => 'Supprimer', 'action' => 'delete', 'url' => Helpers::callRoute('production_delete', ['id' => $this->id]), 'role' => 'admin'],
        ];
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return int
     */
    public function getPosition(): int
    {
        return $this->position;
    }

    /**
     * @param int $position
     */
    public function setPosition(int $position): void
    {
        $this->position = $position;
    }

    /**
     * @return string
     */
    public function getCreatedAt(): string
    {
        return $this->createdAt;
    }

    /**
     * @return string|null
     */
    public function getUpdatedAt(): ?string
    {
        return $this->updatedAt;
    }

    /**
     * @return array[]|null
     */
    public function getActions(): ?array
    {
        return $this->actions;
    }

    /**
     * @param array[]|null $actions
     */
    public function setActions(?array $actions): void
    {
        $this->actions = $actions;
    }

}