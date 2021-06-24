<?php

namespace App\Controller;

use App\Core\Helpers;
use App\Core\View;
use App\Core\FormValidator;
use App\Models\Page as PageModel;
use App\Models\PageArticle;

class Page
{

    protected $columnsTable;

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
    }

    public function showAllAction()
    {
        $page = new PageModel();
        $formCreatePage = $page->formBuilderRegister();
        $formUpdatePage = $page->formBuilderUpdate();
        $pages = $page->findAll();

        if (!$pages) $pages = [];

        foreach ($pages as $page) {
            $page->cleanPublicationDate();
        }

        $view = new View("pages/list");

        $view->assign('title', 'Pages');
        $view->assign('pages', $pages);
        $view->assign('formCreatePage', $formCreatePage);
        $view->assign('formUpdatePage', $formUpdatePage);
        $view->assign('columnsTable', $this->columnsTable);
        $view->assign('bodyScripts', [PATH_TO_SCRIPTS . 'bodyScripts/pages/pages.js']);
    }

    public function getPagesAction()
    {

        if (!empty($_POST['pageType'])) {
            $pageModel = new PageModel();
            $pageArticle = new PageArticle();

            // get pages
            if ($_POST['pageType'] === 'published') {
                $pages = $pageModel->selectWhere('state', htmlspecialchars($_POST['pageType']));
                $pages = array_merge($pages, $pageModel->selectWhere('state', 'hidden'));
            } else {
                $pages = $pageModel->selectWhere('state', htmlspecialchars($_POST['pageType']));
            }

            if (!$pages) $pages = [];

            $pageArray = [];
            foreach ($pages as $page) {

                if($page->getState() != "deleted"){
                    $actions = $page->generateActionsMenu();
                }
                else{
                    $page->setActions($page->getActionsDeletedPages());
                    $actions = $page->generateActionsMenu();
                }

                $pageArray[] = [
                    $this->columnsTable['title'] => $page->getTitle(),
                    $this->columnsTable['slug'] => $page->getSlug(),
                    $this->columnsTable['position'] => $page->getPosition(),
                    $this->columnsTable['articles'] => $pageArticle->count("pageId")->where("pageId", $page->getId())->first()->total,
                    $this->columnsTable['state'] => $page->getState() == "hidden" ?
                        '<div id="page-visibilty-' . $page->getId() . '" class="state-switch switch-visibily-page" onclick="toggleSwitch(this)"></div>'
                        : '<div id="page-visibilty-' . $page->getId() . '" class="state-switch switched-on switch-visibily-page" onclick="toggleSwitch(this)"></div>',
                    $this->columnsTable['actions'] => $actions
                ];
            }

            echo json_encode([
                "pages" => $pageArray,
                "columns" => $this->columnsTable,
            ]);
        }
    }

    public function createcPageAction()
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

    public function createPageAction()
    {

        $page = new PageModel();
        $form = $page->formBuilderRegister();

        if (!empty($_POST)) {

            $response = [];

            //$errors = FormValidator::check($form, $_POST);
            $errors = '';

            if (empty($errors)) {

                if(empty($_POST['slug'])) {
                    $slug = Helpers::slugify($_POST['title']);
                } else {
                    $slug = htmlspecialchars($_POST['slug']);
                }

                $isNotUniqueSlug = $page->selectWhere('slug', $slug); // check unicity of slug

                if (empty($isNotUniqueSlug)) {

                    $page->setSlug($slug);

                    $stateAndPublicationPage = $this->stateValidator($_POST['publicationDate'], $_POST['state'] ?? null);
                    $page->setState($stateAndPublicationPage['state']);
                    $page->setPublicationDate($stateAndPublicationPage['publicationDate']);

                    if (!empty($page->getState())) {

                        $page->setTitle($_POST["title"]);
                        $page->setPosition(empty($_POST["position"]) ? "2" : $_POST["position"]);
                        $page->setTitleSeo(empty($_POST["titleSEO"]) ? "mon tire seo deuxième" : $_POST["titleSEO"]);
                        $page->setDescriptionSeo(empty($_POST["descriptionSEO"]) ? "ma description seo" : $_POST["descriptionSEO"]);
                        $page->setCreatedAt(Helpers::getCurrentTimestamp());
                        $save = $page->save();

                        if ($save) {
                            $response['message'] = 'La page a été créée !';
                            $response['success'] = true;
                        } else {
                            $response['message'] = 'Oulah Oops problème serveur sorry';
                            $response['success'] = false;
                        }
                    } else {
                        $response['message'] = 'Le statut choisi est incorrect';
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

    public function updateVisibilityAction()
    {
        if (isset($_POST['form'])) {

            if ($_POST['form'] == "changeVisibility") {

                if (isset($_POST['id']) && !empty($_POST['id'])) {
                    $page = new PageModel();
                    $page->setId($_POST['id']);

                    if ($page->getState() === "published") {
                        $page->setState('hidden');
                    } else if ($page->getState() === "hidden") {
                        $page->setState('published');
                    }

                    $page->save();
                }
            }
        }
    }

    public function updatePageAction($id)
    {
        $view = new View('pages/createPage');

        $page = new PageModel();
        $form = $page->formBuilderUpdate();
        $page->setId($id);

        if (!empty($_POST)) {

            //$errors = FormValidator::check($form, $_POST);
            $errors = [];

            if (empty($errors)) {

                if (empty($_POST['slug'])) {
                    $slug = Helpers::slugify($_POST['title']);
                } else {
                    $slug = htmlspecialchars($_POST['slug']);
                }

                $isNotUniqueSlug = $page->selectWhere('slug', $slug); // check unicity of slug
                if (count($isNotUniqueSlug) < 2) {

                    $page->setSlug($slug);

                    $stateAndPublicationPage = $this->stateValidator($_POST['publicationDate'], $_POST['state']??null);
                    $page->setState($stateAndPublicationPage['state']);
                    $page->setPublicationDate($stateAndPublicationPage['publicationDate']);

                    if (!empty($page->getState())) {

                        $page->setTitle($_POST["title"]);
                        $page->setPosition(empty($_POST["position"]) ? "2" : $_POST["position"]);
                        $page->setTitleSeo(empty($_POST["titleSEO"]) ? "mon tire seo deuxième" : $_POST["titleSEO"]);
                        $page->setDescriptionSeo(empty($_POST["descriptionSEO"]) ? "ma description seo" : $_POST["descriptionSEO"]);
                        $page->setCreatedAt(Helpers::getCurrentTimestamp());
                        $save = $page->save();

                        if ($save) {
                            $response['message'] = 'Votre page a bien été sauvegardée !';
                            $response['success'] = true;
                        } else {
                            $response['message'] = 'Oops, un problème serveur est survenu';
                            $response['success'] = false;
                        }
                    } else {
                        $response['message'] = 'Erreur : Le statut choisi est incorrect';
                        $response['success'] = false;
                    }
                } else {
                    $response['message'] = 'Erreur : Ce slug est déjà existant';
                    $response['success'] = false;
                }

                $view->assign('response', $response);

            }
        }

        //get page
        $arrayPage = $page->jsonSerialize();
        $arrayPage['publicationDate'] = date("Y-m-d\TH:i:s", strtotime($arrayPage['publicationDate'])); // format date

        // return view
        $view->assign('form', $form);
        $view->assign('data', $arrayPage);
        $view->assign('title', 'Modifier une page');
    }

    public function updatePageStateAction($state, $id){

        $page = new PageModel;
        $page->setId($id);

        if($state == "hidden")
        {
            $page->setStateToPublishedHidden();

        }else if ($state == "draft") 
        {
            $page->setStateToDraft();
        }

        $page->save();

        $this->showAllAction();
    }

    public function deletePageAction()
    {
        if (!empty($_POST['id'])) {
            $page = new PageModel();
            $id = $_POST['id'];
            $page->setId($id);

            if($page->getState() == "deleted")
            {
                $pageArticle = new PageArticle();
                $check = $pageArticle->hardDelete()->where('pageId', $id)->execute(); // delete foreing keys
              
                if($check){
                    $check = $page->delete();
                }
            }
            else {
                $page->setState("deleted");
                $page->delete();
                $page->save();
            }
        }
    }

    private function stateValidator($publicationDate, $state)
    {

        /* Set State */
        if (empty($publicationDate) && $_POST['state'] == 'draft') { // draft

            $state = 'draft';
            $publicationDate = null;
        } else if (!empty($_POST['publicationDate'])) { // scheduled

            $state = 'scheduled';
            $publicationDate = htmlspecialchars($_POST['publicationDate']);

        } else if ($_POST['state'] == 'published' && empty($_POST['publicationDate'])) { // published

            $state = 'published';
            $publicationDate = Helpers::getCurrentTimestamp();
        }

        return [
            'publicationDate' => $publicationDate,
            'state' => $state
        ];
    }
}