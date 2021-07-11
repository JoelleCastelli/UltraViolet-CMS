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

    public function showAllAction() {        
        $view = new View("articles/list");
        $view->assign('title', 'Articles');
        $view->assign('headScripts', [PATH_TO_SCRIPTS.'headScripts/articles/articles.js']);
    }

    public function createArticleAction() {
        $article = new ArticleModel();
        $form = $article->formBuilderCreateArticle();

        $view = new View("articles/createArticle");
        $view->assign("title", "Créer un article");
        $view->assign("form", $form);
        $view->assign('bodyScripts', [
            "tiny" => PATH_TO_SCRIPTS.'bodyScripts/tinymce.js',
            "articles" => PATH_TO_SCRIPTS.'bodyScripts/articles/articles.js'
        ]);
    
        if (!empty($_POST)) {

            $errors = FormValidator::check($form, $_POST);
            if ($article->hasDuplicateSlug($_POST["title"])) $errors[] = "Ce slug (titre adapté à l'URL) existe déjà. Veuillez changer votre titre d'article";

            if (empty($errors)) {

                $title = htmlspecialchars($_POST["title"]);
                $state = $_POST["state"];
                $user = Request::getUser();

                $article->setTitle($title);
                $article->setSlug(Helpers::slugify($title));
                $article->setDescription(htmlspecialchars($_POST["description"]));
                $article->setContent($_POST["content"]);
                $article->setMediaId(htmlspecialchars($_POST["media"]));
                $article->setPersonId($user->getId());
                
                if ($state == "published") {
                    $today = date("Y-m-d\TH:i");
                    $article->setPublicationDate($today);
                } else if ($state == "scheduled" && !empty($_POST["publicationDate"])) {
                    $article->setPublicationDate(htmlspecialchars($_POST["publicationDate"]));
                } else if ($state == "draft") {
                    // continue
                } else {
                    $today = date("Y-m-d\TH:i");
                    $article->setPublicationDate($today);
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

            } else {
                $view->assign("errors", $errors);
            }
        }
    }

    public function updateArticleAction($id) {
        $article = new ArticleModel();

        $articleExist = $article->setId($id);
        if (!$articleExist) Helpers::redirect404();

        $view = new View("articles/updateArticle");
        $form = $article->formBuilderUpdateArticle($id);

        $view->assign('form', $form);
        $view->assign("data", $article->jsonSerialize());
        $view->assign("title", "Modifier un article");
        $view->assign('bodyScripts', [
            "tiny" => PATH_TO_SCRIPTS.'bodyScripts/tinymce.js',
            "articles" => PATH_TO_SCRIPTS.'bodyScripts/articles/articles.js'
        ]);

        if (!empty($_POST)) {

            $errors = FormValidator::check($form, $_POST);
            if ($article->hasDuplicateSlug($_POST["title"], $id)) $errors[] = "Ce slug (titre adapté à l'URL) existe déjà. Veuillez changer votre titre d'article";

            if (empty($errors)) {

                $title = htmlspecialchars($_POST["title"]);
                $state = $_POST["state"];
                $user = Request::getUser();
                $today = date("Y-m-d\TH:i");

                $article->setTitle($title);
                $article->setSlug(Helpers::slugify($title));
                
                $article->setContent($_POST["content"]);
                $article->setMediaId(htmlspecialchars($_POST["media"]));
                $article->setPersonId($user->getId());

                if ($state == "published") {
                    $article->setPublicationDate($today);
                    $article->setDeletedAt(null);
                } else if ($state == "scheduled" && !empty($_POST["publicationDate"])) {
                    $article->setPublicationDate(htmlspecialchars($_POST["publicationDate"]));
                    $article->setDeletedAt(null);
                } else if ($state == "draft") {
                    $article->setPublicationDate(null);
                    $article->setDeletedAt(null);
                } else if ($state == "removed") {
                    $article->setDeletedAt(Helpers::getCurrentTimestamp());
                } else {
                    
                }

                $categoryArticle = new CategoryArticleModel();

                $entriesInDB = $categoryArticle->select("categoryId")->where("articleId", $id)->get(false);
                $categoriesInPost = $_POST["categories"];
                
                $entriesToRemove = array_diff($entriesInDB, $categoriesInPost);
                $entriesToAdd = array_diff($categoriesInPost, $entriesInDB);

                foreach($entriesToRemove as $entry) {
                    $categoryArticle->hardDelete()->where("articleId", $id)->andWhere("categoryId", $entry)->execute();
                }
        
                foreach($entriesToAdd as $entry) {
                    $newCategory = new CategoryArticleModel();
                    $newCategory->setArticleId($id);
                    $newCategory->setCategoryId($entry);
                    $newCategory->save();
                }

                $article->save();
                Helpers::namedRedirect("articles_list");
            
            } else {
                $view->assign("errors", $errors);
            }
        }
    }


    // API methods

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

        Helpers::cleanDumpArray($_POST["id"], "id de l'article à supprimer");
        die();

        if (empty($_POST["id"])) return;

        $article = new ArticleModel();
        $id = $_POST["id"];
        $article->setId($id);

        if ($article->getDeletedAt()) {
            $categoryArticle = new CategoryArticleModel();
            $entries = $categoryArticle->select()->where("articleId", $id)->get();
            foreach ($entries as $entry) {
                $entry->hardDelete()->execute();
            }
            $article->hardDelete()->where("id", $id)->execute();
        } else {
            $article->setDeletedAt(Helpers::getCurrentTimestamp());
        }
    }

}