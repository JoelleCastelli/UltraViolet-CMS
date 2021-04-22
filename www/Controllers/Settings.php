<?php


namespace App\Controller;


use App\Core\View;

class Settings
{
    public function showAllAction() {
        $view = new View("settings/list");
        $view->assign('title', 'Paramètres');
    }

}