<?php

namespace App\Controller;

use App\Core\View;
use App\Core\Helpers;
use App\Core\FormValidator;
use App\Models\Article as ArticleModel;

class Article {

    // TODO : Checker que l'user à les droits et est connecté, et qu'une page existe pour y attacher l'article
    // TODO : Faire une fonction globale permettant de slugifier un article en fonction de son titre

    public function showAllAction() {
        $article = new ArticleModel;
        $articles = $article->selectWhere('state', 'published');
        $view = new View("articles/list");
        $view->assign('title', 'Articles');
        $view->assign('headScript', Helpers::urlJS('headScripts/articles'));
        $view->assign('articles', $articles);
    }

    public function tabChangeAction() {
        $article = new ArticleModel;
        echo json_encode($article->selectWhere('state', $_POST['articleState']));
    }

    public function modifyArticleAction() {
        $view = new View("articles/modifyArticle");
        $view->assign("title", "Modifier un article");
    }

    public function createArticleAction() {

        $article = new ArticleModel();
        $form = $article->formBuilderCreateArticle();
        $view = new View("articles/createArticle");

        if (!empty($_POST)) {

            $errors = FormValidator::check($form, $_POST);
            
            if (empty($errors)) {

                $title = htmlspecialchars($_POST["title"]);

                $article->setTitle($title);
                $article->setSlug(Helpers::slugify($title));
                $article->setDescription(htmlspecialchars($_POST["description"]));
                $article->setContent(htmlspecialchars($_POST["content"]));
                $article->setState(htmlspecialchars($_POST["state"]));

                // TODO : Get real connected Person and Media used
                $article->setUvtrMediaId(1);
                $article->setUvtrPersonId(1);

                $article->save();
            } 
            else 
                $view->assign("errors", $errors);
        }
        $view->assign("title", "Créer un article");
        $view->assign("form", $form);
    }

}