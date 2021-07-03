<?php

namespace App\Models;

use App\Core\Database;
use App\Core\FormBuilder;
use App\Core\Helpers;

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
        $settings = new Settings();
        $settings = $settings->findAll();
        $settingsArray = [];
        foreach ($settings as $setting) {
            $settingsArray[$setting->name] = $setting->value;
        }
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
                    "appName" => [
                        "type" => "text",
                        "minLength" => 1,
                        "maxLength" => 60,
                        "label" => "Nom de l'application",
                        "class" => "search-bar",
                        "value" => $settingsArray['appName'],
                        "error" => "Le nom l'application doit contenir entre 1 et 60 caractères",
                        "required" => true,
                    ],
                    "dbName" => [
                        "type" => "text",
                        "minLength" => 1,
                        "maxLength" => 64,
                        "label" => "Nom de la base de données",
                        "class" => "search-bar",
                        "value" => $settingsArray['dbName'],
                        "error" => "Le nom la base de données doit contenir entre 1 et 64 caractères",
                        "required" => true,
                    ],
                    "dbHost" => [
                        "type" => "text",
                        "minLength" => 1,
                        "maxLength" => 60,
                        "label" => "Hôte",
                        "class" => "search-bar",
                        "value" => $settingsArray['dbHost'],
                        "error" => "Le nom de l'hôte doit contenir entre 1 et 60 caractères",
                        "required" => true,
                    ],
                    "dbPort" => [
                        "type" => "text",
                        "minLength" => 1,
                        "maxLength" => 5,
                        "label" => "Port",
                        "class" => "search-bar",
                        "value" => $settingsArray['dbPort'],
                        "error" => "Le port doit contenir entre 1 et 5 caractères",
                        "required" => true,
                    ],
                    "dbUser" => [
                        "type" => "text",
                        "minLength" => 1,
                        "maxLength" => 16,
                        "label" => "Utilisateur",
                        "class" => "search-bar",
                        "value" => $settingsArray['dbUser'],
                        "error" => "Le nom d'utilisateur de la base de données doit contenir entre 1 et 16 caractères",
                        "required" => true,
                    ],
                    "dbDriver" => [
                        "type" => "text",
                        "minLength" => 1,
                        "maxLength" => 20,
                        "label" => "Driver",
                        "class" => "search-bar",
                        "value" => $settingsArray['dbDriver'],
                        "error" => "Le nom du driver doit contenir entre 1 et 20 caractères",
                        "required" => true,
                    ],
                    "dbPwd" => [
                        "type" => "text",
                        "minLength" => 0,
                        "maxLength" => 32,
                        "label" => "Mot de passe",
                        "class" => "search-bar",
                        "value" => $settingsArray['dbPwd'],
                        "error" => "Le mot de passe ne peut pas dépasser 32 caractères",
                    ],
                    "tmdbApiKey" => [
                        "type" => "text",
                        "minLength" => 1,
                        "maxLength" => 60,
                        "label" => "Clé API TMDB",
                        "class" => "search-bar",
                        "value" => $settingsArray['tmdbApiKey'],
                        "error" => "La clé API TMDB doit contenir entre 1 et 60 caractères",
                        "required" => true,
                    ],
                    "tinyApiKey" => [
                        "type" => "text",
                        "minLength" => 1,
                        "maxLength" => 60,
                        "label" => "Clé API TinyMCE",
                        "class" => "search-bar",
                        "value" => $settingsArray['tinyApiKey'],
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