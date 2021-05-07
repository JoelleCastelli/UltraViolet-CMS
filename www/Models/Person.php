<?php

namespace App\Models;

use App\Core\Database;
use App\Core\FormBuilder;
use App\Core\Traits\ModelsTrait;


class Person extends Database
{
    use ModelsTrait;

    private ?int $id = null;
    private string $createdAt;
    private ?string $updatedAt;
    protected ?string $fullName;
    protected ?string $pseudo;
    protected ?string $email;
    protected ?string $password;
    protected bool $optin;
    protected ?string $deletedAt;
    protected string $role = 'user';
    protected int $uvtr_media_id;
    // Foreign properties
    public Media $media;

    public function __construct() {
        parent::__construct();
        $this->media = new Media();
    }

    public function getId(): ?int {
        return $this->id;
    }

    public function getFullName(): ?string {
        return $this->fullName;
    }

    public function setFullName($fullName): void {
        $this->fullName = $fullName;
    }

    public function getPseudo(): ?string {
        return $this->pseudo;
    }

    public function setPseudo($pseudo): void {
        $this->pseudo = $pseudo;
    }

    public function getEmail(): ?string {
        return $this->email;
    }

    public function setEmail($email): void {
        $this->email = $email;
    }

    public function getPassword(): ?string {
        return $this->password;
    }

    public function setPassword($password): void {
        $this->password = $password;
    }

    public function getOptin(): bool {
        return $this->optin;
    }

    public function setOptin($optin): void {
        $this->optin = $optin;
    }

    public function getUpdatedAt(): ?string {
        return $this->updatedAt;
    }

    public function setUpdatedAt($updatedAt): void {
        $this->updatedAt = $updatedAt;
    }

    public function getCreatedAt(): string {
        return $this->createdAt;
    }

    public function getDeletedAt(): ?string {
        return $this->deletedAt;
    }

    public function setDeletedAt($deletedAt): void {
        $this->deletedAt = $deletedAt;
    }

    public function getRole(): string {
        return $this->role;
    }

    public function setRole(string $role): void {
        $this->role = $role;
    }

    public function getUvtrMediaId(): int {
        return $this->uvtr_media_id;
    }

    public function setUvtrMediaId($uvtr_media_id): void {
        $this->uvtr_media_id = $uvtr_media_id;
    }

    public function getMedia(): Media {
        if (!empty($this->uvtr_media_id) && is_numeric($this->uvtr_media_id))
            $this->media->setId($this->uvtr_media_id);
        return $this->media;
    }

    public function isLogged(): bool {
        if(isset($_SESSION['loggedIn']) && $_SESSION['loggedIn'] == true) {
            return true;
        } else {
            return false;
        }
    }

    public function setMedia(Media $media): void {
        $this->media = $media;
    }

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