<?php

namespace App\Controller;

use App\Core\View;

class Comment
{

    public function showAllAction() {
        $view = new View("comments/list");
        $view->assign('title', 'Commentaires');
    }



}