<?php

namespace App\Models;

use App\Core\Database;

class User extends Database
{

	private $id = null;
	protected $firstname;
	protected $lastname;
	protected $email;
	protected $pwd;
	protected $country;
	protected $role = 0;
	protected $status = 1;
	protected $isDeleted = 0;

	public function __construct(){
		parent::__construct();
	}

	/**
	 * @return mixed
	 */
	public function getId() {
	    return $this->id;
	}

	/**
	 * @param mixed $id
	 */
	public function setId($id): void {
	    $this->id = $id;
        $this->findOneById($this->id); // populate object with all the others values
    }

	/**
	 * @return mixed
	 */
	public function getFirstname() {
	    return $this->firstname;
	}

	/**
	 * @param mixed $firstname
	 */
	public function setFirstname($firstname): void {
	    $this->firstname = $firstname;
	}

	/**
	 * @return mixed
	 */
	public function getLastname() {
	    return $this->lastname;
	}

	/**
	 * @param mixed $lastname
	 */
	public function setLastname($lastname): void {
	    $this->lastname = $lastname;
	}

	/**
	 * @return mixed
	 */
	public function getEmail() {
	    return $this->email;
	}

	/**
	 * @param mixed $email
	 */
	public function setEmail($email): void {
	    $this->email = $email;
	}

	/**
	 * @return mixed
	 */
	public function getPwd() {
	    return $this->pwd;
	}

	/**
	 * @param mixed $pwd
	 */
	public function setPwd($pwd): void {
	    $this->pwd = $pwd;
	}

	/**
	 * @return mixed
	 */
	public function getCountry() {
	    return $this->country;
	}

	/**
	 * @param mixed $country
	 */
	public function setCountry($country): void {
	    $this->country = $country;
	}

	/**
	 * @return int
	 */
	public function getRole(): int {
	    return $this->role;
	}

	/**
	 * @param int $role
	 */
	public function setRole(int $role): void {
	    $this->role = $role;
	}

	/**
	 * @return int
	 */
	public function getStatus(): int {
	    return $this->status;
	}

	/**
	 * @param int $status
	 */
	public function setStatus(int $status): void {
	    $this->status = $status;
	}

	/**
	 * @return int
	 */
	public function getIsDeleted(): int {
	    return $this->isDeleted;
	}

	/**
	 * @param int $isDeleted
	 */
	public function setIsDeleted(int $isDeleted): void {
	    $this->isDeleted = $isDeleted;
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
				"submit"=>"S'inscrire"
			],
            "inputs"=>[
                "firstname" => [
                    "type"=>"text",
                    "placeholder"=>"Exemple : Yves",
                    "label"=>"Votre Prénom",
                    "class"=>"form_input",
                    "minLength"=>2,
                    "maxLength"=>50,
                    "error"=>"Votre prénom doit faire entre 2 et 50 caractères",
                    "required" => true

                ],
                "lastname"=>[
                    "type"=>"text",
                    "placeholder"=>"Exemple : Skrzypczyk",
                    "label"=>"Votre Nom",
                    "class"=>"form_input",
                    "minLength"=>2,
                    "maxLength"=>100,
                    "error"=>"Votre nom doit faire entre 2 et 100 caractères",
                    "required" => true

                ],
                "email"=>[
                    "type"=>"email",
                    "placeholder"=>"Exemple : nom@gmail.com",
                    "label"=>"Votre Email",
                    "class"=>"form_input",
                    "minLength"=>8,
                    "maxLength"=>320,
                    "error"=>"Votre email doit faire entre 8 et 320 caractères",
                    "required" => true

                ],
                "pwd"=>[
                    "type"=>"password",
                    "label"=>"Votre mot de passe",
                    "class"=>"form_input",
                    "minLength"=>8,
                    "error"=>"Votre mot de passe doit faire au minimum 8 caractères",
                    "required" => true

                ],
                "pwdConfirm"=> [
                    "type"=>"password",
                    "label"=>"Confirmation",
                    "class"=>"form_input",
                    "confirm"=>"pwd",
                    "error"=>"Votre mot de passe de confirmation ne correspond pas",
                    "required" => true

                ],
                "country"=>[
                    "type"=>"text",
                    "placeholder"=>"Exemple : fr",
                    "label"=>"Votre Pays",
                    "class"=>"form_input",
                    "minLength"=>2,
                    "maxLength"=>2,
                    "error"=>"Votre pays doit faire 2 caractères",
                    "required" => true

                ],
                "birthday"=>[
                    "type"=>"date",
                    "placeholder"=>"anniversaire",
                    "label"=>"Mon anniversaire",
                    "class"=>"form_input",
                    "min"=>"1970-11-04",
                    "max"=>"2005-11-04",
                    "error"=>"Votre anniversaire doit être entre 04-11-1970 et 04-11-2005",
                    "required" => true

                ],

            ],
            "selects"=>[
                "cars"=>[
                    "label"=>"Choisissez une voiture : ",
                    "class_label"=>"",
                    "class_select"=>"",
                    "error"=>"Choisir une voiture svp",
                    "options"=>[
                        [
                            "value"=>"",
                            "label"=>"Veuiller selectionner une voiture",
                            "disabled"=>"disabled",
                            "selected"=>"selected",
                            "class"=>"",
                        ],
                        [
                            "value"=>"volvo1",
                            "label"=>"Volvo",

                        ],
                        [
                            "value"=>"saab1",
                            "label"=>"Saab",
                        ],
                        [
                            "value"=>"mercedes1",
                            "label"=>"Mercedes",

                        ],
                        [
                            "value"=>"audi1",
                            "label"=>"Audi",

                        ],

                    ]
                ],
                "houses"=>[
                    "label"=>"Choisissez une maison : ",
                    "error"=>"Choisir une maison svp",
                    "options"=>[
                        [
                            "disabled"=>"disabled",
                            "selected"=>"selected",
                            "value"=>"",
                            "label"=>"Choisissez une maison ",
                        ],
                        [
                            "value"=>"tinyHouse1",
                            "label"=>"mini maison",
                        ],
                        [
                            "value"=>"bigHouse1",
                            "label"=>"grande maison",
                        ]
                    ]
                ],
            ],
            "radios"=>[
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
            ],
            "checkboxes"=>[
                "transports"=> [
                    "error" => "Choississez un transport siouplè !",
                    "label" => "Quel transport avez vous ? Choisissez en un ",
                    "class_fieldset" => "",
                    "class_legend" => "",
                    "id" => "",
                    "options"=>[
                        [

                            "id"=>"vehicle1",
                            "value"=>"Bike",
                            "label"=>"I have a bike",
                            "class_label" => "",
                            "class_input" => ""
                        ],
                        [
                            "id"=>"vehicle2",
                            "value"=>"Car",
                            "label"=>"I have a car",
                        ],
                        [
                            "id"=>"vehicle3",
                            "value"=>"Boat",
                            "label"=>"I have a boat",
                        ]
                    ]
                ],
                "rules"=> [
                    "error" => "Vous devez acceptez le reglement",
                    "label" => "Regles",
                    "options"=>[
                        [

                            "id"=>"rules",
                            "value"=>"rule",
                            "label"=>"Acceptez le reglement",
                            "required" => true
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