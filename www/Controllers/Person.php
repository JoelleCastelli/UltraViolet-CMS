<?php

namespace App\Controller;

use App\Core\Helpers;
use App\Core\View;
use App\Core\FormValidator;
use App\Models\Person as PersonModel;

class Person
{

    public function showAllAction() {
        $view = new View("persons/list");
        $view->assign('title', 'Utilisateurs');
        $view->assign('bodyScripts', [PATH_TO_SCRIPTS . 'bodyScripts/persons/persons.js']);
    }

	public function defaultAction() {
		echo "User default";
	}

	public function deleteAction() {
	    $user = new PersonModel();
	    $user->setId(3);
	    $user->setDeletedAt(Helpers::getCurrentTimestamp());
	    $user->save();
    }

    public function loginAction() {
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
                            Helpers::redirect('/');
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
		$user = new PersonModel();
        $view = new View('register', 'front');
        $form = $user->formBuilderRegister();
        $view->assign("form", $form);

        if(!empty($_POST)) {
            $errors = FormValidator::check($form, $_POST);
            if(empty($errors)) {
                if($user->findOneBy("pseudo", $_POST['pseudo'])) {
                    $errors[] = 'Ce pseudonyme est indisponible';
                }
                if($user->findOneBy("email", $_POST['email'])) {
                    $errors[] = 'Cette adresse e-mail est déjà utilisée';
                }
                if(empty($errors)) {
                    $user->setPseudo(htmlspecialchars($_POST['pseudo']));
                    $user->setEmail(htmlspecialchars($_POST['email']));
                    $user->setPassword(password_hash(htmlspecialchars($_POST['pwd']), PASSWORD_DEFAULT));
                    
                    //$user->setDefaultProfilePicture();
                    $user->setMediaId(1);
                    $user->setEmailConfirmed(1);

                    // set emailkey
                    $lengthkey = 15;
                    $key= "";
                    for($i=1;$i<$lengthkey;$i++) {
                        $key.=mt_rand(0,9);
                    }
                    $user->setEmailKey($key);

                    //Helpers::dd($user);

                    $user->save();
                
                    Helpers::setFlashMessage('success', "Votre compte a bien été créé ! Un e-mail de confirmation
                    vous a été envoyé sur " .$_POST['email'].". </br> Cliquez sur le lien dans ce mail avant de vous connecter.");
                    Helpers::redirect('/connexion');
                }
			}
            $view->assign("errors", $errors);
		}
	}

	public function logoutAction() {
        session_destroy();
        Helpers::redirect('/');
    }

    public function updateAction()
    {
        $user = new UserModel();
        $user->setId(3);
        $user->setFirstname("NON");
        $user->setCountry("pr");
        $user->setRole("6");
        $user->save();
    }

	//Method : Action
	public function addAction(){
		
		//Récupérer le formulaire
		//Récupérer les valeurs de l'internaute si il y a validation du formulaire
		//Vérification des champs (uncitié de l'email, complexité du pwd, ...)
		//Affichage du résultat

	}

    

	public function showAction(){
		
		//Affiche la vue user intégrée dans le template du front
		$view = new View("user"); 
	}
	
}
