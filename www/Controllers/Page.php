<?php

namespace App\Controller;

use App\Core\View;
use App\Core\FormValidator;
use App\Models\Page as PageModel;

class Page
{

	public function indexAction() {

        $view = new View("page/index");
    }

	public function createPageAction() {
		$page = new PageModel();
		$view = new View("page/createPage");

		$form = $page->formBuilderRegister();

		if(!empty($_POST)) {
			
			$errors = FormValidator::check($form, $_POST);

			if(empty($errors)){
				$page->setTitle($_POST["title"]);
				$page->setSlug($_POST["slug"]);
				$page->setPosition($_POST["position"]);
				$page->setTitleSeo($_POST["titleSEO"]);
				$page->setDescriptionSeo($_POST["descriptionSEO"]);
				$page->setState($_POST["state"]);

				$page->save();
			}else{
				$view = new View("page/createPage");
				$view->assign("errors", $errors);
			}
		}

		$view->assign("form", $form);
	}
	
}
