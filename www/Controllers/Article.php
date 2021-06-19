<?php

namespace App\Controller;

use App\Core\View;
use App\Core\Helpers;
use App\Core\FormValidator;
use App\Models\Article as ArticleModel;

class Article {

    function slugify($text) : string { 
   
        $text = preg_replace('~[^\pL\d]+~u', '-', $text);
        $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
        $text = preg_replace('~[^-\w]+~', '', $text);
        $text = trim($text, '-');
        $text = preg_replace('~-+~', '-', $text);
        $text = strtolower($text);
    
        if (empty($text)) return '-1';
        return $text;
    }

    public function showAllAction() {
        $article = new ArticleModel;
        $articles = $article->selectWhere('state', 'published');
        
        $view = new View("articles/list");
        $view->assign('title', 'Articles');
        $view->assign('articles', $articles);
        $view->assign('headScripts', [PATH_TO_SCRIPTS.'headScripts/articles/articles.js']);
    }

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
                "Actions" => "[CHOIX DES ACTIONS]"
            ];
        }

        echo json_encode([
            "state" => $state,
            "articles" => $articlesArray
        ]);
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

//              $errors = FormValidator::check($form, $_POST);

//              if (empty($errors)) {
                if (true) {

                $title = htmlspecialchars($_POST["title"]);
            
                

                $article->setTitle($title);

                // echo $this->slugify($title);

                $article->setSlug($this->slugify($title));

                // var_dump($article);

                $article->setDescription(htmlspecialchars($_POST["description"]));
                

                
                // $article->setContent(htmlspecialchars($_POST["content"]));
                $article->setContent("Super content statique");
                $article->setState(htmlspecialchars($_POST["state"]));
                
                

                // TODO : Get real connected Person and Media used
                $article->setMediaId(1);
                $article->setPersonId(1);

                // echo "<pre>";
                // var_dump($article);
                // echo "</pre>";

                // die;

                $article->save();
            } 
            else 
                $view->assign("errors", $errors);
        }
        $view->assign("title", "Créer un article");
        $view->assign("form", $form);
    }

}