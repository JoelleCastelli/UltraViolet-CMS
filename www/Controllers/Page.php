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

    public function accessPageAction()
    {
        echo "YO";
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

    public function createPageAction()
    {
        $page = new PageModel();
        $form = $page->formBuilderRegister();
        $response = [];

        if (!empty($_POST)) {

            $errors = FormValidator::check($form, $_POST);

            if (empty($errors)) {
                
                $slug = empty($_POST['slug']) ? Helpers::slugify($_POST['title']) : $_POST['slug'];
                $publicationDate  = empty($_POST["publicationDate"]) ? null : $_POST["publicationDate"];
                $isNotUniqueSlug = $page->selectWhere('slug', $slug); // check unicity of slug

                if (empty($isNotUniqueSlug)) {
                     
                    $this->stateValidator($page, $publicationDate, $_POST['state'] ?? null);

                    $page->setSlug($slug);
                    $page->setTitle($_POST["title"]);
                    $page->setPosition($_POST["position"]);
                    $page->setTitleSeo($_POST["titleSeo"]);
                    $page->setDescriptionSeo($_POST["descriptionSeo"]);
                    $page->setCreatedAt(Helpers::getCurrentTimestamp());
                    $save = $page->save();

                    if ($save) {

                        if($page->getState() === 'published') // if published, add route to file
                        {
                            $id = $page->getLastInsertId();
                            $key = $page->getSlug() . '-page-' . $id;
                            $path[$key]['path'] = '/' . $page->getSlug();
                            $path[$key]['controller'] = 'Page';
                            $path[$key]['action'] = 'accessPage';
                            $this->writePathInRouteFile($path, $id);
                        }

                        $response['message'] = 'La nouvelle s\'est bien crée';
                        $response['success'] = true;
                    } else {
                        $response['message'] = 'Oops ! Le seveur vient de rencontrer un problème durant la sauvegarde, veuillez recommencer.';
                        $response['success'] = false;
                    }

                } else {
                    $response['message'] = 'Le slug est déjà existant, veuillez en choisir un autre svp ';
                    $response['success'] = false;
                }
            } else {
                $response['message'] = $errors;
                $response['success'] = false;
            }
        }

        echo json_encode($response);
    }

    public function updatePageAction($id)
    {
        $page = new PageModel();
        $view = new View('pages/updatePage');

        // if page not exist
        if (empty($page->findOneBy("id", $id))) {
            Helpers::redirect(Helpers::callRoute("articles_list"), "404");
        }

        $form = $page->formBuilderUpdate($id);
        $page->setId($id);

        if (!empty($_POST)) {

            $errors = FormValidator::check($form, $_POST);

            if (empty($errors)) {

                $slug = empty($_POST['slug']) ? Helpers::slugify($_POST['title']) : $_POST['slug'];
                $publicationDate  = empty($_POST["publicationDate"]) ? null : $_POST["publicationDate"];
                $isNotUniqueSlug = $page->select()->where('slug', $slug)->andWhere('id', $id, "!=")->get(); // check unicity of slug

                if (count($isNotUniqueSlug) === 0) {
                    $this->stateValidator($page, $publicationDate, $_POST['state'] ?? null);

                    $page->setSlug($slug);
                    $page->setTitle($_POST["title"]);
                    $page->setPosition($_POST["position"]);
                    $page->setTitleSeo($_POST["titleSeo"]);
                    $page->setDescriptionSeo($_POST["descriptionSeo"]);

                    $save = $page->save();

                    if ($save) {
                        $this->updatePathInRouteFile($page, $page->getId());

                        $response['message'] = 'Votre page a bien été modifiée !';
                        $response['success'] = true;
                    } else {
                        $response['message'] = 'Oops, un problème serveur est survenu, veuillez recommencer s\'il vous plaît';
                        $response['success'] = false;
                    }

                } else {
                    $response['message'] = 'Erreur : Ce slug est déjà existant';
                    $response['success'] = false;
                }
                
            }else {
                $response['message'] = $errors;
                $response['success'] = false;
            }
            $view->assign('response', $response);

        }

        //get page
        if($page->getPublicationDate() != null)
            $page->cleanPublicationDate();
        $arrayPage = $page->jsonSerialize();

        // return view
        $view->assign('form', $form);
        $view->assign('data', $arrayPage);
        $view->assign('title', 'Modifier la page n° ' . $page->getId());
        $view->assign('bodyScripts', [PATH_TO_SCRIPTS . 'bodyScripts/pages/pages.js']);
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
                        $this->updatePathInRouteFile($page, $page->getId());

                    } else if ($page->getState() === "hidden") {
                        $page->setState('published');
                        $this->updatePathInRouteFile($page, $page->getId());
                    }

                    $page->save();
                }
            }
        }
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
                    if($check)
                    {
                        $key = $page->getSlug() . '-page-' . $id;
                        $this->deletePathInRouteFile($key);
                    }
                }
            }
            else {
                $page->setState("deleted");
                $check = $page->delete();

                if ($check) {
                    $key = $page->getSlug() . '-page-' . $id;
                    $this->deletePathInRouteFile($key);
                }
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

    private function updatePathInRouteFile($page, $id)
    {
        $key = $page->getSlug() . '-page-' . $id;
        $this->deletePathInRouteFile($key);

        if ($page->getState() === 'published') // if published, add route to file
        {
            $path[$key]['path'] = '/' . $page->getSlug();
            $path[$key]['controller'] = 'Page';
            $path[$key]['action'] = 'accessPage';
            $this->writePathInRouteFile($path);
        }
    }

    private function writePathInRouteFile($path)
    {
        $key = key($path);

        if(!file_exists(PATH_TO_ROUTES))
            $file = fopen(PATH_TO_ROUTES, 'a+');

        $routes = yaml_parse_file(PATH_TO_ROUTES);
        $routes[$key] = $path[$key];
        yaml_emit_file(PATH_TO_ROUTES, $routes, YAML_UTF8_ENCODING);

        if(isset($file))
            fclose($file);
    }

    private function deletePathInRouteFile($key)
    {
        if (!file_exists(PATH_TO_ROUTES))
            return;

        $routes = yaml_parse_file(PATH_TO_ROUTES);

        if(key_exists($key, $routes))
            unset($routes[$key]);

        yaml_emit_file(PATH_TO_ROUTES, $routes, YAML_UTF8_ENCODING);
    }
}