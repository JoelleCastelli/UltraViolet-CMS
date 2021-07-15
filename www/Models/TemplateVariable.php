<?php

namespace App\Models;

use App\Core\Database;
use App\Core\FormBuilder;
use App\Core\Helpers;

class TemplateVariable extends Database
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
        $variables = new TemplateVariable();
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
                "#navbar-front" => [
                    "type" => "color",
                    "label" => "Fond de la navbar",
                    "value" => $currentVariables['#navbar-front'],
                    "error" => "Ce code couleur n'est pas valable",
                    "required" => true,
                ],
                "#test" => [
                    "type" => "color",
                    "label" => "Fond de la page",
                    "error" => "Ce code couleur n'est pas valable",
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