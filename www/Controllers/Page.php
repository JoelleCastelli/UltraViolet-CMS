<?php

namespace App\Controller;

use App\Core\View;
use App\Core\FormValidator;
use App\Models\Page as PageModel;

class Page
{

    protected $columnsTable;
    protected $actions;

    public function __construct()
    {
        $this->columnsTable = [
            "title" => 'Nom de la page',
            "slug" => 'URL de la page',
            "position" => 'Ordre',
            "articles" => 'Nombre d\'articles',
            "state" => 'Visibilité',
            "actions" => 'Actions'
        ];

        $this->actions = [
            ['name' => 'Modifier', 'url' => '/admin/productions/modifier'],
            ['name' => 'Supprimer', 'url' => '/admin/productions/modifier'],
        ];
    }

    public function showAllAction() {
        $pages = new PageModel();
        $pages = $pages->findAll();

        if(!$pages) $pages = [];

        foreach ($pages as $page) {
            $page->cleanPublicationDate();
        }

        $view = new View("pages/list");
        $view->assign('title', 'Pages');
        $view->assign("pages", $pages);
        $view->assign('columnsTable', $this->columnsTable);
        $view->assign('headScripts', [PATH_TO_SCRIPTS . 'headScripts/pages/pages.js']);

    }

    public function getPagesAction()
    {
        if (!empty($_POST['pageType'])) {
            $pages = new PageModel();
            $pages = $pages->selectWhere('state', htmlspecialchars($_POST['pageType']));
            if (!$pages) $pages = [];

            $actions = "<div class='actionsDropdown'>";
            foreach ($this->actions as $action) {
                $actions .= "<a href='" . $action['url'] . "'>" . $action['name'] . "</a>";
            }
            $actions .= "</div>";

            $pageArray = [];
            foreach ($pages as $page) {
                $pageArray[] = [
                    $this->columnsTable['title'] => $page->getTitle(),
                    $this->columnsTable['slug'] => $page->getSlug(),
                    $this->columnsTable['position'] => $page->getPosition(),
                    $this->columnsTable['articles'] => 43,
                    $this->columnsTable['state'] => $page->getState() == "hidden" ? true : false,
                    $this->columnsTable['actions'] => "<div class='bubble-actions'>$actions</div>"
                ];
            }

            echo json_encode([
                "pages" => $pageArray,
                "columns" => $this->columnsTable,
            ]);
        }
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
                $page->setPublicationDate($_POST["publictionDate"]);
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
