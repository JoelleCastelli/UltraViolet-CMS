<?php


namespace App\Controller;


use App\Core\View;

class Stats
{
    public function showAllAction() {
        $view = new View("stats/list");
        $view->assign('title', 'Statistiques');
    }

}