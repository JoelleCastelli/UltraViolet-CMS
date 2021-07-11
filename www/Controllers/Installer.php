<?php

namespace App\Controller;

use App\Core\FormValidator;
use App\Core\Helpers;
use App\Core\View;
use App\Models\Installer as InstallerModel;
use App\Models\Person;
use Exception;

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
        $settings = new InstallerModel();
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

                // Check if connection is successful
                try {
                    $db = new \PDO(DBDRIVER . ":host=" . DBHOST . ";dbname=" . DBNAME . ";port=" . DBPORT, DBUSER, DBPWD);
                } catch (Exception $e) {
                    $errors[] = "Nous n'avons pas pu nous connecter à votre base de données. Veuillez vérifier vos informations";
                }

                // Close DB connection
                $db = null;

                // If connection OK: next step
                if (empty($errors))
                    Helpers::redirect(Helpers::callRoute('configStep3'));

            }
            $view->assign("errors", $errors);
        }
    }

    public function step3Action() {
        $view = new View("config/step3");
    }

    public function step4Action() {
        // Get default SQL script
        $str = file_get_contents(getcwd().'/uv_database.sql');
        // Replace default "ultraviolet" database name by .env value
        $str = str_replace("ultraviolet", DBNAME, $str);
        // Replace default "uv_" prefix name by env value
        $str = str_replace("uv_", DBPREFIXE, $str);
        // Write updated script in user SQL script
        file_put_contents(getcwd().'/user_database.sql', $str);

        // Populate database
        $db = new \PDO(DBDRIVER . ":host=" . DBHOST . ";dbname=" . DBNAME . ";port=" . DBPORT, DBUSER, DBPWD);
        $sql = file_get_contents(getcwd().'/user_database.sql');
        $db->exec($sql);
        $db = null;

        Helpers::redirect(Helpers::callRoute('configStep5'));
    }

    public function step5Action() {
        $settings = new InstallerModel();
        $form = $settings->formBuilderCreateAdminUser();
        $view = new View("config/step5");
        $view->assign("form", $form);

        if(!empty($_POST)) {
            $errors = FormValidator::check($form, $_POST);
            if(empty($errors)) {
                // Update app name in .env file
                Helpers::updateConfigField('APP_NAME', htmlspecialchars($_POST['APP_NAME']));
                // Create new user in database
                $admin = new Person();
                // Check if pseudo is available
                if($admin->findOneBy("pseudo", $_POST['pseudo']))
                    $errors[] = 'Ce pseudonyme est indisponible';
                // Check if email is not already in database
                if($admin->findOneBy("email", $_POST['email']))
                    $errors[] = 'Cette adresse e-mail est déjà utilisée';

                if(empty($errors)) {
                    $admin->setPseudo(htmlspecialchars($_POST['pseudo']));
                    $admin->setEmail(htmlspecialchars($_POST['email']));
                    $admin->setPassword(password_hash(htmlspecialchars($_POST['password']), PASSWORD_DEFAULT));
                    $admin->setEmailConfirmed(true);
                    $admin->setRole('admin');
                    $admin->setDefaultProfilePicture();
                    if($admin->save()) {
                        Helpers::redirect(Helpers::callRoute('configStep6'));
                    } else {
                        $errors[] = "Les informations n'ont pas pu être sauvegardées, veuillez recommencer";
                    }
                }
            }
            $view->assign("errors", $errors);
        }
    }

    public function step6Action() {
        // Installation is complete
        // Update .env file to prevent installer to start again
        Helpers::updateConfigField('UV_INSTALLED', "true");
        $view = new View("config/step6");
    }

}