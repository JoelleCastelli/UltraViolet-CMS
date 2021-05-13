<?php

namespace App\Controller;

use App\Core\View;
use App\Core\Helpers;
use App\Core\MediaManager;
use App\Models\Media as MediaModels;

class Media
{

    /**
     * @var string[]
     */
    protected $columnsTable;

    public function __construct() {
        $this->columnsTable = [
            "title" => 'Nom',
            "path" => 'Chemin',
            "video" => 'Vidéo',
        ];
    }

    public function showAllAction() {
        $view = new View("medias/list");
        $view->assign('title', 'Média');

        $media = new MediaModels();
        $form = $media->formBuilderUpload();
        $view->assign("form", $form);
        $view->assign('bodyScript', Helpers::urlJS('/bodyScripts/medias'));

    }

    public function getMediasAction() {

        if(empty($_POST['mediaType'])) // get all medias
        {
            $media = new MediaModels();
            $medias = $media->findAll();

            if(!$medias) $medias = [];

        }else { // get medias by type

            echo 'oops';

        }

        // populate in arrays
        $mediaArray = [];

        foreach ($medias as $media) {

            $mediaArray[] = array(
                $this->columnsTable['title'] => $media->getTitle(),
                $this->columnsTable['path'] => $media->getPath(),
                $this->columnsTable['video'] => $media->getVideo(),
            );
        }

        // send data
        $data = array(
            "medias" => $mediaArray,
            "columns" => $this->columnsTable,
            "type" => $_POST['mediaType']??""
        );

        echo json_encode($data);
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