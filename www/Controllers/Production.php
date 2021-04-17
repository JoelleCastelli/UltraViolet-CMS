<?php

namespace App\Controller;

use App\Core\FormValidator;
use App\Core\View;
use App\Models\Production as ProductionModel;


class Production
{

    public function showAllAction() {
        $productions = new ProductionModel();
        $productions = $productions->findAll();

        if(!$productions) $productions = [];

        foreach ($productions as $production) {
            $production->cleanReleaseDate();
            $production->translateType();
            $production->cleanRuntime();
        }

        $view = new View("productions/list");
        $view->assign("productions", $productions);
        $view->assign('title', 'Productions');
    }

    public function addProductionAction() {
        $production = new ProductionModel();
        $form = $production->formBuilderAddProduction();
        $view = new View("production/add-production");
        $view->assign("form", $form);

        if(!empty($_POST)) {
            $errors = FormValidator::check($form, $_POST);
            if(empty($errors)) {
                $production->setTitle($_POST["title"]);
                $production->setType($_POST["type"]);
                $production->save();
            } else {
                $view->assign("errors", $errors);
            }
        }
    }