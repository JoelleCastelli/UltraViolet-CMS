<?php

namespace App\Core;

class MediaManager
{
    protected $file;
    protected $video;
    protected $errors = [];

    public function __construct(array $file)
    {
        $this->file = $file;
       // Helpers::dd($file);
        $this->typeValidator();
        if($this->video === false)
        {
            $this->imageSizeValidator();
            $this->uploadFile();

        }else if($this->video === true) {



        }else {
            $this->errors[] = "Veuillez envoyer une image ou vidéo.";
        }
    }

    public function typeValidator()
    {
        $extension = pathinfo($this->file['name'], PATHINFO_EXTENSION);

        $imageExtensions = array('jpg', 'jpeg', 'png', 'gif', 'tiff', 'svg');
        $videoExtensions = array('mp4', 'mov', 'avi', 'flv', 'wmv');
        if (in_array($extension, $imageExtensions)) {
            $this->video = false;
        } elseif (in_array($extension, $videoExtensions)) {
            $this->video = true;
        }   else {
            $this->video = null;
        }
    }

    public function imageSizeValidator()
    {
        $size = $this->file['size'];

        if($size > 10485760) {
            $this->errors[] = "Votre image est de taille supérieur à 10MB";
            echo "error size";
        } else {
            echo "size good";
        }

    }

    public function uploadFile() {
        if(empty($this->errors))
        {
            $fileName = md5(time() . htmlspecialchars($this->file['name'])) . '.' . pathinfo($this->file['name'], PATHINFO_EXTENSION);
            $this->file['name'] = $fileName;
            $check = move_uploaded_file($this->file['tmp_name'], "dist/images/back/" . $fileName);

            if($check) {
                echo "file upload success";
                return true;

            }else {
                echo "cannot move uploaded file oops";
                return $this->errors;

            }
        }
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
