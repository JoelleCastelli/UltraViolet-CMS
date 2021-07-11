<?php

namespace App\Controller;

use App\Core\FormValidator;
use App\Core\Helpers;
use App\Core\View;
use App\Models\Settings as SettingsModel;

class Settings
{

    /**
     * Check and update settings
     */
    public function showAllAction()
    {
        $settings = new SettingsModel();
        $form = $settings->formBuilderUpdateSettings();
        $view = new View("settings/list");
        $view->assign('title', 'Paramètres');
        $view->assign("form", $form);
        $view->assign("settings", Helpers::readConfigFile());

        // If form is submitted, check the data and save settings
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
                // Success message
                Helpers::setFlashMessage('success', "Les paramètres ont été mis à jour");
            }
            $view->assign("errors", $errors);
        }
    }

}