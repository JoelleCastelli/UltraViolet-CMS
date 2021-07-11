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

    /**
     * Introduction page
     **/
    public function step1Action() {
        $view = new View("installer/step1");
    }

    /**
     * Update .env file with database information
     **/
    public function step2Action() {
        $settings = new InstallerModel();
        $form = $settings->formBuilderInstallDB();
        $view = new View("installer/step2");
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
                    new \PDO(DBDRIVER . ":host=" . DBHOST . ";dbname=" . DBNAME . ";port=" . DBPORT, DBUSER, DBPWD);
                } catch (Exception $e) {
                    $errors[] = "Nous n'avons pas pu nous connecter à votre base de données. Veuillez vérifier vos informations";
                }

                // If connection OK: next step
                if (empty($errors))
                    Helpers::redirect(Helpers::callRoute('configStep3'));

            }
            $view->assign("errors", $errors);
        }
    }

    /**
     * .env file update confirmation + database creation announcement
     **/
    public function step3Action() {
        $view = new View("installer/step3");
    }

    /**
     * Tables creation in the database
     **/
    public function step4Action() {
        // Get default SQL script
        $str = file_get_contents(getcwd().'/_scripts/uv_db_script.sql');
        if($str) {
            // Replace default "ultraviolet" database name by .env value
            $str = str_replace("ultraviolet", DBNAME, $str);
            // Replace default "uv_" prefix name by env value
            $str = str_replace("uv_", DBPREFIXE, $str);
            // Write updated script in user SQL script
            if(file_put_contents(getcwd().'/_scripts/custom_db_script.sql', $str)) {
                // Populate database from custom SQL script
                $db = new \PDO(DBDRIVER.":host=".DBHOST."; dbname=".DBNAME."; port=".DBPORT."; charset=UTF8", DBUSER, DBPWD);
                $sql = file_get_contents(getcwd().'/_scripts/custom_db_script.sql');
                $db->exec($sql);
                // Check that the tables have correctly been created in the database (12 tables expected)
                if(count($db->query("SHOW TABLES")->fetchAll()) == 12) {
                    $db = null;
                    Helpers::redirect(Helpers::callRoute('configStep5'));
                } else {
                    $db = null;
                    Helpers::setFlashMessage('error', "Erreur dans la création des tables dans la base de données, veuillez recommencer.");
                }
            } else {
                Helpers::setFlashMessage('error', "Erreur dans l'écriture du script SQL personnalisé, veuillez recommencer.");
            }
        } else {
            Helpers::setFlashMessage('error', "Erreur dans la lecture du script SQL n'a pas pu être lu, veuillez recommencer.");
        }
        // Back to previous page with error flash message
        Helpers::redirect(Helpers::callRoute('configStep3'));

    }

    /**
     * Form to specify app name + create admin user
     **/
    public function step5Action() {
        $settings = new InstallerModel();
        $form = $settings->formBuilderCreateAdminUser();
        $view = new View("installer/step5");
        $view->assign("form", $form);

        if(!empty($_POST)) {
            $errors = FormValidator::check($form, $_POST);
            if(empty($errors)) {
                $admin = new Person();

                // Update app name in .env file
                Helpers::updateConfigField('APP_NAME', htmlspecialchars($_POST['APP_NAME']));
                // Check if pseudo is available (should not be necessary)
                if($admin->findOneBy("pseudo", $_POST['pseudo']))
                    $errors[] = 'Ce pseudonyme est indisponible';
                // Check if email is not already in database (should not be necessary)
                if($admin->findOneBy("email", $_POST['email']))
                    $errors[] = 'Cette adresse e-mail est déjà utilisée';

                if(empty($errors)) {
                    // Create new user in database
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

    /**
     * Confirm installation
     **/
    public function step6Action() {
        // Update .env file to prevent installer to start again
        Helpers::updateConfigField('UV_INSTALLED', "true");
        $view = new View("installer/step6");
    }

}