<?php

namespace App\Controller;

use App\Core\FormValidator;
use App\Core\Helpers;
use App\Models\Category as CategoryModel;
use App\Core\View;
use App\Models\CategoryArticle;

class Category
{
    protected array $columnsTable;
    public function __construct() {
        $this->columnsTable = [
            'name' => 'Nom',
            'position' => 'Position',
            'description' => 'Description',
            'actions' => 'Actions'
        ];
    }

    /**
     * List all the categories
     */
    public function showAllAction()
    {
        $view = new View("categories/list");
        $view->assign('title', 'Catégories');
        $view->assign('columnsTable', $this->columnsTable);
        $view->assign('headScripts', [PATH_TO_SCRIPTS.'headScripts/categories/categories.js']);
    }


    /**
     * Return all the categories
     */
    public function getCategoriesAction()
    {
        if (!empty($_POST['categoryType'])) {

            // get categories
            if ($_POST['categoryType'] === 'visible') {
                $categories = CategoryModel::getVisibleCategories();
            } else if ($_POST['categoryType'] === 'hidden') {
                $categories = CategoryModel::getHiddenCategories();
            }

            if (!$categories) $categories = [];

            $categoryArray = [];

            foreach ($categories as $category) {

                if ($category->getPosition() > 0) {
                    $actions = $category->generateActionsMenu();
                } else {
                    $category->setActions($category->getActionsDeletedCategories());
                    $actions = $category->generateActionsMenu();
                }

                $categoryArray[] = [
                    $this->columnsTable['name'] => $category->getName(),
                    $this->columnsTable['position'] => $category->getPosition(),
                    $this->columnsTable['description'] => $category->getDescriptionSeo(),
                    $this->columnsTable['actions'] => $actions
                ];
            }

            echo json_encode([
                "categories" => $categoryArray,
                "columns" => $this->columnsTable,
            ]);
        }
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

                if(!$this->isSlugUnique(Helpers::slugify($_POST['name'])))
                    $errors = ['Ce nom est déjà existant'];

                if(empty($errors))
                {
                    // Set object values
                    $category->setName(htmlspecialchars($_POST['name']));
                    $category->setPosition(htmlspecialchars($_POST['position']));
                    $category->setDescriptionSeo(htmlspecialchars($_POST['descriptionSeo']));
                    $category->save();
                    Helpers::setFlashMessage('success', "La catégorie " . $_POST["title"] . " a bien été ajoutée à la base de données.");
                    Helpers::namedRedirect('categories_list');
                }
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

                if (!$this->isSlugUnique(Helpers::slugify($_POST['name']), $id))
                    $errors = ['Ce nom est déjà existant'];
                
                if (empty($errors)) {

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
                    Helpers::namedRedirect('categories_list');

                }else {
                    $view->assign("errors", $errors);
                }
            } else {
                $view->assign("errors", $errors);
            }
        }
    }

    /**
     * Delete a category in the database
     */
    public function deleteCategoryAction() {
        if(!empty($_POST['categoryId']) && is_numeric($_POST['categoryId'])) {
            $response = [];
            $category = new CategoryModel();
            $categoryArticle = new CategoryArticle();
            
            if($categoryArticle->hardDelete()->where('categoryId', $_POST['categoryId'])->execute())
            {
                if ($category->hardDelete()->where('id', $_POST['categoryId'])->execute()) {
                    $response['success'] = true;
                    $response['message'] = 'La catégorie a bien été supprimée';
                } else {
                    $response['success'] = false;
                    $response['message'] = 'La catégorie n\'a pas pu être supprimée';
                }
            } else {
                $response['success'] = false;
                $response['message'] = 'La catégorie n\'a pas pu être supprimée';
            }
            echo json_encode($response);
        }
    }

    /*
    * For hidding categories
    */
    public function hideCategoryAction($id){

        $category = new CategoryModel();
        $category->setId($id);

        if (!empty($category)) {
            $category->setPosition(0);
            $category->save();
            Helpers::setFlashMessage('success', "La catégorie a bien été cachée");
            Helpers::namedRedirect('categories_list');
        }
    }

    /*
    * Make Category visible again 
    */
    public function restoreCategoryAction($id)
    {

        $category = new CategoryModel();
        $category->setId($id);

        if (!empty($category)) {
            $category->setPosition(10);
            $category->save();
            Helpers::setFlashMessage('success', "La catégorie est de nouveau visible");
            Helpers::namedRedirect('categories_list');
        }
    }

    private function isSlugUnique($slug, $id = 0): bool
    {
        $category = new CategoryModel;
        $categories = $category->findAll();
        foreach($categories as $category)
        {
            if (Helpers::slugify($category->getName()) === $slug && $category->getId() != $id) {

                return false;
            }
        }
        return true;
    }

    /***************** */
    /* FRONT FUNCTIONS */
    /***************** */

    public function showCategoryArticlesAction($categorySlug)
    {
        $category = new CategoryModel;
        $categories = $category->select()->where('position', 1, '>=')->get();
        foreach ($categories as $category) {

            if (Helpers::slugify($category->getName()) === $categorySlug) {

                $view = new View('articles/list', 'front');
                $view->assign('articles',  $category->getArticlesPublished());
                $view->assign("category", $category->getName());
                return;
            }
        }

        Helpers::redirect404();
    }

}