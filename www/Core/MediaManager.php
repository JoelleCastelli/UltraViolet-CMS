<?php

namespace App\Core;

class MediaManager
{
    protected array $files = [];
    protected int $oneMegabytesInBytes = 1048576;
    protected array $result = [];
    private array $imageExtensions = ['jpg', 'jpeg', 'png'];
    private array $videoExtensions = ['mp4', 'mov', 'avi', 'flv', 'wmv'];

    public function __construct()
    {
        $this->result = [
            "errors" => [],
            "files" => []
        ];
    }

    public function check($files, $type) {

        // init array files correctly
        $this->files = $this->generateFilesArray($files);

        // Max number of simultaneous files
        if(sizeof($this->files) > 10) {
            $this->result['errors'][] = "Vous ne pouvez pas sélectionner plus de 10 fichiers";
        }

        // verifications files
        foreach ($this->getFiles() as $file) {

            if($file['error'] != UPLOAD_ERR_OK) return "Erreur dans le chargement du fichier";

            //init
            $fileSize = $file['size'];
            $fileName = basename($file["name"]);
            $filePath = getcwd().PATH_TO_IMG.$type."/".$fileName;
            $fileTempPath = $file['tmp_name'];
            $fileExtension = pathinfo($file['name'], PATHINFO_EXTENSION);

            // validate files
            if(!$this->isCorrectFileType($fileExtension)) {
                $this->result['errors'][] = "Seules les images et les vidéos sont acceptées";
                return $this->result['errors'];
            }

            $video = $this->isVideo($fileExtension);
            $this->result['files'][] =  [
                "video" => $video,
                "path" => $filePath,
                "title" => $fileName,
                "tempPath" => $fileTempPath
            ];

            if ($video === false) {
                // file is image
                $this->imageSizeValidator($fileSize);
                if (!empty($this->result['errors']))
                    return $this->result['errors'];
            } elseif ($video === true) {
                // file is video
                $this->videoSizeValidator($fileSize);
                if (!empty($this->result['errors']))
                    return $this->result['errors'];
            } else {
                return $this->result['errors'];
            }

        }
        $this->setFiles($this->result['files']);
        return $this->result['errors'];
    }

    public function isCorrectFileType($fileExtension): bool
    {
        if (in_array($fileExtension, $this->imageExtensions))
            return true;
        elseif (in_array($fileExtension, $this->videoExtensions))
            return true;
        else
            return false;
    }

    public function isVideo($fileExtension): bool
    {
        if (in_array($fileExtension, $this->imageExtensions))
            return false;
        elseif (in_array($fileExtension, $this->videoExtensions))
            return true;
    }

    public function imageSizeValidator($fileSize)
    {
        $max = 10 * $this->oneMegabytesInBytes;
        if($fileSize > $max)
            $this->result['errors'][] = "Le poids de l'image ne peut pas être supérieur à 10MB";
    }

    public function videoSizeValidator($fileSize)
    {
        $max = 30 * $this->oneMegabytesInBytes;
        if($fileSize > $max)
            $this->result['errors'][] = "Le poids de la vidéo ne peut pas être supérieur à 30MB";
    }

    public function uploadFile($mediaManagerFiles): bool
    {
        foreach ($mediaManagerFiles as $file) {
            try {
                move_uploaded_file($file['tempPath'], $file['path']);
            } catch (\Exception $e) {
                $this->result['errors'][] = "Le téléchargement n'a pas pu être effectué. " . $e;
                return false;
            }
        }
        return true;
    }

    public function generateFilesArray(&$file_post): array
    {
        //init
        $isMulti = is_array($file_post['name']);
        $fileCount = $isMulti?count($file_post['name']):1;
        $fileKeys = array_keys($file_post);

        //build array
        $fileArr = [];
        for( $i = 0; $i < $fileCount; $i++) {

            foreach ($fileKeys as $key) {
                if ($isMulti)
                    $fileArr[$i][$key] = $file_post[$key][$i];
                else
                    $fileArr[$i][$key] = $file_post[$key];
            }
        }
        return $fileArr;
    }

    public function setFiles(array $files): void
    {
        $this->files = $files;
    }

    public function getFiles(): array
    {
        return $this->files;
    }

}
