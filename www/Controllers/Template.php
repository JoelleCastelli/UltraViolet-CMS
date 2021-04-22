<?php

namespace App\Controller;

use App\Core\View;

class Template {

    public function showAllAction() {
        $view = new View("templates/list");
        $view->assign('title', 'Templates');
    }

}