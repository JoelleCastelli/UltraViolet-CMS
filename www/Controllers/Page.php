<?php

namespace App\Controller;

use App\Core\View;
use App\Core\FormValidator;
use App\Models\Page as PageModel;

class Page
{

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
				$page->setDraft($_POST["draft"]);

				$page->save();
			}else{
				$view->assign("errors", $errors);
			}
		}

		$view->assign("form", $form);
	}
	
}
