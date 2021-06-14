<?php

namespace App\Controller;

use App\Core\Helpers;
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
            $pageModel = new PageModel();
            
            // get pages
            if ($_POST['pageType'] === 'published' ){
                $pages = $pageModel->selectWhere('state', htmlspecialchars($_POST['pageType']));
                $pages = array_merge($pages, $pageModel->selectWhere('state', 'hidden'));

            }else {
                $pages = $pageModel->selectWhere('state', htmlspecialchars($_POST['pageType']));
            }
            
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
                    $this->columnsTable['state'] => $page->getState() == "hidden" ? 
                                        '<div id="page-visibilty-' . $page->getId() . '" class="state-switch switch-visibily-page" onclick="toggleSwitch(this)"></div>' 
                                        : '<div id="page-visibilty-' . $page->getId() . '" class="state-switch switched-on switch-visibily-page" onclick="toggleSwitch(this)"></div>',
                    $this->columnsTable['actions'] => $page->generateActionsMenu()
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

            //$errors = FormValidator::check($form, $_POST);
            $errors = '';

            if (empty($errors)) {

                /* VERIFICATIONS AND CLEAN UP SLUG */
                if (empty($_POST['slug'])) // if slug not specify
                {
                    $patterns[] = "/[^A-Za-z0-9\-]/"; // only alphabets, numbers and dash
                    $patterns[] = "/^-+/"; // begin
                    $patterns[] = "/-+$/"; // end
                    $patterns[] = "/\s+/"; // space
                    $patterns[] = "/-+/"; // only one dash
                    
                    $replacement[] = "";
                    $replacement[] = "";
                    $replacement[] = "";
                    $replacement[] = "-";
                    $replacement[] = "-";

                    $unwantedCharacters= array(
                        'Š' => 'S', 'š' => 's', 'Ž' => 'Z', 'ž' => 'z', 'À' => 'A', 'Á' => 'A', 'Â' => 'A', 'Ã' => 'A', 'Ä' => 'A', 'Å' => 'A', 'Æ' => 'A', 'Ç' => 'C', 'È' => 'E', 'É' => 'E',
                        'Ê' => 'E', 'Ë' => 'E', 'Ì' => 'I', 'Í' => 'I', 'Î' => 'I', 'Ï' => 'I', 'Ñ' => 'N', 'Ò' => 'O', 'Ó' => 'O', 'Ô' => 'O', 'Õ' => 'O', 'Ö' => 'O', 'Ø' => 'O', 'Ù' => 'U',
                        'Ú' => 'U', 'Û' => 'U', 'Ü' => 'U', 'Ý' => 'Y', 'Þ' => 'B', 'ß' => 'Ss', 'à' => 'a', 'á' => 'a', 'â' => 'a', 'ã' => 'a', 'ä' => 'a', 'å' => 'a', 'æ' => 'a', 'ç' => 'c',
                        'è' => 'e', 'é' => 'e', 'ê' => 'e', 'ë' => 'e', 'ì' => 'i', 'í' => 'i', 'î' => 'i', 'ï' => 'i', 'ð' => 'o', 'ñ' => 'n', 'ò' => 'o', 'ó' => 'o', 'ô' => 'o', 'õ' => 'o',
                        'ö' => 'o', 'ø' => 'o', 'ù' => 'u', 'ú' => 'u', 'û' => 'u', 'ý' => 'y', 'þ' => 'b', 'ÿ' => 'y'
                    );
               
                    $slug = strtr($_POST["title"], $unwantedCharacters);
                    $slug = preg_replace($patterns, $replacement, $slug);
                    $slug = strtolower($slug);
                   
                } else { // if slug specify

                    $slug = htmlspecialchars($_POST["slug"]); 
                }
                $isNotUniqueSlug = $page->selectWhere('slug', $slug); // check unicity of slug
                
                if(empty($isNotUniqueSlug)) {
                    
                    $page->setSlug($slug);

                    /* Set State */
                    if(empty($_POST['publicationDate']) && $_POST['state'] == 'draft') { // draft

                        $page->setState('draft');
                        $page->setPublicationDate(null);
                        
                    } else if(!empty($_POST['publicationDate'])) { // scheduled

                        $page->setState('scheduled');
                        $page->setPublicationDate(htmlspecialchars($_POST['publicationDate']));
                        
                    }else if ($_POST['state'] == 'published' && empty($_POST['publicationDate'])) { // published
                        
                        $page->setState('published');
                        $page->setPublicationDate(Helpers::getCurrentTimestamp());
                    }

                    if (!empty($page->getState())) {

                        $page->setTitle($_POST["title"]);
                        $page->setPosition(empty($_POST["position"]) ? "2" : $_POST["position"]);
                        $page->setTitleSeo(empty($_POST["titleSEO"]) ? "mon tire seo deuxième" : $_POST["titleSEO"]);
                        $page->setDescriptionSeo(empty($_POST["descriptionSEO"]) ? "ma description seo" : $_POST["descriptionSEO"]);
                        $page->setCreatedAt(Helpers::getCurrentTimestamp());
                        $save= $page->save();

                        if($save) {
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

    public function updatePageAction()
    {
        if(isset($_POST['form'])){

                        
            if($_POST['form'] == "changeVisibility")
            {

                if(isset($_POST['id']) && !empty($_POST['id']))
                {
                    $page = new PageModel();
                    $page->setId($_POST['id']);
                    
                    if($page->getState() === "published"){
                        $page->setState('hidden');
                    }else if ($page->getState() === "hidden"){
                        $page->setState('published');
                    }
                    
                    $page->save();
                }

            }

        }
    }
}