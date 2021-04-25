<?php

namespace App\Controller;

use App\Core\Helpers;
use App\Core\View;
use App\Core\FormValidator;
use App\Models\Person as PersonModel;
use App\Models\Page;

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
	    $user->setId(11);
	    $user->setDeletedAt(Helpers::getCurrentTimestamp());
	    $user->save();
    }

	public function registerAction() {

		$user = new PersonModel();
		$form = $user->formBuilderRegister();
        $view = new View("register");

        if(!empty($_POST)) {

            $errors = FormValidator::check($form, $_POST);
            if(empty($errors)){

                if(isset($_POST["optin"]))
                    $newletters = $_POST["optin"][0];
                else
                    $newletters = 0;

                $dateNow = new \DateTime('now');
                $updatedAt = $dateNow->format("Y-m-d H:i:s");

				$user->setFullName($_POST["fullName"]);
				$user->setPseudo($_POST["pseudo"]);
				$user->setEmail($_POST["email"]);
                $user->setPassword($_POST["pwd"]);
                $user->setRole($_POST["role"]);
                $user->setOptin($newletters);
                $user->setUpdatedAt($updatedAt);
                $user->setUvtrMediaId(1);
                $user->setDeletedAt(null);

				//$user->save();
			}else{
                $view->assign("errors", $errors);
			}
		}

        $view->assign("form", $form);
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
	
}
