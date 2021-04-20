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
			"inputs"=>[
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
                "csrf" => FormValidator::getCSRFToken()
			],
            "inputs"=>[
                "fullName" => [
                    "type"=>"text",
                    "placeholder"=>"Exemple : Jeanne Dow",
                    "label"=>"Votre nom et prénom",
                    "class"=>"",
                    "minLength"=>2,
                    "maxLength"=>50,
                    "error"=>"Votre prénom doit faire entre 2 et 50 caractères et écrit correctement",
                    //"regex" => "/^[a-zA-ZàáâäãåąčćęèéêëėįìíîïłńòóôöõøùúûüųūÿýżźñçčšžÀÁÂÄÃÅĄĆČĖĘÈÉÊËÌÍÎÏĮŁŃÒÓÔÖÕØÙÚÛÜŲŪŸÝŻŹÑßÇŒÆČŠŽ∂ð ,.'-]+$/u",
                    "required" => true

                ],
                "pseudo"=>[
                    "type"=>"text",
                    "placeholder"=>"Exemple : jeanne-dow-du-77",
                    "label"=>"Votre Pseudo",
                    "class"=>"",
                    "minLength"=>2,
                    "maxLength"=>25,
                    "error"=>"Votre pseudo doit faire entre 2 et 25 caractères",
                    "required" => true

                ],
                "email"=>[
                    "type"=>"email",
                    "placeholder"=>"Exemple : mon_email@mail.com",
                    "label"=>"Votre Email",
                    "class"=>"",
                    "minLength"=>8,
                    "maxLength"=>130,
                    "error"=>"Votre email doit faire entre 8 et 130 caractères",
                    "required" => true

                ],
                "pwd"=>[
                    "type"=>"password",
                    "label"=>"Votre mot de passe",
                    "class"=>"",
                    "minLength"=>8,
                    "regex"=> "/(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&-])[A-Za-z\d@$!%*?&-]{8,}$/",
                    "error"=>"Votre mot de passe doit faire au minimum 8 caractères, comporté au moins une lettre minusucule et majuscule, un chiffre et une lettre spéciale.",
                    "required" => true

                ],
                "pwdConfirm"=> [
                    "type"=>"password",
                    "label"=>"Confirmation",
                    "class"=>"",
                    "confirm"=>"pwd",
                    "error"=>"Votre mot de passe de confirmation ne correspond pas",
                    "required" => true

                ]

            ],
            "selects"=>[
                "role"=>[
                    "label"=>"Définnissez votre rôle : ",
                    "class_label"=>"",
                    "class_select"=>"",
                    "error"=>"Veuillez choisir un rôle.",
                    "options"=>[
                        [
                            "value"=>"",
                            "label"=>"-- Choisissez un rôle --",
                            "disabled"=>"disabled",
                            "selected"=>"selected",
                            "class"=>"",
                        ],
                        [
                            "value"=>"user",
                            "label"=>"Utilisateur",

                        ],
                        [
                            "value"=>"admin",
                            "label"=>"Administrateur",
                        ],
                        [
                            "value"=>"editor",
                            "label"=>"Editeur",

                        ],
                        [
                            "value"=>"vip",
                            "label"=>"V.I.P",
                        ]
                    ]
                ]
            ],
            /*"radios"=>[
                "gender"=> [
                    "label" => "Votre genre ",
                    "class_fieldset" => "",
                    "class_legend" => "",
                    "error" => "Choisissez un genre siouplè !",
                    "options"=>[
                        [
                            "id"=>"male",
                            "value"=>"male",
                            "label"=>"Male",
                            "class_label" => "",
                            "class_input" => ""
                        ],
                        [
                            "id"=>"female",
                            "value"=>"female",
                            "label"=>"Female",
                        ],
                        [
                            "id"=>"other",
                            "value"=>"other",
                            "label"=>"Other",
                        ],
                        [
                            "id"=>"cactus",
                            "value"=>"cactus",
                            "label"=>"Je me sens cactus",
                        ]
                    ]
                ]
            ],*/
            "checkboxes"=>[
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
            ]
		];
	}
}

/*
 * CHECKBOXES
 *
 * Alone
     *   <div>
            <input type="checkbox" id="subscribeNews" name="subscribe" value="newsletter">
            <label for="subscribeNews">Souhaitez-vous vous abonner à la newsletter ?</label>
         </div>
        <div>
            <button type="submit">S'abonner</button>
        </div>
 *
 * Multiples
 *
     * <fieldset>
            <legend>Veuillez sélectionner vos intérêts :</legend>
            <div>
                <input type="checkbox" id="coding" name="interest" value="coding">
                <label for="coding">Développement</label>
            </div>
            <div>
                <input type="checkbox" id="music" name="interest" value="music">
                <label for="music">Musique</label>
            </div>
      </fieldset>
 * */