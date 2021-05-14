<?php

namespace App\Controller;

use App\Core\Helpers;
use App\Core\View;
use App\Models\Article as ArticleModel;

class Article {

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

}