<?php

namespace App\Controller;

use App\Core\FormValidator;
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

    /**
     * List all the categories
     */
    public function showAllAction()
    {
        $categories = new CategoryModel();
        $categories = $categories->select()->orderBy('position')->get();
        $view = new View("categories/list");
        $view->assign('title', 'Catégories');
        $view->assign('columnsTable', $this->columnsTable);
        $view->assign('categories', $categories);
        $view->assign('headScripts', [PATH_TO_SCRIPTS.'headScripts/categories/categories.js']);
    }

    /**
     * Add a category in the database
     */
    public function addCategoryAction() {
        // Generate form
        $category = new CategoryModel();
        $form = $category->formBuilderAddCategory();
        $view = new View("categories/add-category");
        $view->assign('title', 'Nouvelle catégorie');
        $view->assign("form", $form);

        // If form is submitted, check the data and save category
        if(!empty($_POST)) {
            $errors = FormValidator::check($form, $_POST);
            if(empty($errors)) {
                // Set object values
                $category->setName(htmlspecialchars($_POST['name']));
                $category->setPosition(htmlspecialchars($_POST['position']));
                $category->save();
                Helpers::setFlashMessage('success', "La catégorie ".$_POST["title"]." a bien été ajoutée à la base de données.");
                Helpers::redirect(Helpers::callRoute('categories_list'));
            }
            $view->assign("errors", $errors);
        }
    }

    /**
     * Update a category
     */
    public function updateCategoryAction($id) {
        $category = new CategoryModel();
        $form = $category->formBuilderUpdateCategory($id);
        $view = new View("categories/update");
        $view->assign('title', 'Modifier une catégorie');
        $view->assign("form", $form);

        // If form is submitted, check the data and save the category
        if(!empty($_POST)) {
            $errors = FormValidator::check($form, $_POST);
            if(empty($errors)) {
                // Dynamic setters
                foreach ($_POST as $key => $value) {
                    if ($key !== 'csrfToken' && $value !== '') {
                        if(!empty($value)) {
                            $functionName = "set".ucfirst($key);
                            $category->$functionName(htmlspecialchars($value));
                        }
                    }
                }
                $category->save();
                Helpers::setFlashMessage('success', "La catégorie a bien été mise à jour");
                Helpers::redirect(Helpers::callRoute('categories_list'));
            } else {
                $view->assign("errors", $errors);
            }
        }
    }

    /**
     * Delete a category in the database
     */
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