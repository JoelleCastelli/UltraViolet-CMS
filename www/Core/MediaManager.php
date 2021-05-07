<?php

namespace App\Core;

class MediaManager
{
    protected $files;
    protected $video;
    protected $result = [];

    public function __construct()
    {
        $this->result = [
            "errors" => [],
            "files" => []
        ];

    }

    public function check($files) {

        // init array files correctly
        $this->files = $this->formatArrayFiles($files);
        print_r($this->files);

        // verifications files
        foreach ($this->files as $file) {

            if($file['error'] != UPLOAD_ERR_OK)
            {
                return "error file oops";
            }

            //init
            $fileSize = $file['size'];
            $fileName = md5(time() . htmlspecialchars($file['name'])) . '.' . pathinfo($file['name'], PATHINFO_EXTENSION);
            $filePath = "dist/images/back/" . $fileName;
            $fileTempPath = $file['tmp_name'];
            $fileExtension = pathinfo($file['name'], PATHINFO_EXTENSION);

            // validate files
            $this->typeAndExtensionValidator($fileExtension);

            $this->result['files'][] =  [
                "video" => $this->video,
                "path" => $filePath,
                "title" => $fileName,
                "tempPath" => $fileTempPath
            ];

            if ($this->video === false) { // file is image
                $this->imageSizeValidator($fileSize);

                if (!empty($this->result['errors']))
                    return $this->result;

            } elseif ($this->video === true) { // file is video
                echo "file video";

            } else {
                return $this->result;
            }

        }

        // upload files
        foreach ($this->result['files'] as $file) {
            $this->uploadFile($file['title'], $file['path'], $file['tempPath']);
        }

        print_r($this->result);

        return $this->result;

    }
    public function typeAndExtensionValidator($fileExtension)
    {
        $imageExtensions = array('jpg', 'jpeg', 'png', 'gif', 'tiff', 'svg');
        $videoExtensions = array('mp4', 'mov', 'avi', 'flv', 'wmv');

        if (in_array($fileExtension, $imageExtensions))
            $this->video = false;
        elseif (in_array($fileExtension, $videoExtensions))
            $this->video = true;
        else
            $this->result['errors'] = "Veuillez envoyer seulement les vidéos ou photos";
    }

    public function imageSizeValidator($fileSize)
    {
        $oneMegabytesInBytes = 1048576;
        $max = 10 * $oneMegabytesInBytes;

        if($fileSize > $max) {
            $this->result['errors'] = "Votre image est de taille supérieur à 10MB";
        }

    }

    public function uploadFile($fileName, $filePath, $fileTempPath) {

        try {
            move_uploaded_file($fileTempPath, $filePath);

        }catch (\Exception $e) {
            $this->result['errors'] = "oops, problème au déplacement des fichiers exception " . $e;

        }
    }

    public function formatArrayFiles(&$file_post){

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

}
