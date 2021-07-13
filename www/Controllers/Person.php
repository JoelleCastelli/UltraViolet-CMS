<?php

namespace App\Controller;

use App\Core\Helpers;
use App\Core\View;
use App\Core\FormValidator;
use App\Core\Mail;
use App\Core\Request;
use App\Models\Person as PersonModel;
use App\Models\Comment as CommentModel;
use App\Models\Article as ArticleModel;


class Person
{
    protected $columnsTable;

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

    public function showAllAction() {
        $view = new View("persons/list");
        $view->assign('title', 'Utilisateurs');
        $view->assign('columnsTable', $this->columnsTable);
        $view->assign('bodyScripts', [PATH_TO_SCRIPTS . 'bodyScripts/persons/person.js']);
    }
    
	public function defaultAction() {
		echo "User default";
	}

    public function loginAction() {

        $this->redirectHomeIfLogged();

        $user = new PersonModel();
        $view = new View("login", "front");
        $form = $user->formBuilderLogin();
        $view->assign("form", $form);

        if(!empty($_POST)) {
            $errors = FormValidator::check($form, $_POST);
            if(empty($errors)) {
                $user = $user->findOneBy("email", $_POST['email']);
                if(!empty($user)) {
                    if(password_verify($_POST['password'], $user->getPassword())) {
                        if($user->isEmailConfirmed()) {
                            $_SESSION['loggedIn'] = true;
                            $_SESSION['user_id'] = $user->getId();
                            Helpers::setFlashMessage('success', "Bienvenue ".$user->getPseudo());
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

	public function registerAction() {

        $this->redirectHomeIfLogged();

		$user = new PersonModel();
        $view = new View('register', 'front');
        $form = $user->formBuilderRegister();
        $view->assign("form", $form);

        if(!empty($_POST)) {
            $errors = FormValidator::check($form, $_POST);
            if(empty($errors)) {
                // Check if pseudo is available
                if($user->findOneBy("pseudo", $_POST['pseudo']))
                    $errors[] = 'Ce pseudonyme est indisponible';
                // Check if email is not already in database
                if($user->findOneBy("email", $_POST['email']))
                    $errors[] = 'Cette adresse e-mail est déjà utilisée';

                // If no error in form, populate Person object and save in the database
                if(empty($errors)) {
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
                    vous a été envoyé sur " .$_POST['email'].". </br> Cliquez sur le lien dans ce mail avant de vous connecter.");
                    Helpers::namedRedirect('login');
                }
			}
            $view->assign("errors", $errors);
		}
	}

    public function getUsersAction() {
        if(empty($_POST)) return;

        $users = new PersonModel();

        if(!empty($_POST['deletedAt'])) {
            $users = $users->select()->where('deletedAt', 'NOT NULL')->get();
        }

        if (!empty($_POST['role'])) {        
            $users = $users->select()->where('role', htmlspecialchars($_POST['role']))->andWhere('deletedAt', 'NULL')->get();

        }

        if(!$users) $users = [];
        
        $usersArray = [];
        foreach ($users as $user) {
            $emailConfirmed = $user->isEmailConfirmed();
            if ( $emailConfirmed == true ) $emailConfirmed = 'oui';
            else $emailConfirmed = 'non';
            $usersArray[] = [
                $this->columnsTable['name'] => $user->getFullName(),
                $this->columnsTable['pseudo'] => $user->getPseudo(),
                $this->columnsTable['mail'] => $user->getEmail(),
                $this->columnsTable['checkMail'] => $emailConfirmed,
                $this->columnsTable['actions'] => $user->generateActionsMenu(),
            ];
        }
            echo json_encode(["users" => $usersArray]);
    }

	public function logoutAction() {
        session_destroy();
        Helpers::namedRedirect('front_home');
    }

    public function updatePersonAction($id) {
        if (!empty($id)){ 
            // Helpers::cleanDumpArray($id,'id post');
            $user = new PersonModel();
            $form = $user->formBuilderUpdatePerson($id);

            $view = new View("persons/update");
            $view->assign('title', 'Modifier un utilisateur');
            $view->assign("form", $form);
            
            $user->setId($id);

            // If form is submitted, check the data and save the category
            if(!empty($_POST)) {
                $errors = FormValidator::check($form, $_POST);
                if(empty($errors)) {
                    $user->setEmail(htmlspecialchars($_POST["email"]));
                    $user->setPseudo(htmlspecialchars($_POST["pseudo"]));
                    $user->setRole(htmlspecialchars($_POST["role"]));
                    
                    $user->save();
                    
                    Helpers::setFlashMessage('success', "L'utilisateur a bien été mise à jour");
                    Helpers::redirect(Helpers::callRoute('users_list'));
                    
                } else {
                    $view->assign("errors", $errors);
                }
            }
        }
    }

    public function deletePersonAction() {

        if (!empty($_POST['id'])){ 
            $user = new PersonModel();
            $id = $_POST['id'];
            $user->setId($id);
                        
            if ($user->getDeletedAt()) {
                //HARD DELETE USER

                //Comments HARD delete
                $comments = new CommentModel();
                $comments = $comments->select()->where("personId", $id)->get();
                foreach ($comments as $comment) {
                    $comment->hardDelete()->where( 'id' , $comment->getId() )->execute();
                }

                //Articles HARD delete
                $articles = new ArticleModel();
                $articles = $articles->select()->where( "personId" , $id)->get();
                foreach ($articles as $article) {
                    $article->articleHardDelete();
                }
                $user->delete();

            }else{
                //SOFT DELETE USER

                //Articles SOFT delete
                $articles = new ArticleModel();
                $articles = $articles->select()->where( "personId" , $id)->get();
                foreach ($articles as $article) {
                    $article->getDeletedAt() ? $article->articleHardDelete() : $article->articleSoftDelete();
                }
                $user->delete();
            }
            
            Helpers::setFlashMessage('success', "Vous aviez bien supprimer cette utilisateur");
        }else{
            Helpers::setFlashMessage('error', "La suppression de l'utilisateur n'a pas abouti");
        }
    }

    
	public function showAction(){
		//Affiche la vue user intégrée dans le template du front
		$view = new View("user"); 
	}

    public function verificationAction($pseudo, $key){

        $view = new View("userVerification", "front");
        $user = new PersonModel();
        $user = $user->select()->where("pseudo", $pseudo)->andWhere("emailkey", $key)->first();
        
        if(!empty($user))
        {
            if($user->isEmailConfirmed() != true)
            {
                $user->setEmailConfirmed(1);

                $user->save();
                Helpers::setFlashMessage('success', "Votre compte a bien était activé");
                Helpers::namedRedirect('login');
            }else
            {
                Helpers::setFlashMessage('error', "Votre compte est déjà activé");
            }

        }else 
        {
            Helpers::setFlashMessage('error', "Aucun utilisateur trouvé");
        }
	}	

    public function forgetPasswordMailAction(){
		
		$user = new PersonModel();
        $view = new View("forgetPassword", "front");
        $form = $user->formBuilderForgetPassword();
        $view->assign("form", $form);

        if(!empty($_POST)) {
            $errors = FormValidator::check($form, $_POST);
            if(empty($errors)) {
                $user = $user->findOneBy("email", $_POST['email']);
                if(!empty($user)) {
                    $to   = $_POST['email'];
                    $from = 'ultravioletcms@gmail.com';
                    $name = 'Ultaviolet';
                    $subj = 'Changée de mot de passe';
                    $msg = $user->forgetPasswordMail($user->getId(), $user->getEmailKey());
                    
                    $mail = new Mail();
                    $mail->sendMail($to, $from, $name, $subj, $msg);
                    Helpers::setFlashMessage('success', " Un e-mail
                    vous a été envoyé sur " .$_POST['email']);
                    Helpers::namedRedirect('login');
                } else {
                    $errors[] = "Aucun compte n'a été trouvé";
                }
            }
            $view->assign("errors", $errors);
        }
	}

    public function resetPasswordAction($id, $key){

        $user = new PersonModel();
        $view = new View("resetPassword", "front");
        $form = $user->formBuilderResetPassword($id, $key);
        $view->assign("form", $form);
        if(!empty($_POST)) {
            $errors = FormValidator::check($form, $_POST);
            if(empty($errors)) {
                $user = $user->select()->where("id", $id)->andWhere("emailkey", $key)->first();
                if(!empty($user)) {
                    if(password_verify($_POST['password'], $user->getPassword())) {
                        Helpers::setFlashMessage('error', "Le mot de passe corespond au mot de passe déjà enregistré."); 
                    } else {
                        $user->setPassword(password_hash(htmlspecialchars($_POST['pwd']), PASSWORD_DEFAULT));
                        $user->save();
                        Helpers::setFlashMessage('success', "Votre mot de passe à bien était changée.");
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
