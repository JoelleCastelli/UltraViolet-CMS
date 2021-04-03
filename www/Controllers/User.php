<?php

namespace App\Controller;

use App\Core\View;
use App\Core\FormValidator;
use App\Models\User as UserModel;
use App\Models\Page;

class User
{

	public function defaultAction() {
		echo "User default";
	}

	public function registerAction() {
		$user = new UserModel();
		$view = new View("register");
		$form = $user->formBuilderRegister();

		if(!empty($_POST)) {

            $errors = FormValidator::check($form, $_POST);
           /* echo "<pre>";
            print_r($errors);
            echo "</pre>";*/
            if(empty($errors)){
				$user->setFirstname($_POST["firstname"]);
				$user->setLastname($_POST["lastname"]);
				$user->setEmail($_POST["email"]);
				$user->setPwd($_POST["pwd"]);
                $user->setCountry($_POST["country"]);

				$user->save();
			}else{

                $view->assign("errors", $errors);
			}
		}

        $view->assign("form", $form);
        $view->assign("post", $_POST);
        $view->assign("formLogin", $user->formBuilderLogin());
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

	//Method : Action
	public function showAllAction(){
		
		//Affiche la vue users intégrée dans le template du back
		$view = new View("users", "back"); 
		$view->assign("title", "Afficher les utilisateurs");
		$view->assign("user", "Kamal Hennou");
		
	}
	
}
