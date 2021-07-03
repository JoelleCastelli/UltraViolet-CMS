<?php

namespace App\Controller;

use App\Core\FormValidator;
use App\Core\Helpers;
use App\Core\View;
use App\Models\Settings as SettingsModel;

class Settings
{
    /**
     * Update settings
     */
    public function showAllAction()
    {
        $settings = new SettingsModel();
        $form = $settings->formBuilderUpdateSettings();
        $view = new View("settings/list");
        $view->assign('title', 'Paramètres');
        $view->assign("form", $form);

        // If form is submitted, check the data and save settings
        if(!empty($_POST)) {
            $errors = FormValidator::check($form, $_POST);
            if(empty($errors)) {
                $settingsList = $_POST;
                unset($settingsList['csrfToken']);      // remove csrfToken from settings list before save loop
                foreach ($settingsList as $name => $value) {
                    // Each entry of the form is a row in the Settings table
                    $setting = new SettingsModel();
                    $setting = $setting->findOneBy('name', $name);
                    if($setting == false) {
                        Helpers::dd($name);
                    }
                    $setting->setValue($value);
                    $setting->save();
                }
                Helpers::setFlashMessage('success', "Les paramètres ont été mis à jour");
                Helpers::redirect(Helpers::callRoute('settings'));
            }
            $view->assign("errors", $errors);
        }
    }

}