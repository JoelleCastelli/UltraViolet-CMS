<?php


namespace App\Models;


use App\Core\Database;

class ArticleHistory extends Database
{
    private ?int $id = null;
    protected int $views;
    protected string $date;
    protected ?int $articleId;

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @param int|null $id
     */
    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return int
     */
    public function getViews(): int
    {
        return $this->views;
    }

    /**
     * @param int $views
     */
    public function setViews(int $views): void
    {
        $this->views = $views;
    }

        /**
     * @return string
     */
    public function getDate(): string
    {
        return $this->date;
    }

    /**
     * @param string $datez
     */
    public function setDate(string $date): void
    {
        $this->date = $date;
    }

        /**
     * @return int
     */
    public function getArticleId(): int
    {
        return $this->articleId;
    }

    /**
     * @param int $views
     */
    public function setArticleId(int $articleId): void
    {
        $this->articleId = $articleId;
    }

}