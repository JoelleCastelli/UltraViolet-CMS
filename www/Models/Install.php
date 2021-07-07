<?php

namespace App\Models;

use App\Core\FormBuilder;
use App\Core\Helpers;

class Install
{

    public function __construct()
    {

    }

    public function formBuilderInstallDB(): array
    {
        $settings = Helpers::readConfigFile();
        if($settings) {
            return [
                "config" => [
                    "method" => "POST",
                    "action" => "",
                    "class" => "form_control",
                    "id" => "formBuilderInstallDB",
                    "submit" => "Valider",
                    "referer" => Helpers::callRoute('configStep2')
                ],
                "fields" => [
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
                    "DBUSER" => [
                        "type" => "text",
                        "minLength" => 1,
                        "maxLength" => 16,
                        "label" => "Identifiant",
                        "class" => "search-bar",
                        "value" => $settings['DBUSER'],
                        "error" => "Le nom d'utilisateur de la base de données doit contenir entre 1 et 16 caractères",
                        "required" => true,
                    ],
                    "DBPWD" => [
                        "type" => "password",
                        "minLength" => 0,
                        "maxLength" => 32,
                        "label" => "Mot de passe",
                        "class" => "search-bar",
                        "value" => $settings['DBPWD'],
                        "error" => "Le mot de passe ne peut pas dépasser 32 caractères",
                    ],
                    "DBPREFIXE" => [
                        "type" => "text",
                        "minLength" => 0,
                        "maxLength" => 6,
                        "label" => "Mot de passe",
                        "class" => "search-bar",
                        "value" => $settings['DBPREFIXE'],
                        "error" => "Le préfixe doit contenir entre 1 et 6 caractères",
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