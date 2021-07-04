<?php

namespace App\Models;

use App\Core\Database;
use App\Core\FormBuilder;
use App\Core\Helpers;
use App\Controller\Settings as SettingsController;

class Settings extends Database
{
    private $id = null;
    protected string $name;
    protected string $value;

    public function __construct()
    {
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
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getValue(): string
    {
        return $this->value;
    }

    /**
     * @param string $value
     */
    public function setValue(string $value): void
    {
        $this->value = $value;
    }

    /**
     * Form to update a category
     */
    public function formBuilderUpdateSettings(): array
    {
        $settings = SettingsController::readConfigFile();
        if($settings) {
            return [
                "config" => [
                    "method" => "POST",
                    "action" => "",
                    "class" => "form_control card",
                    "id" => "formAddCategory",
                    "submit" => "Valider",
                    "referer" => Helpers::callRoute('settings')
                ],
                "fields" => [
                    "APP_NAME" => [
                        "type" => "text",
                        "minLength" => 1,
                        "maxLength" => 60,
                        "label" => "Nom de l'application",
                        "class" => "search-bar",
                        "value" => $settings['APP_NAME'],
                        "error" => "Le nom l'application doit contenir entre 1 et 60 caractères",
                        "required" => true,
                    ],
                    "DBNAME" => [
                        "type" => "text",
                        "minLength" => 1,
                        "maxLength" => 64,
                        "label" => "Nom de la base de données",
                        "class" => "search-bar",
                        "value" => $settings['DBNAME'],
                        "error" => "Le nom la base de données doit contenir entre 1 et 64 caractères",
                        "required" => true,
                    ],
                    "DBHOST" => [
                        "type" => "text",
                        "minLength" => 1,
                        "maxLength" => 60,
                        "label" => "Hôte",
                        "class" => "search-bar",
                        "value" => $settings['DBHOST'],
                        "error" => "Le nom de l'hôte doit contenir entre 1 et 60 caractères",
                        "required" => true,
                    ],
                    "DBPORT" => [
                        "type" => "text",
                        "minLength" => 1,
                        "maxLength" => 5,
                        "label" => "Port",
                        "class" => "search-bar",
                        "value" => $settings['DBPORT'],
                        "error" => "Le port doit contenir entre 1 et 5 caractères",
                        "required" => true,
                    ],
                    "DBUSER" => [
                        "type" => "text",
                        "minLength" => 1,
                        "maxLength" => 16,
                        "label" => "Utilisateur",
                        "class" => "search-bar",
                        "value" => $settings['DBUSER'],
                        "error" => "Le nom d'utilisateur de la base de données doit contenir entre 1 et 16 caractères",
                        "required" => true,
                    ],
                    "DBDRIVER" => [
                        "type" => "text",
                        "minLength" => 1,
                        "maxLength" => 20,
                        "label" => "Driver",
                        "class" => "search-bar",
                        "value" => $settings['DBDRIVER'],
                        "error" => "Le nom du driver doit contenir entre 1 et 20 caractères",
                        "required" => true,
                    ],
                    "DBPWD" => [
                        "type" => "text",
                        "minLength" => 0,
                        "maxLength" => 32,
                        "label" => "Mot de passe",
                        "class" => "search-bar",
                        "value" => $settings['DBPWD'],
                        "error" => "Le mot de passe ne peut pas dépasser 32 caractères",
                    ],
                    "TMDB_API_KEY" => [
                        "type" => "text",
                        "minLength" => 1,
                        "maxLength" => 60,
                        "label" => "Clé API TMDB",
                        "class" => "search-bar",
                        "value" => $settings['TMDB_API_KEY'],
                        "error" => "La clé API TMDB doit contenir entre 1 et 60 caractères",
                        "required" => true,
                    ],
                    "TINYMCE_API_KEY" => [
                        "type" => "text",
                        "minLength" => 1,
                        "maxLength" => 60,
                        "label" => "Clé API TinyMCE",
                        "class" => "search-bar",
                        "value" => $settings['TINYMCE_API_KEY'],
                        "error" => "La clé API TinyMCE doit contenir entre 1 et 60 caractères",
                        "required" => true,
                    ],
                    "csrfToken" => [
                        "type"=>"hidden",
                        "value"=> FormBuilder::generateCSRFToken(),
                    ]
                ],
            ];
        }
    }

}