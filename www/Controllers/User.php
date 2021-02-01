<?php

namespace App\Controller;

use App\Core\View;
use App\Core\FormValidator;
use App\Models\User as UserModel;
use App\Models\Page;

class User
{

	//Method : Action
	public function defaultAction(){
		echo "User default";
	}


	//Method : Action
	public function registerAction(){
		
		/*
			$user->setFirstname("Yves");
			$user->setLastname("SKRZYPCZYK");
			$user->setEmail("y.skrzypczyk@gmail.com");
			$user->setPwd("Test1234");
			$user->setCountry("fr");

			$user->save();



			$page = new Page();
			$page->setTitle("Nous contacter");
			$page->setSlug("/contact");
			$page->save();



			$user = new User();
			$user->setId(2); //Attention on doit populate
			$user->setFirstname("Toto");
			$user->save();

		*/


		$user = new UserModel();
		$view = new View("register"); 

		$form = $user->formBuilderRegister();

		if(!empty($_POST)){
			
			$errors = FormValidator::check($form, $_POST);

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
		$view->assign("formLogin", $user->formBuilderLogin());
	}


	//Method : Action
	public function addAction(){
		
		//Récupérer le formulaire
		//Récupérer les valeurs de l'internaute si il y a validation du formulaire
		//Vérification des champs (uncitié de l'email, complexité du pwd, ...)
		//Affichage du résultat

	}

	//Method : Action
	public function showAction(){
		
		//Affiche la vue user intégrée dans le template du front
		$view = new View("user"); 
	}



	//Method : Action
	public function showAllAction(){
		
		//Affiche la vue users intégrée dans le template du back
		$view = new View("users", "back"); 
		
	}
	
}
