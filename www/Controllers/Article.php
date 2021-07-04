<?php

namespace App\Controller;

use App\Core\View;
use App\Core\Helpers;
use App\Core\FormValidator;
use App\Core\Request;
use App\Models\Article as ArticleModel;

class Article {

    // utils

    private function getArticlesBySate($state) : array {
        $article = new ArticleModel;
        $now = date('Y-m-d H:i:s');

        if ($state == "published") {
           return $article->select()
           ->where("publicationDate", $now, "<=")
           ->andWhere("deletedAt", "NULL", "=")
           ->get();
        } 
        
        if ($state == "scheduled") {
            return $article->select()
            ->where("publicationDate", $now, ">=")
            ->andWhere("deletedAt", "NULL")
            ->get();
        } 
        
        if ($state == "draft") {
            return $article->select()
            ->where("publicationDate", "NULL")
            ->andWhere("deletedAt", "NULL")
            ->get();
        } 
        
        if ($state == "removed") {
            return $article->select()->where("deletedAt", "NOT NULL")->get();
        }

        return [];

    }

    // Standard controller methods
    public function showAllAction() {
        $article = new ArticleModel;

        // $articles = $this->getArticlesBySate("published");
        // $articles = $this->getArticlesBySate("scheduled");
        // $articles = $this->getArticlesBySate("draft");
        // $articles = $this->getArticlesBySate("removed");
        $articles = [];
        
        $view = new View("articles/list");
        $view->assign('title', 'Articles');
        $view->assign('articles', $articles);
        $view->assign('headScripts', [PATH_TO_SCRIPTS.'headScripts/articles/articles.js']);
    }

    public function createArticleAction() {
        // TODO : check and redirect if id exist or invalid

        $article = new ArticleModel();
        $form = $article->formBuilderCreateArticle();
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
                if (!empty($_POST["publicationDate"])) {
                    $article->setPublicationDate(htmlspecialchars($_POST["publicationDate"]));
                }

                // TODO : Get Real Media
                $article->setMediaId(1);
                $article->setPersonId($user->getId());
                
                $article->save();
                Helpers::namedRedirect("articles_list");
            }
            else
                $view->assign("errors", $errors);
        }

        $view->assign("title", "Créer un article");
        $view->assign("form", $form);
        $view->assign('bodyScripts', [PATH_TO_SCRIPTS.'bodyScripts/tinymce.js']);
    }

    public function updateArticleAction($id) {
        // TODO : check and redirect if id exist or invalid


        $article = new ArticleModel();
        $article->setId($id);

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
                if (!empty($_POST["publicationDate"])) {
                    $article->setPublicationDate(htmlspecialchars($_POST["publicationDate"]));
                }


                // TODO : Get Real Media
                $article->setMediaId(1);
                $article->setPersonId($user->getId());
                
                $article->save();
                $this->redirect("articles_list");
            }
            else
                $view->assign("errors", $errors);
        }

        $arrayArticle = $article->jsonSerialize();
        // Helpers::dd($arrayArticle);

        $view->assign('form', $form);
        $view->assign("data", $arrayArticle);
        $view->assign("title", "Modifier un article");
        $view->assign('bodyScripts', [PATH_TO_SCRIPTS.'bodyScripts/tinymce.js']);
    }



    // API methods

    // TODO : Need to secure this
    public function getArticlesAction() {

        $articles = new ArticleModel();
        $articles = $articles->findAll();

        if (!$articles) $articles = [];

        $articlesArray = [];
        foreach ($articles as $article) {
            $articlesArray[] = [
                "Titre" => $article->getTitle(),
                "Auteur" => $article->getPerson()->getPseudo(),
                "Vues" => $article->getTotalViews(),
                "Commentaire" => "[NOMBRE COMMENTAIRE]",
                "Date" => $article->getCreatedAt(),
                "Publication" => $article->getPublicationDate(),
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