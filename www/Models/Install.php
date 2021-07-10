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
                        "label" => "Préfixe des tables",
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

    public function formBuilderCreateAdminUser(): array
    {
        return [
            "config" => [
                "method" => "POST",
                "action" => "",
                "class" => "form_control",
                "id" => "formBuilderInstallDB",
                "submit" => "Valider",
                "referer" => Helpers::callRoute('configStep5')
            ],
            "fields" => [
                "APP_NAME" => [
                    "type" => "text",
                    "minLength" => 1,
                    "maxLength" => 50,
                    "label" => "Titre du site",
                    "class" => "search-bar",
                    "error" => "Le titre du site doit contenir entre 1 et 50 caractères",
                    "required" => true,
                ],
                "pseudo" => [
                    "type" => "text",
                    "minLength" => 1,
                    "maxLength" => 25,
                    "label" => "Pseudonyme",
                    "class" => "search-bar",
                    "error" => "Le nom d'utilisateur de la base de données doit contenir entre 1 et 16 caractères",
                    "required" => true,
                ],
                "email" => [
                    "type" => "text",
                    "minLength" => 0,
                    "maxLength" => 130,
                    "label" => "Adresse email",
                    "class" => "search-bar",
                    "error" => "Votre adresse email doit comporter entre 8 et 130 caractères",
                    "required" => true,
                ],
                "password" => [
                    "type" => "password",
                    "minLength" => 8,
                    "maxLength" => 255,
                    "label" => "Mot de passe",
                    "class" => "search-bar",
                    "regex" => "/(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&-])[A-Za-z\d@$!%*?&-]{8,}$/",
                    "error" => "Votre mot de passe comporter au minimum 8 caractères dont au moins une lettre minuscule, une majuscule, un chiffre et un caractère spécial",
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