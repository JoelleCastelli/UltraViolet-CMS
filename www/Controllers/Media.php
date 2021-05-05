<?php

namespace App\Controller;

use App\Core\View;
use App\Core\Helpers;
use App\Core\MediaManager;
use App\Models\Media as MediaModels;

class Media
{

    public function showAllAction() {
        $view = new View("medias/list");
        $view->assign('title', 'Média');

        $media = new MediaModels();
        $form = $media->formBuilderUpload();
        $view->assign("form", $form);
    }

    public function uploadAction()
    {
        //Helpers::dd($_POST);

        if (!empty($_FILES)) {
            echo "file";
            echo "<pre>";

            print_r($_FILES['media']);
            new MediaManager($_FILES['media']);
            die();
        }else {
            echo "empty file";
        }


    }
}