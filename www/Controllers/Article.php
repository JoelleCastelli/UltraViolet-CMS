<?php

namespace App\Controller;

use App\Core\View;

class Article {
    
    public function modifyArticleAction() {
        $view = new View("modifyArticle", "back");
    }

}