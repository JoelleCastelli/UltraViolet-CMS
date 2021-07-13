<?php


namespace App\Models;


use App\Core\Database;
use App\Core\FormBuilder;
use App\Core\Helpers;

class Category extends Database
{
    private ?int $id = null;
    protected string $name;
    protected int $position;
    protected string $createdAt;
    private ?string $updatedAt;
    private ?array $actions;

    private ?array $articles;

    public function __construct()
    {
        parent::__construct();
        $this->actions = [
            ['name' => 'Modifier', 'action' => 'modify', 'url' => Helpers::callRoute('category_update', ['id' => $this->id]), 'role' => 'admin'],
            ['name' => 'Supprimer', 'action' => 'delete', 'url' => Helpers::callRoute('category_delete', ['id' => $this->id]), 'role' => 'admin'],
        ];
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

    /**
     * @param array[]|null $actions
     */
    public function getArticlesPublished() 
    {
        $categoryArticle = new CategoryArticle;
        $article = new Article;

        $articlesId = $categoryArticle->select('articleId')->where('categoryId', $this->id)->get(false);

        if(!empty($articlesId))
            $this->articles = $article->select()->whereIn('id', $articlesId)->andWhere('deletedAt', "NULL")->andWhere('publicationDate', date("Y-m-d H:i:s"), "<=")->get();
        else
            $this->articles = [];

        return $this->articles;
    }

    /**
     * Form to add a new category
     */
    public function formBuilderAddCategory(): array
    {
        return [
            "config" => [
                "method" => "POST",
                "action" => "",
                "class" => "form_control card",
                "id" => "formAddCategory",
                "submit" => "Valider",
                "referer" => Helpers::callRoute('category_creation')
            ],
            "fields" => [
                "name" => [
                    "type" => "text",
                    "minLength" => 1,
                    "maxLength" => 60,
                    "label" => "Nom",
                    "class" => "search-bar",
                    "error" => "Le nom de catégorie doit contenir entre 1 et 60 caractères",
                    "required" => true,
                ],
                "position" => [
                    "type" => "number",
                    "min" => 0,
                    "label" => "Position dans le menu",
                    "class" => "search-bar",
                    "error" => "La position doit être supérieure ou égale à 0",
                    "required" => true,
                ],
                "csrfToken" => [
                    "type"=>"hidden",
                    "value"=> FormBuilder::generateCSRFToken(),
                ]
            ],
        ];
    }

    public static function getMenuCategories()
    {
        $category = new Category;
        $categories = $category->select()->where('position', 1, ">")->orderBy('position')->orderBy('name')->get();

        $mainCategories = array_splice($categories, 0, 5);

        return ['main' => $mainCategories, 'other' => $categories];
    }

    /**
     * Form to update a category
     */
    public function formBuilderUpdateCategory($id): array
    {
        $category = new Category();
        $category = $category->findOneBy('id', $id);
        if($category) {
            return [
                "config" => [
                    "method" => "POST",
                    "action" => "",
                    "class" => "form_control card",
                    "id" => "formAddCategory",
                    "submit" => "Valider",
                    "referer" => Helpers::callRoute('category_update', ['id' => $id])
                ],
                "fields" => [
                    "id" => [
                        "type" => "hidden",
                        "value" => $id
                    ],
                    "name" => [
                        "type" => "text",
                        "minLength" => 1,
                        "maxLength" => 60,
                        "label" => "Nom",
                        "class" => "search-bar",
                        "value" => $category->getName(),
                        "error" => "Le nom de catégorie doit contenir entre 1 et 60 caractères",
                        "required" => true,
                    ],
                    "position" => [
                        "type" => "number",
                        "min" => 0,
                        "label" => "Position dans le menu",
                        "class" => "search-bar",
                        "value" => $category->getPosition(),
                        "error" => "La position doit être supérieure ou égale à 0",
                        "required" => true,
                    ],
                    "csrfToken" => [
                        "type"=>"hidden",
                        "value"=> FormBuilder::generateCSRFToken(),
                    ]
                ],
            ];
        }
    }

}