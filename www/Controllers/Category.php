<?php

namespace App\Controller;

use App\Models\Category as CategoryModel;
use App\Core\View;

class Category
{
    protected array $columnsTable;
    public function __construct() {
        $this->columnsTable = [
            'name' => 'Nome',
            'position' => 'Position',
            'actions' => 'Actions'
        ];
    }

    public function showAllAction()
    {
        $categories = new Category();
        //$categories = $categories->selectAll();
        $view = new View("categories/list");
        $view->assign('title', 'Catégories');
    }

}