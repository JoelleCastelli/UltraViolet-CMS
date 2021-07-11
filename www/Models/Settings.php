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
     * Form to update the app settings
     */
    public function formBuilderUpdateSettings(): array
    {
        $settings = Helpers::readConfigFile();
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