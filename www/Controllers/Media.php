<?php

namespace App\Controller;

use App\Core\FormValidator;
use App\Core\Helpers;
use App\Core\View;
use App\Core\MediaManager;
use App\Models\Media as MediaModel;

class Media
{

    protected array $columnsTable;

    public function __construct() {
        $this->columnsTable = [
            "thumbnail" => 'Miniature',
            "title" => 'Nom',
            "id" => "Identifiant",
            "createdAt" => "Date d'ajout",
            "actions" => "Actions"

        ];
    }

    public function showAllAction() {
        $view = new View("medias/list");
        $view->assign('title', 'Médias');
        $view->assign('columnsTable', $this->columnsTable);
        $view->assign('bodyScripts', [PATH_TO_SCRIPTS.'bodyScripts/medias.js']);
        $media = new MediaModel();
        $form = $media->formBuilderUpload();
        $view->assign("form", $form);
        if(!empty($_POST) && !empty($_FILES)) {
            $errors = FormValidator::check($form, $_POST);
            if(empty($errors)) {
                $mediaManager = new MediaManager();
                $errors = $mediaManager->check($_FILES['media'], 'other');
                if(empty($errors)) {
                    $mediaManager->uploadFile($mediaManager->getFiles());
                    $mediaManager->saveFile($mediaManager->getFiles());
                    Helpers::redirect(Helpers::callRoute('media_list'));
                }
            }
            $view->assign("errors", $errors);
        }
    }

    /**
     * Called by AJAX script to display media filtered by type
     */
    public function getMediasAction() {
        if(!empty($_POST['mediaType'])) {
            $medias = new MediaModel();

            if($_POST['mediaType'] === 'poster') {
                $medias = $medias->select()->like('path', "%/posters/%")->orderBy('createdAt', 'DESC')->get();
            } elseif($_POST['mediaType'] === 'vip') {
                $medias = $medias->select()->like('path', "%/vip/%")->orderBy('createdAt', 'DESC')->get();
            } elseif($_POST['mediaType'] === 'video') {
                $medias = $medias->select()->where('video', 1)->orderBy('createdAt', 'DESC')->get();
            } elseif($_POST['mediaType'] === 'other') {
                $medias = $medias->select()->like('path', "%/other/%")->orderBy('createdAt', 'DESC')->get();
            }

            if(!$medias) $medias = [];

            $mediasArray = [];
            foreach ($medias as $media) {

                // Dlay default image if file is not found
                if(file_exists(getcwd().$media->getPath()))
                    $path = $media->getPath();
                else
                    $path = PATH_TO_IMG.'default_poster.jpg';

                $mediasArray[] = [
                    $this->columnsTable['title'] => $media->getTitle(),
                    $this->columnsTable['thumbnail'] => "<img class='thumbnail' src='".$path."'/>",
                    $this->columnsTable['id'] => $media->getId(),
                    $this->columnsTable['createdAt'] => $media->getCleanCreatedAtDate(),
                    $this->columnsTable['actions'] => $media->generateActionsMenu(),
                ];
            }
            echo json_encode($mediasArray);
        }
    }

    public function deleteMediaAction() {
        if(!empty($_POST['mediaId'])) {
            $response = [];
            $media = new MediaModel();
            $deleteOk = $media->hardDelete()->where('id', $_POST['mediaId'])->execute();
            if($deleteOk) {
                $response['success'] = true;
                $response['message'] = "L'image a été supprimée";
            } else {
                $response['false'] = true;
                $response['message'] = "L'image n'a pas pu être supprimée";
            }
            echo json_encode($response);
        }
    }

    /**
     * Called by AJAX script to return array of ugc images
     */
    public function getMediasUserAction() { 
        
        /* return list of all ugc images */
        $files = array_diff(scandir('src/img/other'), ['..', '.']);
        $images = [];
        $regex = '([a-zA-Z-_]+(\.(?i)(jpg|png|gif|bmp))$)';

        // return array format for tinymce
        foreach ($files as $file) {
            if(preg_match($regex, $file))
                array_push($images, [
                    'title' => $file,
                    'value' => PATH_TO_IMG . 'other/' . $file]
                );
        }

        echo json_encode($images);
    }
}