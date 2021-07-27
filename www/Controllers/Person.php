<?php

namespace App\Controller;

use App\Core\Helpers;
use App\Core\MediaManager;
use App\Core\View;
use App\Core\FormValidator;
use App\Core\Mail;
use App\Core\Request;
use App\Models\Person as PersonModel;
use App\Models\Comment as CommentModel;
use App\Models\Article as ArticleModel;
use App\Models\Media;


class Person
{
    protected array $columnsTable;

    public function __construct()
    {
        $this->columnsTable = [
            "name" => 'Nom et prénom',
            "pseudo" => 'Pseudonyme',
            "mail" => 'Email',
            "checkMail" => 'Verification email',
            "actions" => 'Actions'
        ];
    }

    public function showAllAction()
    {
        $view = new View("persons/list");
        $view->assign('title', 'Utilisateurs');
        $view->assign('columnsTable', $this->columnsTable);
        $view->assign('bodyScripts', [PATH_TO_SCRIPTS . 'bodyScripts/persons/person.js']);
    }

    public function loginAction()
    {
        $this->redirectHomeIfLogged();

        $user = new PersonModel();
        $view = new View("login", "front");
        $form = $user->formBuilderLogin();
        $view->assign("form", $form);

        if (!empty($_POST)) {
            $errors = FormValidator::check($form, $_POST);
            if (empty($errors)) {
                $user = $user->findOneBy("email", $_POST['email']);
                if (!empty($user)) {
                    if (password_verify($_POST['password'], $user->getPassword())) {
                        if ($user->isEmailConfirmed()) {
                            $_SESSION['loggedIn'] = true;
                            $_SESSION['user_id'] = $user->getId();
                            Helpers::setFlashMessage('success', "Bienvenue " . $user->getPseudo());
                            Helpers::namedRedirect('front_home');
                        } else {
                            $errors[] = "Merci de confirmer votre adresse e-mail. Renvoyer l'email de confirmation";
                        }
                    } else {
                        $errors[] = "Les identifiants ne sont pas reconnus";
                    }
                } else {
                    $errors[] = "Les identifiants ne sont pas reconnus";
                }
            }
            $view->assign("errors", $errors);
        }
    }

