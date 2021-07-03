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

    public function deleteCategoryAction() {
        if(!empty($_POST['categoryId'])) {
            $response = [];
            $category = new CategoryModel();
            if($category->hardDelete()->where('id', $_POST['categoryId'])->execute()) {
                $response['success'] = true;
                $response['message'] = 'La catégorie a bien été supprimée';
            } else {
                $response['success'] = false;
                $response['message'] = 'La catégorie n\'a pas pu être supprimée';
            }
            echo json_encode($response);
        }
    }

}