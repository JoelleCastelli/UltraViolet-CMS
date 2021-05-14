<?php

namespace App\Controller;

use App\Core\Helpers;
use App\Core\View;
use App\Core\FormValidator;
use App\Models\Person as PersonModel;

class Person
{

    public function showAllAction() {
        $view = new View("users/list");
        $view->assign('title', 'Utilisateurs');
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
            if(empty($errors)){
                $user = $user->findOneBy("email", htmlspecialchars($_POST['email']));
                if(!empty($user)) {
                    if(password_verify($_POST['password'], $user->getPassword())) {
                        $_SESSION['loggedIn'] = true;
                        $_SESSION['user_id'] = $user->getId();
                        Helpers::setFlashMessage('success', "Bienvenue ".$user->getPseudo());
                        Helpers::redirect('/admin');
                    } else {
                        $errors[] = "Les identifiants ne sont pas reconnus";
                        $view->assign("errors", $errors);
                    }
                }
            } else {
                $view->assign("errors", $errors);
            }
        }
    }

	public function registerAction() {

		$user = new PersonModel();
		$form = $user->formBuilderRegister();
        $view = new View("register", "front");

        if(!empty($_POST)) {

            $errors = FormValidator::check($form, $_POST);
            if(empty($errors)){

                // Init some values
                $dateNow = new \DateTime('now');
                $updatedAt = $dateNow->format("Y-m-d H:i:s");
                $pwd = password_hash(htmlspecialchars($_POST["pwd"]), PASSWORD_DEFAULT);

                // Required
				$user->setFullName(htmlspecialchars($_POST["fullName"]));
				$user->setPseudo(htmlspecialchars($_POST["pseudo"]));
                $user->setEmail(htmlspecialchars($_POST["email"]));
                $user->setPassword($pwd);
                $user->setUpdatedAt($updatedAt);

                // Default
                $user->setRole('user');
                $user->setOptin(0);
                $user->setUvtrMediaId(1);
                $user->setDeletedAt(null);

				$user->save();
			}else{
                $view->assign("errors", $errors);
			}
		}

        $view->assign("form", $form);
        $view->assign("formLogin", $user->formBuilderLogin());
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