    public function registerAction()
    {
        $this->redirectHomeIfLogged();

        $user = new PersonModel();
        $view = new View('register', 'front');
        $form = $user->formBuilderRegister();
        $view->assign("form", $form);

        if (!empty($_POST)) {
            $errors = FormValidator::check($form, $_POST);
            if (empty($errors)) {
                // Check if pseudo is available
                if ($user->findOneBy("pseudo", $_POST['pseudo']))
                    $errors[] = 'Ce pseudonyme est indisponible';
                // Check if email is not already in database
                if ($user->findOneBy("email", $_POST['email']))
                    $errors[] = 'Cette adresse e-mail est déjà utilisée';

                // If no error in form, populate Person object and save in the database
                if (empty($errors)) {
                    $user->setPseudo(htmlspecialchars($_POST['pseudo']));
                    $user->setEmail(htmlspecialchars($_POST['email']));
                    $user->setPassword(password_hash(htmlspecialchars($_POST['pwd']), PASSWORD_DEFAULT));
                    $user->generateEmailKey();
                    $user->setDefaultProfilePicture();
                    $user->save();

                    // Send confirmation email
                    $to   = $_POST['email'];
                    $from = 'ultravioletcms@gmail.com';
                    $name = 'UltraViolet';
                    $subj = 'UltraViolet - Confirmez votre email';
                    $msg = $user->verificationMail($_POST['pseudo'], $user->getEmailKey());
                    $mail = new Mail();
                    $mail->sendMail($to, $from, $name, $subj, $msg);

                    Helpers::setFlashMessage('success', "Votre compte a bien été créé ! Un e-mail de confirmation
                    vous a été envoyé sur " . $_POST['email'] . ". </br> Cliquez sur le lien dans ce mail avant de vous connecter.");
                    Helpers::namedRedirect('login');
                }
            }
            $view->assign("errors", $errors);
        }
    }

    public function getUsersAction()
    {
        if (empty($_POST)) return;

        $users = new PersonModel();

        if (!empty($_POST['deletedAt'])) {
            $users = $users->select()->where('deletedAt', 'NOT NULL')->get();
        }

        if (!empty($_POST['role'])) {
            $users = $users->select()->where('role', htmlspecialchars($_POST['role']))->andWhere('deletedAt', 'NULL')->get();
        }

        if (!$users) $users = [];

        $usersArray = [];
        foreach ($users as $user) {
            $actions = $user->generateActionsMenu();

            $emailConfirmed = $user->isEmailConfirmed();
            if ($emailConfirmed == true) $emailConfirmed = 'oui';
            else $emailConfirmed = 'non';
            $usersArray[] = [
                $this->columnsTable['name'] => $user->getFullName()??'/',
                $this->columnsTable['pseudo'] => $user->getPseudo()??'/',
                $this->columnsTable['mail'] => $user->getEmail()??'/',
                $this->columnsTable['checkMail'] => $emailConfirmed,
                $this->columnsTable['actions'] => $actions,
            ];
        }
        echo json_encode(["users" => $usersArray]);
    }

    public function logoutAction()
    {
        session_destroy();
        Helpers::namedRedirect('front_home');
    }

    public function updatePersonAction($id)
    {
        if (!empty($id)) {
            $user = new PersonModel();
            $form = $user->formBuilderUpdatePerson($id);

            $view = new View("persons/update");
            $view->assign('title', 'Modifier un utilisateur');
            $view->assign("form", $form);

            $user->setId($id);

            // If form is submitted, check the data and save the category
            if (!empty($_POST)) {
                $errors = FormValidator::check($form, $_POST);
                if (empty($errors)) {

                    if ($user->count('email')->where('email', htmlspecialchars($_POST['email']))->andWhere('id', $user->getId(), '!=')->first(false))
                        $errors = ['Cet email est indisponible'];

                    if ($user->count('pseudo')->where('pseudo', htmlspecialchars($_POST['pseudo']))->andWhere('id', $user->getId(), '!=')->first(false))
                        $errors = ['Ce pseudonyme est indisponible'];

                    if (empty($errors)) {
                        $user->setEmail(htmlspecialchars($_POST["email"]));
                        $user->setPseudo(htmlspecialchars($_POST["pseudo"]));
                        $user->setRole(htmlspecialchars($_POST["role"]));
                        $user->save();

                        Helpers::setFlashMessage('success', "Vos informations ont bien été mises à jour");
                        Helpers::redirect(Helpers::callRoute('users_list'));
                    }
                }
                $view->assign("errors", $errors);
            }
        }
    }

    public function updatePersonStateAction()
    {

        if (!empty($_POST['id'])) {

            $user = new PersonModel;
            $id = $_POST['id'];
            $user->setId($id);

            if ($user->getDeletedAt()) {
                $user->setDeletedAt(null);
                $user->save();
                Helpers::setFlashMessage('succes', "Votre utilisateur a été restaurer");
            } else {
                Helpers::setFlashMessage('error', "Votre utilisateur n'est pas trouvable ");
            }
        }
    }

    public function updateUserAction()
    {
        $user = Request::getUser();
        if ($user && $user->isLogged()) {

            $view = new View('userUpdate', 'front');
            $form = $user->formBuilderUpdatePersonalInfo();
            $view->assign("form", $form);
            $view->assign("title", "Modifier vos informations");
            $view->assign('bodyScripts', [PATH_TO_SCRIPTS . 'bodyScripts/persons/userSettings.js']);

            if (!empty($_POST)) {
                $errors = FormValidator::check($form, $_POST);
                if (empty($errors)) {

                    if (!$user->isEmailConfirmed())
                        $errors = ['Veuillez confirmer d\'abord votre mail actuel'];

                    if ($user->count('email')->where('email', htmlspecialchars($_POST['email']))->andWhere('id', $user->getId(), '!=')->first(false))
                        $errors = ['Cet email est indisponible'];

                    if ($user->count('pseudo')->where('pseudo', htmlspecialchars($_POST['pseudo']))->andWhere('id', $user->getId(), '!=')->first(false))
                        $errors = ['Ce pseudonyme est indisponible'];

                    if (empty($errors)) {
                        if ($_POST["email"] !== $user->getEmail())
                            $emailChanged = true;

                        $user->setEmail(htmlspecialchars($_POST["email"]));
                        $user->setPseudo(htmlspecialchars($_POST["pseudo"]));
                        // Save profile picture
                        if(!empty($_FILES['profilePicture'])) {
                            $_FILES['profilePicture']["name"] = "user-".$user->getId().".png";
                            $mediaManager = new MediaManager();
                            $errors = $mediaManager->check($_FILES['profilePicture'], 'users');
                            if(empty($errors)) {
                                $mediaManager->uploadFile($mediaManager->getFiles());
                                $mediaManager->saveFile($mediaManager->getFiles());
                                $media = new Media();
                                $media = $media->findOneBy('path', PATH_TO_IMG_USERS.$_FILES['profilePicture']["name"]);
                                $user->setMediaId($media->getId());
                            }
                            unset($_FILES['profilePicture']);
                        }
                        if ($user->save()) {
                            if (isset($emailChanged)) {
                                $user->setEmailConfirmed(false);

                                /* Send mail confirmation */
                                $to = $user->getEmail();
                                $from = 'ultravioletcms@gmail.com';
                                $name = 'UltraViolet';
                                $subj = 'UltraViolet - Confirmez votre nouvelle adresse e-mail';
                                $msg = $user->updateMail($user->getPseudo(), $user->getEmailKey()); // mail content
                                $mail = new Mail();
                                $mail->sendMail($to, $from, $name, $subj, $msg);

                                $user->save();

                                // Message in cookie instead of session because it's destroyed during logout
                                setcookie('new-mail', "Vos informations ont bien été mises à jour.
                                Un e-mail vous a été envoyé pour confirmer votre nouvelle adresse");
                                $this->logoutAction();
                            }
                            Helpers::setFlashMessage('success', "Vos informations ont bien été mises à jour");
                            Helpers::namedRedirect('user_update');
                        }
                        $errors = ['Oops ! Un soucis lors de la sauvegarde est survenu'];
                    }
                }
                $view->assign("errors", $errors);
            }
        } else {
            Helpers::namedRedirect('front_home');
        }
    }

    public function updatePasswordAction()
    {
        $user = Request::getUser();
        if ($user && $user->isLogged()) {
            $view = new View('userUpdate', 'front');
            $form = $user->formBuilderUpdatePassword();
            $view->assign("form", $form);
            $view->assign("title", "Modifier votre mot de passe");

            if (!empty($_POST)) {
                $errors = FormValidator::check($form, $_POST);
                if (empty($errors)) {
                    // Check old password
                    if (!password_verify($_POST['oldPwd'], $user->getPassword()))
                        $errors[] = 'Ancien mot de passe non correct';

                    // If old password is correct
                    if (empty($errors)) {
                        // Check if new password and password confirmation match
                        if ($_POST['pwdConfirm'] == $_POST["pwd"]) {
                            $user->setPassword(password_hash(htmlspecialchars($_POST['pwd']), PASSWORD_DEFAULT));
                            if ($user->save()) {
                                Helpers::setFlashMessage('success', "Vos informations ont bien été mises à jour");
                                Helpers::namedRedirect('update_password');
                            }
                            $errors = ['Oups ! Un problème est survenu lors de la sauvegarde'];
                        }
                        $errors[] = $form['fields']['pwdConfirm']['error'];
                    }
                }
                $view->assign("errors", $errors);
            }
        } else {
            Helpers::namedRedirect('front_home');
        }
    }

    public function deletePersonAction()
    {
        if (!empty($_POST['id'])) {
            $user = new PersonModel();
            $id = $_POST['id'];
            $user->setId($id);
            $response = [];

            
            

            if(!$user->isAdmin() ||  $user->isAdmin() && $user->count('id')->where('role', 'admin')->andWhere('deletedAt', "NULL")->first(false) > 1){

                // si pas admin --> OK 
                // si admin && count(admin) < 2      ::   1<2  VRAI
                // si admin && count(admin) < 2      ::   2<2  FAUX
                // si admin && count(admin) > 2      ::   1>2  FAUX
                // si admin && count(admin) > 2      ::   2>2  FAUX
                // si admin && count(admin) > 1      ::   2>1  VRAI
                // si admin && count(admin) > 1      ::   1>1  FAUX

                if ($user->getDeletedAt()) {
                    //HARD DELETE USER
                    //Comments HARD delete
                    $comments = new CommentModel();
                    $comments = $comments->select()->where("personId", $id)->get();
                    foreach ($comments as $comment) {
                        $comment->hardDelete()->where('id', $comment->getId())->execute();
                    }
                    //Articles HARD delete
                    $articles = new ArticleModel();
                    $articles = $articles->select()->where("personId", $id)->get();
                    foreach ($articles as $article) {
                        $article->articleHardDelete();
                    }
                    $user->delete();

                } else {
                    //SOFT DELETE USER
                    $user->setPseudo('Anonyme' . $id);
                    $user->setEmail('anonyme' . $id . 'mail.com');
                    $user->setRole('user');
                    $user->delete();
                }
                $response['message'] = "Vous aviez bien supprimer cette utilisateur";
                $response['success'] = true;

            }else{
                $response['message'] = "Vous êtes le seul administrateur, par conséquent vous ne pouvez pas supprimer ce compte.";
                $response['success'] = false;
            }
        } else {
            $response['message'] = "La suppression de l'utilisateur n'a pas abouti";
            $response['success'] = false;
        }

        echo json_encode($response);
        
    }

    public function deleteUserAction()
    {
        $user = Request::getUser();
        if ($user && $user->isLogged()) {

            if (!$user->isAdmin() || ($user->isAdmin() && $user->count('email')->where('role', 'admin')->first(false) > 1)) {

                //SOFT DELETE
                $user->setPseudo(null);
                $user->setEmail(null);
                $user->setPassword(null);
                $user->setEmailKey(null);
                $user->setEmailConfirmed(false);
                $user->setDefaultProfilePicture();

                if ($user->delete()) {
                    Helpers::setFlashMessage("success", "Votre compte a bien été supprimé");
                    Helpers::namedRedirect("logout");
                } else {
                    Helpers::setFlashMessage("error", "Oops ! Une erreur est survenu lors de la suppression de votre compte.");
                    Helpers::namedRedirect("user_update");
                }
            } else {
                Helpers::setFlashMessage("error", "Vous êtes le seul administrateur, par conséquent vous ne pouvez pas supprimer ce compte.");
                Helpers::namedRedirect("user_update");
            }
        }

        Helpers::redirect404();
    }

    public function verificationAction($pseudo, $key)
    {

        $view = new View("userVerification", "front");
        $user = new PersonModel();
        $user = $user->select()->where("pseudo", $pseudo)->andWhere("emailkey", $key)->first();

        if (!empty($user)) {
            if ($user->isEmailConfirmed() != true) {
                $user->setEmailConfirmed(1);

                $user->save();
                Helpers::setFlashMessage('success', "Votre compte a bien été activé");
                Helpers::namedRedirect('login');
            } else {
                Helpers::setFlashMessage('error', "Votre compte est déjà activé");
            }
        } else {
            Helpers::setFlashMessage('error', "Aucun utilisateur trouvé");
        }
    }

    public function forgetPasswordMailAction()
    {

        $user = new PersonModel();
        $view = new View("forgetPassword", "front");
        $form = $user->formBuilderForgetPassword();
        $view->assign("form", $form);

        if (!empty($_POST)) {
            $errors = FormValidator::check($form, $_POST);
            if (empty($errors)) {
                $user = $user->findOneBy("email", $_POST['email']);
                if (!empty($user)) {
                    $to   = $_POST['email'];
                    $from = 'ultravioletcms@gmail.com';
                    $name = 'UltraViolet';
                    $subj = 'UltraViolet : modification du mot de passe';
                    $msg = $user->forgetPasswordMail($user->getId(), $user->getEmailKey());

                    $mail = new Mail();
                    $mail->sendMail($to, $from, $name, $subj, $msg);
                    Helpers::setFlashMessage('success', " Un e-mail
                    vous a été envoyé sur " . $_POST['email']);
                    Helpers::namedRedirect('login');
                } else {
                    $errors[] = "Aucun compte n'a été trouvé";
                }
            }
            $view->assign("errors", $errors);
        }
    }

    public function resetPasswordAction($id, $key)
    {
        $user = new PersonModel();
        $view = new View("resetPassword", "front");
        $form = $user->formBuilderResetPassword($id, $key);
        $view->assign("form", $form);
        if (!empty($_POST)) {
            $errors = FormValidator::check($form, $_POST);
            if (empty($errors)) {
                $user = $user->select()->where("id", $id)->andWhere("emailkey", $key)->first();
                if (!empty($user)) {
                    if (password_verify($_POST['password'], $user->getPassword())) {
                        Helpers::setFlashMessage('error', "Le mot de passe correspond au mot de passe déjà enregistré.");
                    } else {
                        $user->setPassword(password_hash(htmlspecialchars($_POST['pwd']), PASSWORD_DEFAULT));
                        $user->save();
                        Helpers::setFlashMessage('success', "Votre mot de passe a bien été modifié.");
                        Helpers::namedRedirect('login');
                    }
                } else {
                    $errors[] = "Les identifiants ne sont pas reconnus";
                }
            }
            $view->assign("errors", $errors);
        }
    }

    public function redirectHomeIfLogged()
    {
        $user = Request::getUser();
        if ($user && $user->isLogged()) {
            Helpers::namedRedirect('front_home');
        }
    }
}
