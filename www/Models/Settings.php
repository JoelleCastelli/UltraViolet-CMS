<?php

namespace App\Models;

use App\Core\Database;
use App\Core\FormBuilder;
use App\Core\Helpers;

class Settings extends Database
{
    private ?int $id = null;
    protected string $selector;
    protected string $value;
    protected string $defaultValue;

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param int|null $id
     */
    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getSelector(): string
    {
        return $this->selector;
    }

    /**
     * @param string $selector
     */
    public function setSelector(string $selector): void
    {
        $this->selector = $selector;
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
     * @return string
     */
    public function getDefaultValue(): string
    {
        return $this->defaultValue;
    }

    /**
     * @param string $defaultValue
     */
    public function setDefaultValue(string $defaultValue): void
    {
        $this->defaultValue = $defaultValue;
    }

    public function getCurrentVariables(): array
    {
        $variables = new Settings();
        $variables = $variables->findAll();
        $variablesArray = [];
        foreach ($variables as $variable) {
            $variablesArray[$variable->getSelector()] = $variable->getValue();
        }
        return $variablesArray;
    }


    /**
     * Form to read and update the template variables
     */
    public function formBuilderUpdateTemplateVariables(): array
    {
        $currentVariables = $this->getCurrentVariables();

        return [
            "config" => [
                "method" => "POST",
                "action" => "",
                "class" => "form_control card",
                "id" => "formAddCategory",
                "submit" => "Valider",
                "referer" => Helpers::callRoute('templates_lists')
            ],
            "fields" => [
                "navbarBackground" => [
                    "type" => "color",
                    "label" => "Couleur du menu",
                    "value" => $currentVariables['navbarBackground'],
                    "error" => "Le code couleur du menu n'est pas valable",
                    "required" => true,
                ],
                "navbarColor" => [
                    "type" => "color",
                    "label" => "Couleur des titres du menu",
                    "value" => $currentVariables['navbarColor'],
                    "error" => "Le code couleur des titres du menu n'est pas valable",
                    "required" => true,
                ],
                "footerBackground" => [
                    "type" => "color",
                    "label" => "Couleur du footer",
                    "value" => $currentVariables['footerBackground'],
                    "error" => "Le code couleur du footer n'est pas valable",
                    "required" => true,
                ],
                "footerColor" => [
                    "type" => "color",
                    "label" => "Couleur du texte du footer",
                    "value" => $currentVariables['footerColor'],
                    "error" => "Le code couleur des titres du footer n'est pas valable",
                    "required" => true,
                ],
                "titleColor" => [
                    "type" => "color",
                    "label" => "Couleur des titres",
                    "value" => $currentVariables['titleColor'],
                    "error" => "Le code couleur des titres n'est pas valable",
                    "required" => true,
                ],
                "tagsBackground" => [
                    "type" => "color",
                    "label" => "Couleur de fond des tags",
                    "min" => "0.1",
                    "value" => $currentVariables['tagsBackground'],
                    "error" => "La hauteur de ligne ne peut pas être intéfieure à 0.1",
                    "required" => true,
                ],
                "tagsColor" => [
                    "type" => "color",
                    "label" => "Couleur du texte des tags",
                    "min" => "0.1",
                    "value" => $currentVariables['tagsColor'],
                    "error" => "La hauteur de ligne ne peut pas être intéfieure à 0.1",
                    "required" => true,
                ],
                "fontFamily" => [
                    "type" => "select",
                    "label" => "Police",
                    "options" => [
                        [
                            "value" => "mulish",
                            "text" => "Mulish",
                            "selected" => $currentVariables['fontFamily'] == 'mulish',
                        ],
                        [
                            "value" => "comic",
                            "text" => "Comic Sans MS",
                            "selected" => $currentVariables['fontFamily'] == 'comic',
                        ],
                        [
                            "value" => "arial",
                            "text" => "Arial",
                            "selected" => $currentVariables['fontFamily'] == 'arial',
                        ],
                        [
                            "value" => "tahoma",
                            "text" => "Tahoma",
                            "selected" => $currentVariables['fontFamily'] == 'tahoma',
                        ],
                        [
                            "value" => "poppins",
                            "text" => "Poppins",
                            "selected" => $currentVariables['fontFamily'] == 'poppins',
                        ]
                    ],
                    "error" => "La police choisie n'existe pas",
                    "required" => true,
                ],
                "customHeight" => [
                    "type" => "number",
                    "label" => "Hauteur de ligne",
                    "class" => "search-bar",
                    "value" => $currentVariables['customHeight'],
                    "min" => "0",
                    "step" => "0.1",
                    "error" => "La hauteur de ligne ne peut pas être inférieure à 0.1",
                    "required" => true,
                ],
                "customSize" => [
                    "type" => "number",
                    "label" => "Taille de la police",
                    "class" => "search-bar",
                    "value" => $currentVariables['customSize'],
                    "min" => "5",
                    "error" => "La taille de la police ne peut pas être inférieure à 5",
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