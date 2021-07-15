<?php

namespace App\Controller;

use App\Core\FormBuilder;
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
        $form = $this->formBuilderUpdateSettings();
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
                // Save logo
                if(!empty($_FILES['APP_LOGO'])) {
                    $_FILES['APP_LOGO']["name"] = "logo.png";
                    $mediaManager = new MediaManager();
                    $errors = $mediaManager->check($_FILES['APP_LOGO'], 'logo');
                    if(empty($errors)) {
                        $mediaManager->uploadFile($mediaManager->getFiles());
                    }
                }
                // Save favicon
                if(!empty($_FILES['APP_FAVICON'])) {
                    $_FILES['APP_FAVICON']["name"] = "favicon.ico";
                    $mediaManager = new MediaManager();
                    $errors = $mediaManager->check($_FILES['APP_FAVICON'], 'logo');
                    if(empty($errors)) {
                        $mediaManager->uploadFile($mediaManager->getFiles());
                    }
                }
                // Success message
                Helpers::setFlashMessage('success', "Les paramètres ont été mis à jour");
            }
            $view->assign("errors", $errors);
        }
    }

    public function formBuilderUpdateSettings(): array
    {
        $settings = Helpers::readConfigFile();
        if($settings) {
            return [
                "config" => [
                    "method" => "POST",
                    "action" => "",
                    "class" => "form_control card",
                    "id" => "formAddCategory",
                    "submit" => "Valider",
                    "referer" => Helpers::callRoute('settings')
                ],
                "fields" => [
                    "APP_NAME" => [
                        "type" => "text",
                        "minLength" => 1,
                        "maxLength" => 60,
                        "label" => "Nom de l'application",
                        "class" => "search-bar",
                        "value" => $settings['APP_NAME'],
                        "error" => "Le nom l'application doit contenir entre 1 et 60 caractères",
                        "required" => true,
                    ],
                    "META_TITLE" => [
                        "type" => "text",
                        "minLength" => 1,
                        "maxLength" => 155,
                        "label" => "Meta title par défaut",
                        "class" => "search-bar",
                        "value" => $settings['META_TITLE'],
                        "error" => "La meta description ne peut pas dépasser 155 caractères",
                    ],
                    "META_DESC" => [
                        "type" => "textarea",
                        "minLength" => 1,
                        "maxLength" => 160,
                        "label" => "Meta description par défaut",
                        "class" => "search-bar",
                        "value" => $settings['META_DESC'],
                        "error" => "La meta description ne peut pas dépasser 160 caractères",
                    ],
                    "APP_LOGO" => [
                        "type" => "file",
                        "accept" => ".jpg, .jpeg, .png",
                        "label" => "Logo de l'application",
                    ],
                    "APP_FAVICON" => [
                        "type" => "file",
                        "accept" => ".ico",
                        "label" => "Favicon de l'application",
                    ],
                    "csrfToken" => [
                        "type"=>"hidden",
                        "value"=> FormBuilder::generateCSRFToken(),
                    ]
                ],
            ];
        }
    }

}