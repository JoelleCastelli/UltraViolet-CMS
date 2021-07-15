<?php

namespace App\Controller;

use App\Core\FormValidator;
use App\Core\Helpers;
use App\Core\View;
use App\Models\TemplateVariable as TemplateVariableModel;

class TemplateVariable {

    public function showAllAction() {
        $templateVariable = new TemplateVariableModel();
        $form = $templateVariable->formBuilderUpdateTemplateVariables();
        $view = new View("templates/list");
        $view->assign('title', 'Templates');
        $view->assign("form", $form);

        if(!empty($_POST)) {
            $errors = FormValidator::check($form, $_POST);
            if(empty($errors)) {
                $variables = $_POST;
                // Remove csrfToken from settings list before save loop
                unset($variables['csrfToken']);

                // Save new values in database
                foreach ($variables as $selector => $value) {
                    $variable = new TemplateVariableModel();
                    $variable = $variable->select()->where('selector', $selector)->first();
                    $variable->setValue($value);
                    $variable->save();
                }

                // Write new values on CSS file
                $newVariables = new TemplateVariableModel();
                $newVariables = $newVariables->findAll();
                $cssString = '';
                foreach ($newVariables as $variable) {
                    if(strpos($variable->getSelector(), 'Background'))
                        $cssString .= '.'.$variable->getSelector()." { background-color: ".$variable->getValue()."; }\n";
                    else if(strpos($variable->getSelector(), 'Font'))
                        $cssString .= '.'.$variable->getSelector()." { font-size: ".$variable->getValue()."; }\n";
                    else if(strpos($variable->getSelector(), 'Color'))
                        $cssString .= '.'.$variable->getSelector()." { color: ".$variable->getValue()."; }\n";
                    else if(strpos($variable->getSelector(), 'Height'))
                        $cssString .= '.'.$variable->getSelector()." { line-height: ".$variable->getValue()."; }\n";
                    else if(strpos($variable->getSelector(), 'Family'))
                        $cssString .= '.'.$variable->getSelector()." { font-family: ".$variable->getValue().", sans-serif; }\n";
                    else if(strpos($variable->getSelector(), 'Size'))
                        $cssString .= '.'.$variable->getSelector()." { font-size: ".$variable->getValue()."px; }\n";
                }
                file_put_contents(getcwd().'/src/css/variables.css', $cssString);

                Helpers::setFlashMessage('success', "Les modifications ont été sauvegardées");
                Helpers::namedRedirect('templates_lists');
            }
            $view->assign("errors", $errors);
        }
    }

}