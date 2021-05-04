<?php

namespace App\Models;

use App\Core\Helpers;
use App\Core\Database;
use App\Core\FormBuilder;
use App\Core\Traits\ModelsTrait;


class Person extends Database
{
    use ModelsTrait;

    private $id = null;
    private $createdAt;
    private $updatedAt;
    protected $fullName;
    protected $pseudo;
    protected $email;
    protected $password;
    protected $optin;
    protected $deletedAt;
    protected $role = 'user';
    protected $uvtr_media_id;

    //foreign properties
    public $media;

    public function __construct()
    {
        parent::__construct();
        $this->media = new Media();
    }

    /**
     * @return null
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getFullName()
    {
        return $this->fullName;
    }

    /**
     * @param mixed $fullName
     */
    public function setFullName($fullName): void
    {
        $this->fullName = $fullName;
    }

    /**
     * @return mixed
     */
    public function getPseudo()
    {
        return $this->pseudo;
    }

    /**
     * @param mixed $pseudo
     */
    public function setPseudo($pseudo): void
    {
        $this->pseudo = $pseudo;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param mixed $email
     */
    public function setEmail($email): void
    {
        $this->email = $email;
    }

    /**
     * @return mixed
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param mixed $password
     */
    public function setPassword($password): void
    {
        $this->password = $password;
    }

    /**
     * @return mixed
     */
    public function getOptin()
    {
        return $this->optin;
    }

    /**
     * @param mixed $optin
     */
    public function setOptin($optin): void
    {
        $this->optin = $optin;
    }

    /**
     * @return mixed
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * @param mixed $updatedAt
     */
    public function setUpdatedAt($updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }

    /**
     * @return mixed
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @return mixed
     */
    public function getDeletedAt()
    {
        return $this->deletedAt;
    }

    /**
     * @param mixed $deletedAt
     */
    public function setDeletedAt($deletedAt): void
    {
        $this->deletedAt = $deletedAt;
    }

    /**
     * @return string
     */
    public function getRole(): string
    {
        return $this->role;
    }

    /**
     * @param string $role
     */
    public function setRole(string $role): void
    {
        $this->role = $role;
    }

    /**
     * @return mixed
     */
    public function getUvtrMediaId()
    {
        return $this->uvtr_media_id;
    }

    /**
     * @param mixed $uvtr_media_id
     */
    public function setUvtrMediaId($uvtr_media_id): void
    {
        $this->uvtr_media_id = $uvtr_media_id;
    }

    /**
     * @return Media
     */
    public function getMedia(): Media
    {
        if(!empty($this->uvtr_media_id) && is_numeric($this->uvtr_media_id))
            $this->media->setId($this->uvtr_media_id);
        return $this->media;
    public function isLogged(): bool {
        if(isset($_SESSION['loggedIn']) && $_SESSION['loggedIn'] == true) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * @param Media $media
     */
    public function setMedia(Media $media): void
    {
        $this->media = $media;
    }
    public function formBuilderLogin()
    {
    public function canAccessBackOffice(): bool {
        if(in_array($this->getRole(), ['admin', 'editor', 'moderator'])) {
            return true;
        } else {
            return false;
        }
    }

    public function isAdmin(): bool {
        if($this->getRole() === 'admin') {
            return true;
        } else {
            return false;
        }
    }

    public function isEditor(): bool {
        if($this->getRole() === 'editor') {
            return true;
        } else {
            return false;
        }
    }

    public function isModerator(): bool {
        if($this->getRole() === 'moderator') {
            return true;
        } else {
            return false;
        }
    }

    public function isVIP(): bool {
        if($this->getRole() === 'vip') {
            return true;
        } else {
            return false;
        }
    }

    public function isUser(): bool {
        if($this->getRole() === 'user') {
            return true;
        } else {
            return false;
        }
    }

    public function formBuilderLogin(): array {
        return [
            "config" => [
                "method" => "POST",
                "action" => "",
                "class" => "form_control",
                "id" => "form_register",
                "submit" => "Se connecter",
                "referer" => '/connexion'
            ],
            "fields" => [
                "email" => [
                    "type" => "email",
                    "placeholder" => "Email",
                    "class" => "input",
                    "id" => "email",
                    "error" => "Votre champ email est vide.",
                    "required" => true,

                ],
                "password" => [
                    "type" => "password",
                    "placeholder" => "Mot de passe",
                    "class" => "input",
                    "error" => "Votre champ mot de passe est vide.",
                    "required" => true,

                ],
                "csrf_token" => [
                    "type" => "hidden",
                    "value" => FormBuilder::generateCSRFToken()
                ],
            ]

        ];
    }

    public function formBuilderRegister(): array {
        return [
            "config" => [
                "method" => "POST",
                "action" => "",
                "class" => "form_control",
                "id" => "form_register",
                "submit" => "S'inscrire",
                "referer" => '/inscription'
            ],
            "fields" => [
                "csrf_token" => [
                    "type" => "hidden",
                    "value" => FormBuilder::generateCSRFToken()
                ],
                "firstName" => [
                    "type" => "text",
                    "placeholder" => "Prénom",
                    "minLength" => 2,
                    "maxLength" => 50,
                    "class" => "input",
                    "error" => "Votre prénom doit faire entre 2 et 50 caractères et écrit correctement",
                    "regex" => "/^[a-zA-ZàáâäãåąčćęèéêëėįìíîïłńòóôöõøùúûüųūÿýżźñçčšžÀÁÂÄÃÅĄĆČĖĘÈÉÊËÌÍÎÏĮŁŃÒÓÔÖÕØÙÚÛÜŲŪŸÝŻŹÑßÇŒÆČŠŽ∂ð -]+$/u",
                    "required" => true,
                ],
                "name" => [
                    "type" => "text",
                    "placeholder" => "Nom",
                    "minLength" => 2,
                    "maxLength" => 50,
                    "class" => "input",
                    "error" => "Votre nom doit faire entre 2 et 50 caractères et écrit correctement",
                    "regex" => "/^[a-zA-ZàáâäãåąčćęèéêëėįìíîïłńòóôöõøùúûüųūÿýżźñçčšžÀÁÂÄÃÅĄĆČĖĘÈÉÊËÌÍÎÏĮŁŃÒÓÔÖÕØÙÚÛÜŲŪŸÝŻŹÑßÇŒÆČŠŽ∂ð -]+$/u",
                    "required" => true,
                ],
                "pseudo" => [
                    "type" => "text",
                    "placeholder" => "Pseudonyme",
                    "minLength" => 2,
                    "maxLength" => 25,
                    "class" => "input",
                    "error" => "Votre pseudo doit faire entre 2 et 25 caractères",
                    "required" => true,

                ],
                "email" => [
                    "type" => "email",
                    "placeholder" => "Email",
                    "class" => "input",
                    "maxLength" => 130,
                    "error" => "Votre email doit faire entre 8 et 130 caractères",
                    "required" => true,

                ],
                "pwd" => [
                    "type" => "password",
                    "placeholder" => "Mot de passe",
                    "minLength" => 8,
                    "class" => "input",
                    "regex" => "/(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&-])[A-Za-z\d@$!%*?&-]{8,}$/",
                    "error" => "Votre mot de passe doit faire au minimum 8 caractères, comporté au moins une lettre minusucule et majuscule, un chiffre et une lettre spéciale.",
                    "required" => true,

                ],
                "pwdConfirm" => [
                    "type" => "password",
                    "class" => "input",
                    "placeholder" => "Confirmation du mot de passe",
                    "confirm" => "pwd",
                    "error" => "Votre mot de passe de confirmation ne correspond pas",
                    "required" => true,
                ],
            ]
        ];
    }

}
