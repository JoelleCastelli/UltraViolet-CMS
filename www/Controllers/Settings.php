<?php

namespace App\Controller;

use App\Core\FormBuilder;
use App\Core\FormValidator;
use App\Core\Helpers;
use App\Core\MediaManager;
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
        $view->assign('title', 'Paramètres utilisateur');
        $view->assign("form", $form);
        $view->assign("settings", Helpers::readConfigFile());

        // If form is submitted, check the data and save settings
        if(!empty($_POST)) {

            $errors = FormValidator::check($form, $_POST);
            if(empty($errors)) {
                $settings = $_POST;
                // Remove csrfToken from settings list before save loop
                unset($settings['csrfToken']);
                // Update .env file with new values from user
                foreach ($settings as $selector => $value) {
                    $dbSetting = new SettingsModel();
                    $dbSetting = $dbSetting->findOneBy('selector', $selector);
                    $dbSetting->setValue($value);
                    $dbSetting->save();
                }

                // Save logo
                if($_FILES['logo']['error'] != UPLOAD_ERR_NO_FILE) {
                    $_FILES['logo']["name"] = "logo.png";
                    $mediaManager = new MediaManager();
                    $errors = $mediaManager->check($_FILES['logo'], 'logo');
                    if(empty($errors)) {
                        $mediaManager->uploadFile($mediaManager->getFiles());
                    }
                    unset($_FILES['logo']);
                }
                // Save favicon
                if($_FILES['favicon']['error'] != UPLOAD_ERR_NO_FILE) {
                    $_FILES['favicon']["name"] = "favicon.ico";
                    $mediaManager = new MediaManager();
                    $errors = $mediaManager->check($_FILES['favicon'], 'logo');
                    if(empty($errors)) {
                        $mediaManager->uploadFile($mediaManager->getFiles());
                    }
                    unset($_FILES['favicon']);
                }
                // Success message
                Helpers::setFlashMessage('success', "Les paramètres ont été mis à jour");
                Helpers::namedRedirect('settings');
            }
            $view->assign("errors", $errors);
        }
    }

    public function formBuilderUpdateSettings(): array
    {
        $settings = new SettingsModel();
        return [
            "config" => [
                "method" => "POST",
                "action" => "",
                "class" => "form_control card",
                "id" => "formAddCategory",
                "submit" => "Valider",
                "referer" => Helpers::callRoute('settings'),
                "enctype" => "multipart/form-data"
            ],
            "fields" => [
                "appName" => [
                    "type" => "text",
                    "minLength" => 1,
                    "maxLength" => 160,
                    "label" => "Nom de l'application",
                    "class" => "search-bar",
                    "value" => $settings::getAppName(),
                    "error" => "Le nom l'application doit contenir entre 1 et 60 caractères",
                    "required" => true,
                ],
                "metaTitle" => [
                    "type" => "text",
                    "minLength" => 1,
                    "maxLength" => 155,
                    "label" => "Meta title par défaut",
                    "class" => "search-bar",
                    "value" => $settings::getMetaTitle(),
                    "error" => "La meta description ne peut pas dépasser 155 caractères",
                    "required" => true,
                ],
                "metaDescription" => [
                    "type" => "textarea",
                    "minLength" => 1,
                    "maxLength" => 160,
                    "label" => "Meta description par défaut",
                    "class" => "search-bar",
                    "value" => $settings::getMetaDescription(),
                    "error" => "La meta description ne peut pas dépasser 160 caractères",
                    "required" => true,
                ],
                "logo" => [
                    "type" => "file",
                    "accept" => ".jpg, .jpeg, .png",
                    "label" => "Logo de l'application",
                ],
                "favicon" => [
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