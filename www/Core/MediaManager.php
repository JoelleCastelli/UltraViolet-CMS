<?php

namespace App\Core;

use App\Models\Media;

class MediaManager
{
    protected array $files = [];
    protected int $oneMegabytesInBytes = 1048576;
    protected array $result = [];
    private array $imageExtensions = ['jpg', 'jpeg', 'png', 'svg'];

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
            $fileName = pathinfo($file['name'], PATHINFO_FILENAME);
            $filePath = PATH_TO_IMG.$type."/". $file['name'];
            $fileTempPath = $file['tmp_name'];
            $fileExtension = pathinfo($file['name'], PATHINFO_EXTENSION);

            // validate files
            if(!$this->isCorrectFileType($fileExtension)) {
                $this->result['errors'][] = "Seules les images sont acceptées";
                return $this->result['errors'];
            }

            $this->result['files'][] =  [
                "path" => $filePath,
                "title" => $fileName,
                "tempPath" => $fileTempPath
            ];

            // file is image
            $this->imageSizeValidator($fileSize);
            if (!empty($this->result['errors']))
                return $this->result['errors'];

        }
        $this->setFiles($this->result['files']);
        return $this->result['errors'];
    }

    public function isCorrectFileType($fileExtension): bool
    {
        if (in_array($fileExtension, $this->imageExtensions))
            return true;
        else
            return false;
    }

    public function imageSizeValidator($fileSize)
    {
        $max = 10 * $this->oneMegabytesInBytes;
        if($fileSize > $max)
            $this->result['errors'][] = "Le poids de l'image ne peut pas être supérieur à 10MB";
    }

    public function uploadFile($mediaManagerFiles): bool
    {
        foreach ($mediaManagerFiles as $key => $file ) {

            $media = new Media();
            $existingMediaCount = $media->count('path')->where('path', $file['path'])->first();

            if ($existingMediaCount->total > 0) {
                $number = 1;

                // to format image name with number
                while($existingMediaCount->total > 0)
                {
                    $title = $file['title'] . '(' . $number . ')';
                    $path = pathinfo($file['path'], PATHINFO_DIRNAME) . '/' . $title . '.' . pathinfo($file['path'], PATHINFO_EXTENSION);
                $existingMediaCount = $media->count('path')->where('path', $path)->first();
                    $number++;
                }

                $this->files[$key]['path'] = pathinfo($file['path'], PATHINFO_DIRNAME) . '/' . $title . '.' . pathinfo($file['path'], PATHINFO_EXTENSION);
                $this->files[$key]['title'] = $title;

                $file['path'] = $this->files[$key]['path'];
            }

            $check = move_uploaded_file($file['tempPath'], getcwd() . $file['path']);

            if($check){
                $this->result['errors'][] = "Le téléchargement n'a pas pu être effectué. ";
                return false;
            }
        }
        return true;
    }

    public function saveFile($mediaManagerFiles) {
        foreach ($mediaManagerFiles as $file) {

            $media = new Media();
            $media->setPath($file['path']);
            $media->setTitle($file['title']);
            $media->save();
            
        }
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
