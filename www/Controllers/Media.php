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
        $view->assign('bodyScript', 'src/js/bodyScripts/medias.js');

    }

    public function uploadAction()
    {
        echo "<pre>";


        if (!empty($_FILES)) {

            print_r($_FILES);

            $mediaManager = new MediaManager();
            $result = $mediaManager->check($_FILES['media']);
            print_r($result);
            die();
        }else {
            echo "empty file";
            print_r($_FILES);
        }


    }
}