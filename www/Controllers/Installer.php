<?php

namespace App\Controller;

use App\Core\FormValidator;
use App\Core\Helpers;
use App\Core\Database;
use App\Core\View;
use App\Models\Install;

class Installer
{
    public function install($route) {
        $action = $route->getAction();
        $this->$action();
    }

    public function step1Action() {
        $view = new View("config/step1");
    }

    public function step2Action() {
        $settings = new Install();
        $form = $settings->formBuilderInstallDB();
        $view = new View("config/step2");
        $view->assign("form", $form);

        if(!empty($_POST)) {
            $errors = FormValidator::check($form, $_POST);
            if(empty($errors)) {
                $settingsList = $_POST;
                // Remove csrfToken from settings list before save loop
                unset($settingsList['csrfToken']);
                // Update .env file with new values from user
                foreach ($settingsList as $name => $value) {
                    Helpers::updateConfigField($name, $value);
                }

                // Test connection
                // if ok: Helpers::redirect(Helpers::callRoute('configStep3'))
                // else
                // $errors[] = "Nous n'avons pas pu nous connecter à votre base de données. Veuillez vérifier vos informations";

                $errors[] = "Connexion incorrecte";
            }
            $view->assign("errors", $errors);
        }
    }

}