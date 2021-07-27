<?php

namespace App\Controller;

use App\Core\FormValidator;
use App\Core\Helpers;
use App\Core\View;
use App\Models\Settings;

class Templates {

    public function showAllAction() {
        $templateVariable = new Settings();
        $form = $templateVariable->formBuilderUpdateTemplateVariables();
        $view = new View("templates/list");
        $view->assign('title', 'Apparence');
        $view->assign('bodyScripts', [PATH_TO_SCRIPTS.'bodyScripts/templates.js']);
        $view->assign("form", $form);

        if(!empty($_POST)) {
            $errors = FormValidator::check($form, $_POST);
            if(empty($errors)) {
                $variables = $_POST;
                // Remove csrfToken from settings list before save loop
                unset($variables['csrfToken']);
                // Save new values in database
                foreach ($variables as $selector => $value) {
                    $variable = new Settings();
                    $variable = $variable->select()->where('selector', $selector)->first();
                    $variable->setValue($value);
                    $variable->save();
                }
                // Write new values on CSS file
                $this->writeCssFile();

                Helpers::setFlashMessage('success', "Les modifications ont été sauvegardées");
                Helpers::namedRedirect('templates_lists');
            }
            $view->assign("errors", $errors);
        }
    }

    public function writeCssFile() {
        $newVariables = new Settings();
        $newVariables = $newVariables->findAll();
        $cssString = '';
        foreach ($newVariables as $variable) {
            if(strpos($variable->getSelector(), 'Background')) {
                $cssString .= '.'.$variable->getSelector()." { background-color: ".$variable->getValue()."; }\n";
                // Comment button sign
                if($variable->getSelector() == "tagsBackground") {
                    $cssString .= ".add-btn::before { background: " . $variable->getValue() . "; }\n";
                    $cssString .= ".add-btn::after { background: " . $variable->getValue() . "; }\n";
                }
            } else if(strpos($variable->getSelector(), 'Color')) {
                // Dropdown menu
                if($variable->getSelector() == 'navbarColor')
                    $cssString .= ".dropdown .dropdown-content > a:hover { transition: 0.2s; z-index: 40; color: " . $variable->getValue() . "; }\n";
                if($variable->getSelector() == 'navbarColorHover')
                    $cssString .= ".dropdown .dropdown-content > a:hover { background-color: " . $variable->getValue() . "; }\n";

                // Link colors (interface + articles)
                if($variable->getSelector() == 'linksColor')
                    $cssString .= ".article.card article a { color: ".$variable->getValue()." }\n";

                if(strpos($variable->getSelector(), 'Hover')) {
                    $cssString .= '.'.$variable->getSelector().":hover { color: ".$variable->getValue()." }\n";
                } else {
                    $cssString .= '.'.$variable->getSelector()." { color: ".$variable->getValue()."; }\n";
                }
            } else if(strpos($variable->getSelector(), 'Family')) {
                $cssString .= '.'.$variable->getSelector()." { font-family: ".$variable->getValue().", sans-serif; }\n";
            }
        }
        file_put_contents(getcwd().'/src/css/variables.css', $cssString);
    }

    public function restoreAction() {
        $variables = new Settings();
        $variables = $variables->findAll();
        foreach ($variables as $variable) {
            if(!in_array($variable->getSelector(), ['appName', 'metaTitle', 'metaDescription'])) {
                $variable->setValue($variable->getDefaultValue());
                $variable->save();
            }
        }
        $this->writeCssFile();
        Helpers::setFlashMessage('success', "Les valeurs par défaut ont bien été appliquées");
    }

}