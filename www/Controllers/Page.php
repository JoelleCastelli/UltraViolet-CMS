<?php

namespace App\Controller;

use App\Core\Helpers;
use App\Core\View;
use App\Core\FormValidator;
use App\Models\Page as PageModel;

class Page
{

    protected array $columnsTable;

    public function __construct()
    {
        $this->columnsTable = [
            "title" => 'Nom de la page',
            "slug" => 'URL de la page',
            "position" => 'Ordre',
            "state" => 'Visibilité',
            "actions" => 'Actions'
        ];
    }

    public function showAllAction()
    {
        $page = new PageModel();
        $view = new View("pages/list");
        
        $now = Helpers::getCurrentTimestamp();
        $pages = $page->select()->where('publicationDate', $now, '<=')->andWhere('state', 'scheduled')->get();
        foreach ($pages as $page) {
            $page->setState("published");
            $page->save();
        }

        $view->assign('title', 'Pages');
        $view->assign('columnsTable', $this->columnsTable);
        $view->assign('bodyScripts', [PATH_TO_SCRIPTS . 'bodyScripts/pages/pages.js']);
    }

    public function showStaticPageAction($slug)
    {
        $page = new PageModel();
        $page = $page->select()->where('slug', $slug)->andWhere('state', 'published')->first();

        if(!empty($page)){
            $view = new View('staticPage', 'front');
            $view->assign('content', $page->getContent());
        }else {
            Helpers::redirect404();
        }

    }

