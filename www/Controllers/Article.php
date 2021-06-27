<?php

namespace App\Controller;

use App\Core\View;
use App\Core\Helpers;
use App\Core\FormValidator;
use App\Models\Article as ArticleModel;

class Article {

    // utils

    private function redirect(string $route, string $code = "307") {
        Helpers::redirect(Helpers::callRoute($route), $code);
    }

    // Standard controller methods

    public function showAllAction() {
        $article = new ArticleModel;
        $articles = $article->selectWhere('state', 'published');
        
        $view = new View("articles/list");
        $view->assign('title', 'Articles');
        $view->assign('articles', $articles);
        $view->assign('headScripts', [PATH_TO_SCRIPTS.'headScripts/articles/articles.js']);
    }

    public function createArticleAction() {

        $article = new ArticleModel();
        $form = $article->formBuilderCreateArticle();
        $view = new View("articles/createArticle");

        if (!empty($_POST)) {

//              $errors = FormValidator::check($form, $_POST);
                $errors = [];
//              if (empty($errors)) {
                if (true) { // CSRF error here always

                $title = htmlspecialchars($_POST["title"]);

                $article->setTitle($title);
                $article->setSlug(Helpers::slugify($title));
                    
                $article->setDescription(htmlspecialchars($_POST["description"]));
                $article->setContent(htmlspecialchars($_POST["content"]));
                $article->setState(htmlspecialchars($_POST["state"]));

                // TODO : Get real connected Person and Media used
                $article->setMediaId(1);
                $article->setPersonId(1);

                $article->save();
            } 
            else 
                $view->assign("errors", $errors);
        }
        $view->assign("title", "Créer un article");
        $view->assign("form", $form);
    }

    public function updateArticleAction($id) {
        // TODO : check and redirect if id exist or invalid

        $article = new ArticleModel();
        $article->setId($id);

        $view = new View("articles/updateArticle");
        $form = $article->formBuilderUpdateArticle();

        if (!empty($_POST)) {

            // $errors = FormValidator::check($form, $_POST);
            $errors = [];

//              if (empty($errors)) {
            if (true) {

                $title = htmlspecialchars($_POST["title"]);

                $article->setTitle($title);
                $article->setSlug(Helpers::slugify($title));

                $article->setDescription(htmlspecialchars($_POST["description"]));
                $article->setContent(htmlspecialchars($_POST["content"]));
                $article->setState(htmlspecialchars($_POST["state"]));

                // TODO : Get real connected Person and Media used
                $article->setMediaId(1);
                $article->setPersonId(1);

                $article->save();
            }
            else
                $view->assign("errors", $errors);
        }

        $arrayArticle = $article->jsonSerialize();

        $view->assign('form', $form);
        $view->assign("data", $arrayArticle);
        $view->assign("title", "Modifier un article");
        $view->assign("articleId", $id);
    }

    public function deleteArticleAction() {
        // TODO : check and redirect if id exist or invalid

        if (empty($_POST["id"]))  return;

        $article = new ArticleModel();
        $article->setId($_POST["id"]);
        $article->setState("deleted");
        $article->delete();


    }

    // API methods : Always return a json object

    public function getArticlesAction() {
        if (empty($_POST['state'])) return;

        $state = $_POST['state'];
        $articles = new ArticleModel();

        $articles = $articles->selectWhere('state', htmlspecialchars($_POST['state']));

        if (!$articles) $articles = [];

        $articlesArray = [];
        foreach ($articles as $article) {
            $articlesArray[] = [
                "Titre" => $article->getTitle(),
                "Auteur" => $article->getPerson()->getPseudo(),
                "Vues" => $article->getTotalViews(),
                "Commentaire" => "[NOMBRE COMMENTAIRE]",
                "Date" => $article->getCreatedAt(),
                "Publication" => $article->getState(),
                "Actions" => $article->generateActionsMenu()
            ];
        }

        echo json_encode([
            "state" => $state,
            "articles" => $articlesArray
        ]);
    }

}