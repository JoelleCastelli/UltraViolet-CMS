<?php

namespace App\Controller;

use App\Core\FormValidator;
use App\Core\Helpers;
use App\Core\View;
use App\Models\Settings as SettingsModel;

class Settings
{

    /**
     * Create config at installation
     */
    public function installationAction() {
        /*$str = file_get_contents('.env.dev');
        $str = str_replace("INSTALLING=true", "INSTALLING=false", $str);
        file_put_contents('.env.dev', $str);*/
        $settings = new SettingsModel();
        $form = $settings->formBuilderInstallation();
        $view = new View("config/step1");
        $view->assign('title', 'Installation');
        $view->assign("form", $form);
    }

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
                // Remove csrfToken from settings list before save loop
                unset($settingsList['csrfToken']);
                // Get current config file (before update)
                $settings = $settings->readConfigFile();
                foreach ($settingsList as $name => $value) {
                    // Replace current value by new value and write back in file
                    $currentSettingValue = "$name=$settings[$name]";
                    $newSettingValue = "$name=$value";
                    /*$currentSettingValue = $name.'='.$settings[$name];
                    $newSettingValue = (strpos($value, ' ') && !strpos($value, '"')) ? $name.'="'.$value.'"' : $name.'='.$value;*/
                    $str = file_get_contents('.env.dev');
                    $str = str_replace($currentSettingValue, $newSettingValue, $str);
                    file_put_contents('.env.dev', $str);
                }
                Helpers::setFlashMessage('success', "Les paramètres ont été mis à jour");
                Helpers::redirect(Helpers::callRoute('settings'));
            }
            $view->assign("errors", $errors);
        }
    }

}