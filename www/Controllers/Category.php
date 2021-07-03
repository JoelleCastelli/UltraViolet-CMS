<?php

namespace App\Controller;

use App\Core\Helpers;
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
        $categories = new CategoryModel();
        $categories = $categories->findAll();
        $view = new View("categories/list");
        $view->assign('title', 'Catégories');
        $view->assign('columnsTable', $this->columnsTable);
        $view->assign('categories', $categories);
        $view->assign('headScripts', [PATH_TO_SCRIPTS.'headScripts/categories/categories.js']);
    }

}