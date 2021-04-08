<?php

namespace App\Controller;

use App\Core\View;
use App\Models\Production as ProductionModel;


class Production
{

    public function displayProductionListAction() {
        $productions = new ProductionModel();
        $productions = $productions->findAll();

        if(!$productions) $productions = [];

        foreach ($productions as $production) {
            $production->cleanReleaseDate();
            $production->translateType();
            $production->cleanRuntime();
        }

        $view = new View("production/list");
        $view->assign("productions", $productions);
    }

}