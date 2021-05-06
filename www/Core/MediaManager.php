<?php

namespace App\Core;

class MediaManager
{
    protected $files;
    protected $video;
    protected $errors = [];

    public function __construct(array $files)
    {
        $this->files = $this->formatArrayFiles($files);
        print_r($this->files);

        foreach ($this->files as $file) {

            if($file['error'] != UPLOAD_ERR_OK)
            {
                return "error file oops";
            }

            //init
            $fileExtension = pathinfo($file['name'], PATHINFO_EXTENSION);
            $fileSize = $file['size'];

            // validate files
            $this->typeValidator($fileExtension);

            if ($this->video === false) {
                $this->imageSizeValidator($fileSize);

                if (!empty($this->errors))
                    return $this->errors;

            } elseif ($this->video === true) {
                echo "file video";

            } else {
                return $this->errors;
            }

        }

        foreach ($this->files as $file) {

            //init
            $fileName = md5(time() . htmlspecialchars($file['name'])) . '.' . pathinfo($file['name'], PATHINFO_EXTENSION);
            $filePath = "dist/images/back/" . $fileName;
            $fileTempPath = $file['tmp_name'];

            $this->uploadFile($fileName, $filePath, $fileTempPath);

        }
    }

    public function typeValidator($fileExtension)
    {
        $imageExtensions = array('jpg', 'jpeg', 'png', 'gif', 'tiff', 'svg');
        $videoExtensions = array('mp4', 'mov', 'avi', 'flv', 'wmv');

        if (in_array($fileExtension, $imageExtensions))
            $this->video = false;
        elseif (in_array($fileExtension, $videoExtensions))
            $this->video = true;
        else
            $this->errors = "Veuillez envoyer seulement les vidéos ou photos";
    }

    public function imageSizeValidator($fileSize)
    {
        $oneMegabytesInBytes = 1048576;
        $max = 10 * $oneMegabytesInBytes;

        if($fileSize > $max) {
            $this->errors = "Votre image est de taille supérieur à 10MB";
        }

    }

    public function uploadFile($fileName, $filePath, $fileTempPath) {

        try {
            move_uploaded_file($fileTempPath, $filePath);

        }catch (\Exception $e) {
            $this->errors = "oops, problème au déplacement des fichiers exception " . $e;

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

    // check type and extension
        //video or image
    // check size
    // sanitize name
    // insert in specific folder

/*


    $allowed_image_extension = array(
        "png",
        "jpg",
        "jpeg"
    );

    // Get image file extension
    $file_extension = pathinfo($_FILES["file-input"]["name"], PATHINFO_EXTENSION);

    // Validate image extension
    if (!in_array($file_extension, $allowed_image_extension))
    {
        return array(
            "type" => "error",
            "message" => "Seuls les extensions JPG, PNG et JPEG sont autorisées"
        );
    }
    // Validate image file size
    else if (($_FILES["file-input"]["size"] > 100000000))
    {
        return array(
            "type" => "error",
            "message" => "L'image excède 100MB"
        );
    }
    else
    {
        $target = PUBLIC_PATH . '/images/user/' . basename($_FILES["file-input"]["name"]);
        if (move_uploaded_file($_FILES["file-input"]["tmp_name"], $target)) {
            return array(
                "type" => "success",
                "message" => "Image bien sauvegardée"
            );
        } else {
            return array(
                "type" => "error",
                "message" => "Erreur ! Problème avec l'image"
            );
        }
    }*/
}
