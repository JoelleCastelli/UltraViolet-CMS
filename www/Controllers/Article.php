<?php

namespace App\Controller;

use App\Core\View;

class Article {

    public function showAllAction() {
        $view = new View("articles/list");
        $view->assign('title', 'Articles');
    }

    public function modifyArticleAction() {
        $view = new View("articles/modifyArticle", "back");
        $view->assign("title", "Modifier un article");
    }

}