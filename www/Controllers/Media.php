<?php

namespace App\Controller;

use App\Core\View;
use App\Core\MediaManager;
use App\Models\Media as MediaModel;

class Media
{

    /**
     * @var string[]
     */
    protected $columnsTable;

    public function __construct() {
        $this->columnsTable = [
            "thumbnail" => 'Miniature',
            "title" => 'Nom',
            "actions" => 'Actions'
        ];
    }

    public function showAllAction() {
        $view = new View("medias/list");
        $view->assign('title', 'Média');
        $media = new MediaModel();
        $form = $media->formBuilderUpload();
        $view->assign("form", $form);
        $view->assign('columnsTable', $this->columnsTable);
        $view->assign('bodyScripts', [PATH_TO_SCRIPTS.'bodyScripts/medias.js']);
    }

    public function getMediasAction() {
        if(!empty($_POST['mediaType'])) {
            $media = new MediaModel();

            if($_POST['mediaType'] === 'poster') {
                $media = $media->findOneBy('id', 615);
            } elseif($_POST['mediaType'] === 'vip') {
                $media = $media->findOneBy('id', 616);
            } elseif($_POST['mediaType'] === 'video') {
                $media = $media->findOneBy('id', 617);
            } elseif($_POST['mediaType'] === 'other') {
                $media = $media->findOneBy('id', 618);
            }

            $medias[] = $media;
            if(!$medias) $medias = [];

            $mediasArray = [];
            foreach ($medias as $media) {
                $mediasArray[] = [
                    $this->columnsTable['title'] => $media->getTitle(),
                    $this->columnsTable['thumbnail'] => "<img class='thumbnail' src='".$media->getPath()."'/>",
                    $this->columnsTable['actions'] => $media->generateActionsMenu(),
                ];
            }
            echo json_encode($mediasArray);
        }
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