<?php

namespace App\Controller;

use App\Core\FormBuilder;
use App\Core\FormValidator;
use App\Core\Helpers;
use App\Core\View;
use App\Models\CategoryArticle;
use App\Models\Person;
use App\Models\Article;
use App\Models\Comment;
use App\Models\Settings;
use DateTime;
use Exception;

class Installer
{

    public function __construct()
    {
        // Prevent access ton installer if already installed
        if (UV_INSTALLED == 'true') Helpers::redirect404();
    } 


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
        $form = $this->formBuilderInstallDB();
        $view = new View("installer/step2");
        $view->assign("form", $form);

        if(!empty($_POST)) {
            $errors = FormValidator::check($form, $_POST);
            if(empty($errors)) {
                $settingsList = $_POST;
                // Remove csrfToken from settings list before save loop
                unset($settingsList['csrfToken']);

                // Prefix: force underscores if missing and remove duplicated ones
                if(!strpos($settingsList['DBPREFIXE'], '_'))
                    $settingsList['DBPREFIXE'] .= '_';
                // Prefix: remove duplicated underscores
                $settingsList['DBPREFIXE'] = preg_replace('~_+~', '_', $settingsList['DBPREFIXE']);

                // Update .env file with new values from user
                foreach ($settingsList as $name => $value) {
                    Helpers::updateConfigField($name, $value);
                }

                // Check if connection is successful
                try {
                    new \PDO(DBDRIVER.":host=".DBHOST."; dbname=".DBNAME."; port=".DBPORT, DBUSER, DBPWD);
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
     * Populate database: create tables + insert categories + insert first page
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
                // Check that the tables have correctly been created in the database (13 tables expected)
                if(count($db->query("SHOW TABLES")->fetchAll()) == 13) {
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
     * Specify app name + create admin user + create first article  + create first comment
     **/
    public function step5Action() {
        $form = $this->formBuilderCreateAdminUser();
        $view = new View("installer/step5");
        $view->assign("form", $form);

        if(!empty($_POST)) {
            $errors = FormValidator::check($form, $_POST);
            if(empty($errors)) {
                $admin = new Person();

                // Update app name in database
                $appName = new Settings();
                $appName = $appName->findOneBy('selector', 'appName');
                $appName->setValue($_POST['APP_NAME']);
                $appName->save();

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
                    $admin->generateEmailKey();
                    $admin->setDefaultProfilePicture();
                    if($admin->save()) {
                        // Insert first article
                        $articleId = $this->insertExampleArticle($admin->getLastInsertId());
                        // Insert first comment
                        $this->insertExampleComment($admin->getLastInsertId(), $articleId);
                        // Move on to next step
                        Helpers::redirect(Helpers::callRoute('configStep6'));
                    } else {
                        $errors[] = "Le compte utilisateur n'a pas pu être sauvegardé, veuillez recommencer";
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

    public function insertExampleArticle($authorId): string
    {
        // Create and save article
        $article = new Article();
        $article->setTitle("Votre tout premier article");
        $article->setSlug(Helpers::slugify($article->getTitle()));
        $article->setDescription("Découvrez le tout premier article de votre site, et voici le court descriptif qui apparaît dans les listes d'articles !");
        $article->setContent("Voici votre premier article ! Il parle des nouveaux films sortis en salle cette semaine : il est d'ailleurs lié aux catégories 'Films' et 'Actualités' ! Vous pouvez le modifier ou le supprimer directement depuis la page dédiée dans l'interface d'administration.");
        $article->setPersonId($authorId);
        $article->setDefaultPicture();
        $now = new DateTime('NOW');
        $article->setPublicationDate($now->format('Y-m-d H:i:s'));
        $article->save();

        // Bind article to "Films" and "Actualités" categories
        $this->bindArticleToCategory($article->getLastInsertId(), 1);
        $this->bindArticleToCategory($article->getLastInsertId(), 3);

        return $article->getLastInsertId();
    }

    public function insertExampleComment($authorId, $articleId) {
        $comment = new Comment();
        $comment->setContent("Voici le premier commentaire de cet article. Vous pouvez agir dessus via la page dédiée dans l'interface d'administration");
        $comment->setpersonId($authorId);
        $comment->setArticleId($articleId);
        $comment->save();
    }

    public function bindArticleToCategory($articleId, $categoryId) {
        $categoryArticle = new CategoryArticle();
        $categoryArticle->setArticleId($articleId);
        $categoryArticle->setCategoryId($categoryId);
        $categoryArticle->save();
    }

    public function formBuilderInstallDB(): array
    {
        $settings = Helpers::readConfigFile();
        if($settings) {
            return [
                "config" => [
                    "method" => "POST",
                    "action" => "",
                    "class" => "form_control",
                    "id" => "formBuilderInstallDB",
                    "submit" => "Valider",
                    "referer" => Helpers::callRoute('configStep2')
                ],
                "fields" => [
                    "DBNAME" => [
                        "type" => "text",
                        "minLength" => 1,
                        "maxLength" => 64,
                        "label" => "Nom de la base de données",
                        "class" => "search-bar",
                        "value" => $settings['DBNAME'],
                        "error" => "Le nom la base de données doit contenir entre 1 et 64 caractères",
                        "required" => true,
                    ],
                    "DBUSER" => [
                        "type" => "text",
                        "minLength" => 1,
                        "maxLength" => 16,
                        "label" => "Identifiant",
                        "class" => "search-bar",
                        "value" => $settings['DBUSER'],
                        "error" => "Le nom d'utilisateur de la base de données doit contenir entre 1 et 16 caractères",
                        "required" => true,
                    ],
                    "DBPWD" => [
                        "type" => "password",
                        "minLength" => 0,
                        "maxLength" => 32,
                        "label" => "Mot de passe",
                        "class" => "search-bar",
                        "error" => "Le mot de passe ne peut pas dépasser 32 caractères",
                    ],
                    "DBPREFIXE" => [
                        "type" => "text",
                        "minLength" => 0,
                        "maxLength" => 6,
                        "label" => "Préfixe des tables",
                        "class" => "search-bar",
                        "value" => $settings['DBPREFIXE'],
                        "error" => "Le préfixe doit contenir entre 1 et 6 caractères",
                        "required" => true,
                    ],
                    "csrfToken" => [
                        "type"=>"hidden",
                        "value"=> FormBuilder::generateCSRFToken(),
                    ]
                ],
            ];
        }
    }

    public function formBuilderCreateAdminUser(): array
    {
        $appName = new Settings();
        $appName = $appName->findOneBy('selector', 'appName')->getValue();
        return [
            "config" => [
                "method" => "POST",
                "action" => "",
                "class" => "form_control",
                "id" => "formBuilderInstallDB",
                "submit" => "Valider",
                "referer" => Helpers::callRoute('configStep5')
            ],
            "fields" => [
                "APP_NAME" => [
                    "type" => "text",
                    "minLength" => 1,
                    "maxLength" => 50,
                    "label" => "Titre du site",
                    "value" => $appName,
                    "class" => "search-bar",
                    "error" => "Le titre du site doit contenir entre 1 et 50 caractères",
                    "required" => true,
                ],
                "pseudo" => [
                    "type" => "text",
                    "minLength" => 1,
                    "maxLength" => 25,
                    "label" => "Pseudonyme",
                    "class" => "search-bar",
                    "error" => "Le nom d'utilisateur de la base de données doit contenir entre 1 et 16 caractères",
                    "required" => true,
                ],
                "email" => [
                    "type" => "text",
                    "minLength" => 0,
                    "maxLength" => 130,
                    "label" => "Adresse email",
                    "class" => "search-bar",
                    "error" => "Votre adresse email doit comporter entre 8 et 130 caractères",
                    "required" => true,
                ],
                "password" => [
                    "type" => "password",
                    "minLength" => 8,
                    "maxLength" => 255,
                    "label" => "Mot de passe",
                    "class" => "search-bar",
                    "regex" => "/(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&-])[A-Za-z\d@$!%*?&-]{8,}$/",
                    "error" => "Votre mot de passe comporter au minimum 8 caractères dont au moins une lettre minuscule, une majuscule, un chiffre et un caractère spécial",
                    "required" => true,
                ],
                "csrfToken" => [
                    "type"=>"hidden",
                    "value"=> FormBuilder::generateCSRFToken(),
                ]
            ],
        ];
    }

}