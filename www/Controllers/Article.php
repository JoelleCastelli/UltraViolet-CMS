<?php

namespace App\Controller;


use App\Core\View;
use App\Core\Helpers;
use App\Core\FormValidator;
use App\Core\Request;
use App\Models\Article as ArticleModel;
use App\Models\Media as MediaModel;
use App\Models\Category as CategoryModel;
use App\Models\CategoryArticle as CategoryArticleModel;

class Article {

    /*
    $present = $categoryArticle->select()
    ->where("articleId", $articleId, "=")
    ->andWhere("categoryId", $categoryId, "=");
    */

    public function showAllAction() {        
        $view = new View("articles/list");
        $view->assign('title', 'Articles');
        $view->assign('headScripts', [PATH_TO_SCRIPTS.'headScripts/articles/articles.js']);
    }

    public function createArticleAction() {

        $article = new ArticleModel();
        $media = new MediaModel();
        $category = new CategoryModel();

        $data = [
            "media" => $media->findAll(),
            "categories" => $category->findAll()
        ];

        $form = $article->formBuilderCreateArticle($data);
        $view = new View("articles/createArticle");
    
        if (!empty($_POST)) {

            $errors = FormValidator::check($form, $_POST);
            if (empty($errors)) {

                $title = htmlspecialchars($_POST["title"]);
                $user = Request::getUser();

                $article->setTitle($title);
                $article->setSlug(Helpers::slugify($title));
                $article->setDescription(htmlspecialchars($_POST["description"]));
                $article->setContent($_POST["content"]);
                $article->setMediaId(htmlspecialchars($_POST["media"]));
                $article->setPersonId($user->getId());
                if (!empty($_POST["publicationDate"])) {
                    $article->setPublicationDate(htmlspecialchars($_POST["publicationDate"]));
                }
                $article->save();

                $articleId = $article->getLastInsertId();
                foreach ($_POST["categories"] as $categoryId) {
                    $categoryArticle = new CategoryArticleModel();
                    $categoryArticle->setArticleId($articleId);
                    $categoryArticle->setCategoryId(htmlspecialchars($categoryId));
                    $categoryArticle->save();
                }  
                Helpers::namedRedirect("articles_list");
            }
            else
                $view->assign("errors", $errors);
        }
        $categories = $category->select('id')->get(null);
        $data['categories'] = [];
        foreach ($categories as $categorie => $value) {
            array_push($data['categories'], $value['id']);
        }

        $view->assign("data", $data);
        $view->assign("title", "Créer un article");
        $view->assign("form", $form);
        $view->assign('bodyScripts', [PATH_TO_SCRIPTS.'bodyScripts/tinymce.js']);
    }

    public function updateArticleAction($id) {
        $article = new ArticleModel();
        $articleExist = $article->setId($id);

        if (!$articleExist) Helpers::redirect404();

        $view = new View("articles/updateArticle");
        $form = $article->formBuilderUpdateArticle($id);


        if (!empty($_POST)) {

            $errors = FormValidator::check($form, $_POST);
            if (empty($errors)) {

                $title = htmlspecialchars($_POST["title"]);
                $user = Request::getUser();
                

                $article->setTitle($title);
                $article->setSlug(Helpers::slugify($title));
                $article->setDescription(htmlspecialchars($_POST["description"]));
                $article->setContent($_POST["content"]);
                $article->setMediaId(htmlspecialchars($_POST["media"]));
                $article->setPersonId($user->getId());
                if (!empty($_POST["publicationDate"])) {
                    $article->setPublicationDate(htmlspecialchars($_POST["publicationDate"]));
                }

                Helpers::dd($_POST["categories"]);

                // $articleId = $article->getLastInsertId();
                // foreach ($_POST["categories"] as $categoryId) {
                //     $categoryArticle = new CategoryArticleModel();
                //     $categoryArticle->setArticleId($articleId);
                //     $categoryArticle->setCategoryId(htmlspecialchars($categoryId));
                //     $categoryArticle->save();
                // }
                
                $article->save();
                Helpers::namedRedirect("articles_list");
            }
            else
                $view->assign("errors", $errors);
        }

        $view->assign('form', $form);
        $view->assign("data", $article->jsonSerialize());
        $view->assign("title", "Modifier un article");
        $view->assign('bodyScripts', [PATH_TO_SCRIPTS.'bodyScripts/tinymce.js']);
    }



    // API methods

    // TODO : Need to secure this
    public function getArticlesAction() {

        if (empty($_POST["state"])) return;
        $state = $_POST["state"];

        $article = new ArticleModel();
        $articles = $article->getArticlesBySate($state);

        $articlesArray = [];
        foreach ($articles as $article) {
            $articlesArray[] = [
                "Titre" => $article->getTitle(),
                "Auteur" => $article->getPerson()->getPseudo(),
                "Vues" => $article->getTotalViews(),
                "Commentaire" => "[NOMBRE COMMENTAIRE]",
                "Date creation" => $article->getCreatedAt(),
                "Date publication" => $article->getPublicationDate(),
                "Actions" => $article->generateActionsMenu()
            ];
        }

        echo json_encode([
            "articles" => $articlesArray
        ]);
    }

    public function deleteArticleAction() {

        if (empty($_POST["id"])) return;

        $article = new ArticleModel();
        $article->setId($_POST["id"]);

        if ($article->getDeletedAt()) {
            $article->hardDelete()->where("id", $_POST["id"])->execute();
        } else {
            $article->delete();
        }
    }

}