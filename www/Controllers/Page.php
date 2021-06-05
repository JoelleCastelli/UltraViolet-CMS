<?php

namespace App\Controller;

<<<<<<< HEAD

=======
use App\Core\Helpers;
>>>>>>> 75db105 (update view page and add page functionality)
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

    public function showAllAction()
    {
        $page = new PageModel();
        $formCreatePage = $page->formBuilderRegister();
        $pages = $page->findAll();

        if (!$pages) $pages = [];

        foreach ($pages as $page) {
            $page->cleanPublicationDate();
        }

        $view = new View("pages/list");

        $view->assign('title', 'Pages');
        $view->assign('pages', $pages);
        $view->assign('formCreatePage', $formCreatePage);
        $view->assign('columnsTable', $this->columnsTable);
        $view->assign('bodyScripts', [PATH_TO_SCRIPTS . 'bodyScripts/pages/pages.js']);
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
                    $this->columnsTable['state'] => $page->getState() == "hidden" ? '<div class="state-switch switch-visibily-page" onclick="toggleSwitch(this)"></div>' : '<div class="state-switch switched-on switch-visibily-page" onclick="toggleSwitch(this)"></div>',
                    $this->columnsTable['actions'] => "<div class='bubble-actions'>$actions</div>"
                ];
            }

            echo json_encode([
                "pages" => $pageArray,
                "columns" => $this->columnsTable,
            ]);
        }
    }

    public function createPageAction()
    {
        $page = new PageModel();
        $view = new View("pages/createPage");
        $view->assign('title', 'Création d\'une page');

        $form = $page->formBuilderRegister();

        if (!empty($_POST)) {

            $errors = FormValidator::check($form, $_POST);

            if (empty($errors)) {
                $page->setTitle($_POST["title"]);
                $page->setSlug($_POST["slug"]);
                $page->setPosition($_POST["position"]);
                $page->setTitleSeo($_POST["titleSEO"]);
                $page->setDescriptionSeo($_POST["descriptionSEO"]);
                $page->setPublicationDate($_POST["publictionDate"]);
                $page->setState($_POST["state"]);

                $page->save();
            } else {
                $view = new View("pages/createPage");
                $view->assign("errors", $errors);
            }
        }

        $view->assign("form", $form);
    }

    public function addPageAction()
    {

        $page = new PageModel();
        $form = $page->formBuilderRegister();

        if (!empty($_POST)) {

            $response = [];

            // $errors = FormValidator::check($form, $_POST);
            $errors = '';

            if (empty($errors)) {

                /* VERIFICATIONS SLUG */
                if (empty($_POST['slug'])) // if slug not specify
                {
                    $pattern[] = "/\s+/";
                    $pattern[] = "/[^A-Za-z0-9\-]/";
                    $pattern[] = "/-+/";

                    $replacement[] = "-";
                    $replacement[] = "";
                    $replacement[] = "-";

                    $slug = preg_replace($pattern, $replacement, strtolower(trim(htmlspecialchars($_POST["title"]))));
                   
                   
                } else { // if slug specify

                    $slug = htmlspecialchars($_POST["slug"]); 
                }
                $isNotUniqueSlug = $page->selectWhere('slug', $slug); // check unicity of slug

                if(empty($isNotUniqueSlug)) {
                    
                    $page->setSlug($slug);

                    if ($_POST['state'] == 'draft') {
                        $page->setState('draft');
                        $page->setPublicationDate(htmlspecialchars($_POST['publicationDate']));

                    } else if ($_POST['state'] == 'published') {
                        $page->setState('published');
                        $page->setPublicationDate(Helpers::getCurrentTimestamp());
                    }

                    if (!empty($page->getState())) {

                        $page->setTitle($_POST["title"]);
                        $page->setSlug($_POST["slug"]);
                        $page->setPosition(empty($_POST["position"]) ? "2" : $_POST["position"]);
                        $page->setTitleSeo(empty($_POST["titleSEO"]) ? "mon tire seo deuxième" : $_POST["titleSEO"]);
                        $page->setDescriptionSeo(empty($_POST["descriptionSEO"]) ? "ma description seo" : $_POST["descriptionSEO"]);
                        $page->setCreatedAt(Helpers::getCurrentTimestamp());
                        $check = $page->save();

                        if($check) {
                            $response['message'] = 'Sauvegarde faite !';
                            $response['success'] = true;
                        }else {
                            $response['message'] = 'Oulah Oops problème serveur sorry';
                            $response['success'] = false;
                        }

                    } else {
                        $response['message'] = 'Le statut choisie est incorrect';
                        $response['success'] = false;
                    }
                } else {
                    $response['message'] = 'Ce slug existe déjà AHAH';
                    $response['success'] = false;
                }
            } else {
                $response['message'] = $errors;
                $response['success'] = false;
            }
            $response['post'] = $_POST;

            echo json_encode($response);
        }
    }
    // if (empty($errors)) {
    //     $page->setTitle($_POST["title"]);
    //     $page->setSlug($_POST["slug"]);
    //     $page->setPosition($_POST["position"]);
    //     $page->setTitleSeo($_POST["titleSEO"]);
    //     $page->setDescriptionSeo($_POST["descriptionSEO"]);
    //     $page->setPublicationDate($_POST["publictionDate"]);
    //     $page->setState($_POST["state"]);

    //     $page->save();
    // } else {
    //     $view = new View("pages/createPage");
    //     $view->assign("errors", $errors);
    // }


}
