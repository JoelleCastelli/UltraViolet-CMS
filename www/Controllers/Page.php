<?php

namespace App\Controller;


use App\Core\View;
use App\Core\FormValidator;
use App\Models\Page as PageModel;

class Page
{

    public function showAllAction() {
        $pages = new PageModel();
        $pages = $pages->findAll();

        if(!$pages) $pages = [];

        foreach ($pages as $page) {
            $page->cleanPublictionDate();
        }

        $view = new View("pages/list");
        $view->assign('title', 'Pages');
        $view->assign("pages", $pages);
    }

    public function createPageAction() {
        $page = new PageModel();
        $view = new View("pages/createPage");
        $view->assign('title', 'Création d\'une page');

        $form = $page->formBuilderRegister();

        if(!empty($_POST)) {

            $errors = FormValidator::check($form, $_POST);

            if(empty($errors)){
                $page->setTitle($_POST["title"]);
                $page->setSlug($_POST["slug"]);
                $page->setPosition($_POST["position"]);
                $page->setTitleSeo($_POST["titleSEO"]);
                $page->setDescriptionSeo($_POST["descriptionSEO"]);
                $page->setPublictionDate($_POST["publictionDate"]);
                $page->setState($_POST["state"]);

                $page->save();
            }else{
                $view = new View("pages/createPage");
                $view->assign("errors", $errors);
            }
        }

        $view->assign("form", $form);
    }

}