    public function getPagesAction()
    {
        if (!empty($_POST['pageType'])) {
            $pageModel = new PageModel();

            // get pages
            if ($_POST['pageType'] === 'published') {
                $pages = $pageModel->select()
                                    ->where('state', htmlspecialchars($_POST['pageType']))
                                    ->orWhere('state', 'hidden')
                                    ->orderBy('position')
                                    ->get();

            } else {
                $pages = $pageModel->select()
                                    ->where('state', htmlspecialchars($_POST['pageType']))
                                    ->orderBy('position')
                                    ->get();
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

    public function createPageAction()
    {
        $page = new PageModel();
        $form = $page->formBuilderRegister();

        $view = new View('pages/create');
        $view->assign('title', 'Créer une page');
        $view->assign('form', $form);
        $view->assign('bodyScripts', [PATH_TO_SCRIPTS . 'bodyScripts/pages/pages.js', PATH_TO_SCRIPTS . 'bodyScripts/tinymce.js']);

        if (!empty($_POST)) {

            $errors = FormValidator::check($form, $_POST);

            if (empty($errors)) {

                $slug = empty($_POST['slug']) ? Helpers::slugify($_POST['title']) : $_POST['slug'];
                $publicationDate  = empty($_POST["publicationDate"]) ? null : $_POST["publicationDate"];
                $isNotUniqueSlug = $page->selectWhere('slug', $slug); // check unicity of slug
                
                if (!empty($isNotUniqueSlug)) {
                    $errors[] = 'Ce slug est déjà existant';
                }

                if(empty($errors)){

                    $this->stateValidator($page, $publicationDate, $_POST['state'] ?? null);
                    $page->setSlug($slug);
                    $page->setTitle($_POST["title"]);
                    $page->setPosition($_POST["position"]);
                    $page->setTitleSeo($_POST["titleSeo"]);
                    $page->setContent($_POST["content"]);
                    $page->setDescriptionSeo($_POST["descriptionSeo"]);
                    $page->setCreatedAt(Helpers::getCurrentTimestamp());
                    $save = $page->save();

                    if (!$save) 
                        $errors[] = 'Oops ! Un soucis lors de la sauvegarde est survenu, veuillez recommencer svp';
                    
                    if(empty($errors)) {

                        Helpers::setFlashMessage('success', 'La page s\'est bien créée');
                        Helpers::redirect(Helpers::callRoute('pages_list'));

                    }
                }
            }
            
            $view->assign('errors', $errors);
        }
    }

    public function updatePageAction($id)
    {
        $page = new PageModel();

         // if page not exist
        if (empty($page->findOneBy("id", $id))) {
            Helpers::redirect(Helpers::callRoute("articles_list"));
        }

        $form = $page->formBuilderUpdate($id);

        //get page
        $page->setId($id);
        if($page->getPublicationDate() != null)
            $page->cleanPublicationDate();
        $arrayPage = $page->jsonSerialize();

        $view = new View('pages/update');
        $view->assign('form', $form);
        $view->assign('data', $arrayPage);
        $view->assign('title', 'Modifier la page n° ' . $page->getId());
        $view->assign('bodyScripts', [PATH_TO_SCRIPTS . 'bodyScripts/pages/pages.js', PATH_TO_SCRIPTS . 'bodyScripts/tinymce.js']);

        if (!empty($_POST)) {

            $errors = FormValidator::check($form, $_POST);

                if(empty($errors)) {

                $slug = empty($_POST['slug']) ? Helpers::slugify($_POST['title']) : $_POST['slug'];
                $publicationDate  = empty($_POST["publicationDate"]) ? null : $_POST["publicationDate"];
                $isNotUniqueSlug = $page->select()->where('slug', $slug)->andWhere('id', $id, "!=")->get(); // check unicity of slug

                if (count($isNotUniqueSlug) !== 0)
                    $errors[] = 'Ce slug est déjà existant';

                if (empty($errors)) {

                    $this->stateValidator($page, $publicationDate, $_POST['state'] ?? null);
                    $page->setSlug($slug);
                    $page->setTitle($_POST["title"]);
                    $page->setPosition($_POST["position"]);
                    $page->setTitleSeo($_POST["titleSeo"]);
                    $page->setDescriptionSeo($_POST["descriptionSeo"]);
                    $page->setContent($_POST["content"]);

                    $save = $page->save();

                    if (!$save)
                        $errors[] = 'Oops, un problème serveur est survenu, veuillez recommencer s\'il vous plaît';

                    if(empty($errors)){
                        Helpers::setFlashMessage('success', 'Votre page a bien été modifiée !');
                        Helpers::redirect(Helpers::callRoute('page_update', ['id' => $id]));
                    }
                } 
            }
            $view->assign('errors', $errors);
        }
    }

    public function updateVisibilityAction()
    {
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

    public function updatePageStateAction(){

        $page = new PageModel;
        $response = [];

        // Id validator : set and number
        // state validator : set and correct
        // page validator : if exist
        if(!((isset($_POST['id']) && isset($_POST['state']) && is_numeric($_POST['id']) && 
            ($_POST['state'] === "hidden" || $_POST['state'] === "draft" ) && 
            !empty($page->findOneBy('id', $_POST['id']))))){

            $response['message'] = "Une erreur est survenu";
            $response['success'] = false;
        }

        if (empty($response)) { 

            $page->setId($_POST['id']);
            $state = $_POST['state'];

            if ($state === "hidden") {
                $page->setStateToPublishedHidden();
                $response['message'] = "La page a bien été modifiée";
                $response['success'] = true;
            } else if ($state === "draft") {
                $page->setStateToDraft();
                $response['message'] = "La page a bien été modifiée";
                $response['success'] = true;
            }

            $page->save();

        }  
        echo json_encode($response);
    }

    public function deletePageAction()
    {
        if (!empty($_POST['id'])) {
            $page = new PageModel();
            $id = $_POST['id'];
            $page->setId($id);

            if($page->getState() == "deleted")
            {
                $check = $page->delete();
            }
            else {
                $page->setState("deleted");
                $check = $page->delete();
            }
        }
    }

    private function stateValidator(&$page, $publicationDate, $state)
    {

        /* Set State */
        if ($state == 'draft') { // draft
            $page->setStateToDraft();

        } else if ($state == "scheduled") { // scheduled
            $page->setStateToScheduled($publicationDate);

        } else if ($state == 'published' ) { // published
            $page->setStateToPublished();

        } else if ($state == "hidden") {
            $page->setStateToPublishedHidden();
        }
    }

}