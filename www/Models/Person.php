<?php

namespace App\Models;

use App\Core\Database;
use App\Core\FormValidator;

class Person extends Database
{

	private $id = null;
    private $createdAt;
	protected $fullName;
	protected $pseudo;
	protected $email;
    protected $password;
    protected $optin;
    protected $updatedAt;
    protected $deletedAt;
    protected $role;
    protected $uvtr_media_id;

	public function __construct(){
		parent::__construct();
	}

    /**
     * @return null
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param null $id
     */
    public function setId($id): void
    {
        $this->id = $id;
        $this->findOneById($this->id); // populate object with all the others values
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
     * @param mixed $createdAt
     */
    public function setCreatedAt($createdAt): void
    {
        $this->createdAt = $createdAt;
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

	public function formBuilderLogin(){
		return [
			"config"=>[
				"method"=>"POST",
				"action"=>"",
				"class"=>"form_control",
				"id"=>"form_register",
				"submit"=>"S'inscrire"
			],
			"fields"=>[
				"email"=>[
				    "type"=>"email",
                    "placeholder"=>"Exemple : nom@gmail.com",
                    "label"=>"Votre Email",
                    "required"=>true,
                    "class"=>"form_input",
                    "minLength"=>8,
                    "maxLength"=>320,
                    "error"=>"Votre email doit faire entre 8 et 320 caractères"
                ],
				"pwd"=>[
				    "type"=>"password",
                    "label"=>"Votre mot de passe",
                    "required"=>true,
                    "class"=>"form_input",
                    "minLength"=>8,
                    "error"=>"Votre mot de passe doit faire au minimum 8 caractères"
                ]
			]

		];
	}

	public function formBuilderRegister(){

		return [
			"config"=>[
				"method"=>"POST",
				"action"=>"",
				"class"=>"form_control",
				"id"=>"form_register",
				"submit"=>"S'inscrire",
                "required_inputs"=>8,
                "referer" => '/inscription'
			],
            "fields" => [
                "fullName" => [
                    "type"=>"text",
                    "placeholder"=>"Prénom et nom",
                    "minLength"=>2,
                    "maxLength"=>50,
                    "error"=>"Votre prénom doit faire entre 2 et 50 caractères et écrit correctement",
                    //"regex" => "/^[a-zA-ZàáâäãåąčćęèéêëėįìíîïłńòóôöõøùúûüųūÿýżźñçčšžÀÁÂÄÃÅĄĆČĖĘÈÉÊËÌÍÎÏĮŁŃÒÓÔÖÕØÙÚÛÜŲŪŸÝŻŹÑßÇŒÆČŠŽ∂ð ,.'-]+$/u",
                    "required" => true,
                    "firstTagOpen" =>"<div class='search-bar'>",
                    "firstTagClose" =>"<i class='fas fa-user'></i></div>"
                ],
                "pseudo"=>[
                    "type"=>"text",
                    "placeholder"=>"Pseudo",
                    "minLength"=>2,
                    "maxLength"=>25,
                    "error"=>"Votre pseudo doit faire entre 2 et 25 caractères",
                    "required" => true,
                    "firstTagOpen" =>"<div class='search-bar'>",
                    "firstTagClose" =>"<i class='fas fa-user'></i></div>"

                ],
                "email"=>[
                    "type"=>"email",
                    "placeholder"=>"Email",
                    "class"=>"",
                    "minLength"=>8,
                    "maxLength"=>130,
                    "error"=>"Votre email doit faire entre 8 et 130 caractères",
                    "required" => true,
                    "firstTagOpen" =>"<div class='search-bar'>",
                    "firstTagClose" =>"<i class='fas fa-envelope'></i></div>"

                ],
                "pwd"=>[
                    "type"=>"password",
                    "placeholder"=>"Mot de passe",
                    "minLength"=>8,
                    "regex"=> "/(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&-])[A-Za-z\d@$!%*?&-]{8,}$/",
                    "error"=>"Votre mot de passe doit faire au minimum 8 caractères, comporté au moins une lettre minusucule et majuscule, un chiffre et une lettre spéciale.",
                    "required" => true,
                    "firstTagOpen" =>"<div class='search-bar'>",
                    "firstTagClose" =>"<i class='fas fa-lock'></i></div>"

                ],
                "pwdConfirm"=> [
                    "type"=>"password",
                    "placeholder" => "Confirmation du mot de passe",
                    "confirm"=>"pwd",
                    "error"=>"Votre mot de passe de confirmation ne correspond pas",
                    "required" => true,
                    "firstTagOpen" =>"<div class='search-bar'>",
                    "firstTagClose" =>"<i class='fas fa-lock'></i></div>"
                ],
                "role"=>[
                    "type" => "select",
                    "required"=> true,
                    "options" => [

                        "default" => [
                            "value"=>"",
                            "text"=>"Choisissez un rôle...",
                            "disabled"=>true,
                            "selected"=>true,
                            "class"=>"",
                        ],
                        "user" => [
                            "text" => "Utilisateur",
                            "value" =>"user"
                        ],
                        "editor" => [
                            "text" => "Editeur",
                            "value" =>"editor"
                        ],
                        "admin" => [
                            "text" => "Administrateur",
                            "value" =>"admin"
                        ],
                        "vip" => [
                            "text" => "V.I.P",
                            "value" =>"vip"
                        ]
                    ]
                ],
                "optin"=>[
                    "type" => "checkbox",
                    "options" => [
                        "optin" => [
                            "error" => "",
                            "id" => "optin",
                            "value"=>1,
                            "required" => true,
                            "text" => "Accepter la newsletters."
                        ]
                    ]
                ],
                "rules"=>[
                    "type" => "checkbox",
                    "options" => [
                        "rules" => [
                            "error" => "Vous devez accepter le reglement.",
                            "id" => "rules",
                            "value"=>"rule",
                            "required" => true,
                            "text" => "Veuillez lire puis accepter le reglement."
                        ]
                    ]
                ]

            ]
           /* "checkboxes"=>[
                "rules"=> [
                    "error" => "Vous devez accepter le reglement.",
                    "label" => "",
                    "options"=>[
                        [
                            "id" => "rules",
                            "value"=>"rule",
                            "label"=>"Veuillez lire puis accepter le reglement.",
                            "required" => true
                        ],
                    ]
                ],
                "optin"=> [
                    "error" => "Oops ! Un problème se trouve avec votre Optin.",
                    "label" => "",
                    "options"=>[
                        [
                            "id" => "optin",
                            "value"=>1,
                            "label"=>"Acceptez les newsletters."
                        ],

                    ]
                ]
            ],
            "inputs"=>[
                "fullName" => [
                    "type"=>"text",
                    "placeholder"=>"Prénom et nom",
                    "minLength"=>2,
                    "maxLength"=>50,
                    "error"=>"Votre prénom doit faire entre 2 et 50 caractères et écrit correctement",
                    "required" => true
                ],
                "pseudo"=>[
                    "type"=>"text",
                    "placeholder"=>"Pseudo",
                    "minLength"=>2,
                    "maxLength"=>25,
                    "error"=>"Votre pseudo doit faire entre 2 et 25 caractères"

                ],
                "email"=>[
                    "type"=>"email",
                    "placeholder"=>"Email",
                    "class"=>"",
                    "minLength"=>8,
                    "maxLength"=>130,
                    "error"=>"Votre email doit faire entre 8 et 130 caractères",
                    "required" => true,
                    "firstTagOpen" =>"<div class='search-bar'>",
                    "firstTagClose" =>"<i class='fas fa-envelope'></i></div>"

                ],
                "genre"=>[
                    "type"=>"radio",
                    "label"=>"Test",
                    "class"=>"",
                    "error"=>"Erreur test",
                    "required" => true,
                    "options" => [
                        [
                            "value"=>"movie",
                            "text"=>"Film",
                        ],
                        [
                            "value"=>"series",
                            "text"=>"Série",
                        ]
                    ],

                ],
                "pwd"=>[
                    "type"=>"password",
                    "placeholder"=>"Votre email",
                    "minLength"=>8,
                    "regex"=> "/(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&-])[A-Za-z\d@$!%*?&-]{8,}$/",
                    "error"=>"Votre mot de passe doit faire au minimum 8 caractères, comporté au moins une lettre minusucule et majuscule, un chiffre et une lettre spéciale.",
                    "required" => true,
                    "firstTagClose" =>"<i class='fas fa-lock'></i></div>"

                    "required" => true
                ],
                "pwdConfirm"=> [
                    "type"=>"password",
                    "placeholder" => "Confirmation du mot de passe",
                    "confirm"=>"pwd",
                    "error"=>"Votre mot de passe de confirmation ne correspond pas",
                    "required" => true,
                    "firstTagOpen" =>"<div class='search-bar'>",
                    "firstTagClose" =>"<i class='fas fa-lock'></i></div>"

                ],
                "csrf_token" => [
                    "type"=>"hidden",
                    "value"=> FormValidator::generateCSRFToken(),
                ]
            ]
		];
	}
}
