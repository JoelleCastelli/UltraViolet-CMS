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
                // Remove csrfToken from settings list before save loop
                unset($settingsList['csrfToken']);
                // Get current config file (before update)
                $settings = $this->readConfigFile();
                foreach ($settingsList as $name => $value) {
                    // Replace current value by new value and write back in file
                    $currentSettingValue = "$name=$settings[$name]";
                    $newSettingValue = "$name=$value";
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

    /**
     * Read config file and store data into array
     * Example: $settings['APP_NAME'] = 'MyApp'
     */
    public static function readConfigFile(): array
    {
        $settings = [];
        $config = file('.env.dev', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($config as $setting) {
            if(substr($setting, 0, 1) !== '#') {
                $pieces = explode("=", $setting);
                $settings[$pieces[0]] = htmlspecialchars($pieces[1]);
            }
        }
        return $settings;
    }

    public static function writeConfigFile($settings): array
    {
        $settings = [];
        $config = file('.env.dev', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($config as $setting) {
            if(substr($setting, 0, 1) !== '#') {
                $pieces = explode("=", $setting);
                $settings[$pieces[0]] = htmlspecialchars($pieces[1]);
            }
        }
        return $settings;
    }

}