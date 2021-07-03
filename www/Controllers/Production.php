<?php

namespace App\Controller;

use App\Core\FormValidator;
use App\Core\Helpers;
use App\Core\MediaManager;
use App\Core\View;
use App\Models\Production as ProductionModel;
use App\Models\ProductionArticle;
use App\Models\ProductionPerson;
use App\Models\ProductionMedia;

class Production
{

    protected $columnsTable;

    public function __construct() {
        $this->columnsTable = [
            "thumbnail" => 'Miniature',
            "title" => 'Titre',
            "originalTitle" => 'Titre original',
            "season" => 'Saison',
            "series" => 'Série',
            "runtime" => 'Durée',
            "releaseDate" => 'Date de sortie',
            "createdAt" => 'Date d\'ajout',
            "actions" => 'Actions'
        ];
    }

    public function showAllAction() {
        $view = new View("productions/list");
        $view->assign('title', 'Productions');
        $view->assign('columnsTable', $this->columnsTable);
        $view->assign('headScripts', [PATH_TO_SCRIPTS.'headScripts/productions/productions.js']);
    }

    /**
     * Called by AJAX script to display productions filtered by type
     */
    public function getProductionsAction() {
        if(!empty($_POST['productionType'])) {
            $productions = new ProductionModel();
            $productions = $productions->select()->where('type', htmlspecialchars($_POST['productionType']))
                                                 ->andWhere('deletedAt', "NULL")
                                                 ->orderBy('createdAt', 'DESC')
                                                 ->get();
            if(!$productions) $productions = [];

            $productionArray = [];
            foreach ($productions as $production) {
                // Find production key art media and populate poster object
                $productionMedia = new ProductionMedia();
                $productionMedia = $productionMedia->select()->where('productionId', $production->getId())->andWhere('keyArt', 1)->first();
                if($productionMedia) {
                    $production->getPoster()->setId($productionMedia->getMediaId());
                    // Display default image if file is not found
                    if(file_exists(getcwd().$production->getPoster()->getPath()))
                        $path = $production->getPoster()->getPath();
                    else
                        $path = PATH_TO_IMG.'default_poster.jpg';
                } else {
                    // Display default image if no key art is associated
                    $path = PATH_TO_IMG.'default_poster.jpg';
                }

                $productionArray[] = [
                    $this->columnsTable['title'] => $production->getTitle(),
                    $this->columnsTable['thumbnail'] => "<img class='thumbnail' src='".$path."'/>",
                    $this->columnsTable['originalTitle'] => $production->getOriginalTitle(),
                    $this->columnsTable['season'] => $production->getParentSeasonName(),
                    $this->columnsTable['series'] => $production->getParentSeriesName(),
                    $this->columnsTable['runtime'] => $production->getCleanRuntime(),
                    $this->columnsTable['releaseDate'] => $production->getCleanReleaseDate(),
                    $this->columnsTable['createdAt'] => $production->getCleanCreatedAt(),
                    $this->columnsTable['actions'] => $production->generateActionsMenu(),
                ];
            }
            echo json_encode($productionArray);
        }
    }

    /**
     * Manually add a production in the database
     */
    public function addProductionAction() {
        // Generate form
        $production = new ProductionModel();
        $form = $production->formBuilderAddProduction();
        $view = new View("productions/add-production");
        $view->assign('title', 'Nouvelle production manuelle');
        $view->assign('headScripts', [PATH_TO_SCRIPTS.'headScripts/productions/addProductionManual.js']);
        $view->assign("form", $form);

        // If form is submitted, check the data and save production
        if(!empty($_POST)) {
            $errors = FormValidator::check($form, $_POST);
            if(empty($errors)) {
                // Set object values
                $production->setType(htmlspecialchars($_POST['type']));
                $production->setTitle(htmlspecialchars($_POST['title']));
                $production->setOriginalTitle(htmlspecialchars($_POST['originalTitle']));
                $production->setReleaseDate(htmlspecialchars($_POST['releaseDate']));
                $production->setOverview(htmlspecialchars($_POST['overview']));
                if($_POST['runtime'] != '')     // runtime only accepts integer, no empty string
                    $production->setRuntime(htmlspecialchars($_POST['runtime']));
                if($_FILES['poster']) {
                    // If an image is submitted, upload file and update production poster object
                    $mediaManager = new MediaManager();
                    $check = $mediaManager->check($_FILES["poster"], $production->getType());
                    if(empty($check)) {
                        $mediaManager->uploadFile($mediaManager->getFiles());
                        $production->getPoster()->setPath(getcwd().PATH_TO_IMG_POSTERS.$production->getType().'/'.htmlspecialchars($mediaManager->getFiles()[0]['path']));
                    } else {
                        $errors[] = "Le fichier n'a pas pu être téléchargé, la production n'est pas sauvegardée";
                    }
                }
                $production->save();
                Helpers::setFlashMessage('success', "La production ".$_POST["title"]." a bien été ajoutée à la base de données.");
                Helpers::redirect(Helpers::callRoute('productions_list'));
            }
            $view->assign("errors", $errors);
        }
    }

    public function addProductionTmdbAction() {
        $production = new ProductionModel();
        $form = $production->formBuilderAddProductionTmdb();
        $view = new View("productions/add-production-tmdb");
        $view->assign("form", $form);
        $view->assign("title", "Ajout d'une production");
        $view->assign('headScripts', [PATH_TO_SCRIPTS.'headScripts/productions/addProduction.js']);

        if(!empty($_POST)) {
            if(!isset($_POST['seasonNb'])) { $_POST['seasonNb'] = ''; }
            if(!isset($_POST['episodeNb'])) { $_POST['episodeNb'] = ''; }
            if(!isset($_POST['productionPreviewRequest'])) { $_POST['productionPreviewRequest'] = ''; }
            $errors = FormValidator::check($form, $_POST);
            if(empty($errors)) {
                if($_POST['productionType'] === 'movie' && (!empty($_POST['seasonNb']) || !empty($_POST['episodeNb']))) {
                    $errors[] = "Un film ne peut pas avoir de numéro de saison ou d'épisode";
                } else {
                    $urlArray = $this->getTmdbUrl($_POST);
                    $jsonResponseArray = $this->getApiResponse($urlArray);
                    if (!empty($jsonResponseArray)) {
                        $production = new ProductionModel();
                        if($production->populateFromTmdb($_POST, $jsonResponseArray)) {
                            if ($production->findOneBy('tmdbId', $production->getTmdbId())) {
                                $errors[] = "La production avec l'ID TMDB ".$production->getTmdbId()." existe déjà dans la base de données";
                            } else {
                                $production->save();
                                Helpers::setFlashMessage('success', "La production ".$_POST["title"]." a bien été ajoutée à la base de données.");
                                Helpers::redirect(Helpers::callRoute('productions_list'));
                            }
                        } else {
                            echo "Problème dans la récupération de données TMDB";
                        }
                    } else {
                        $errors[] = "La recherche ne correspond à aucun résultat sur TMDB";
                    }
                }
            }
            $view->assign("errors", $errors);
        }
    }

    public function ajaxShowPreviewAction() {
        if(!empty($_POST['productionType']) && !empty($_POST['productionID'])) {
            if($_POST['productionType'] === 'movie' && (!empty($_POST['seasonNb']) || !empty($_POST['episodeNb']))) {
                echo "<p class='error-message-form'>Un film ne peut pas avoir de numéro de saison ou d'épisode</p>";
            } else {
                $urlArray = $this->getTmdbUrl($_POST);
                $jsonResponseArray = $this->getApiResponse($urlArray);
                if (!empty($jsonResponseArray)) {
                    $production = new ProductionModel();
                    if($production->populateFromTmdb($_POST, $jsonResponseArray))
                        $production->displayPreview();
                } else {
                    echo "<p class='error-message-form'>La recherche ne correspond à aucun résultat sur TMDB</p>";
                }
            }
        } else {
            echo "<p class='error-message-form'>Un type et un ID de film ou de série sont nécessaires</p>";
        }
    }

    public function getTmdbUrl($data){
        if(!$data['productionType'] || !$data['productionID']) return false;
        $urlArray = [];
        switch ($data['productionType']) {
            case 'movie':
                $urlArray['movie'] = TMDB_API_URL . 'movie/' . $data['productionID'].'?api_key=' . TMDB_API_KEY;
                break;
            case 'series':
                // If an episode number is specified, check that season is also specified
                if(!empty($data['episodeNb']) && empty($data['seasonNb'])) return false;
                // Series or Season
                $urlArray['series'] = TMDB_API_URL . 'tv/' . $data['productionID'].'?api_key='.TMDB_API_KEY;
                // Episode
                if(!empty($data['episodeNb'])) {
                    $urlArray['episode'] = TMDB_API_URL . '/tv/' . $data['productionID'] . '/season/' . $data['seasonNb'] . '/episode/'. $data['episodeNb'] . '?api_key=' . TMDB_API_KEY;
                }
                break;
        }

        // French results + credits
        foreach ($urlArray as $type => $url) {
            $urlArray[$type] = $url."&language=fr&append_to_response=credits";
        }

        return $urlArray;
    }

    public function getApiResponse($urlArray){
        $results = [];
        if($urlArray) {
            foreach ($urlArray as $url) {
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // Résultat de curl_exec() = string au lieu de l'afficher
                curl_setopt($ch, CURLOPT_FAILONERROR, 1); // Echoue verbalement si code HTTP >= 400
                if (curl_exec($ch)) {
                    $results[] = curl_exec($ch);
                    curl_close($ch);
                } else {
                    curl_close($ch);
                    return false;
                }
            }
            if(empty($results)) return false;
            return $results;
        }
        return false;
    }

    public function updateProductionAction($id) {
        $production = new ProductionModel();
        $form = $production->formBuilderUpdateProduction($id);
        $view = new View("productions/update");
        $view->assign('title', 'Modifier une production');
        $view->assign("form", $form);

        if(!empty($_POST)) {
            $errors = FormValidator::check($form, $_POST);

            if(empty($errors)) {
                // Dynamic setters
                foreach ($_POST as $key => $value) {
                    if ($key !== 'csrfToken' && $value !== '') {
                        if(!empty($value)) {
                            $functionName = "set".ucfirst($key);
                            $production->$functionName(htmlspecialchars($value));
                        }
                    }
                }
                $production->save();
                Helpers::setFlashMessage('success', "La production ".$_POST["title"]." a bien été mise à jour");
                Helpers::redirect(Helpers::callRoute('productions_list'));
                
            } else {
                $view->assign("errors", $errors);
            }
        }
    }

    public function deleteProductionAction() {
        if(!empty($_POST['productionId'])) {
            $response = [];
            // Check if articles are linked to the production
            $articles = new ProductionArticle;
            $articles = $articles->select()->where('articleId', $_POST['productionId'])->get();
            if(empty($articles)) {
                // check if there are child productions
                $childProductions = new ProductionModel();
                $childProductions = $childProductions->select()->where('parentProductionId', $_POST['productionId'])->get();
                if(empty($childProductions)) {
                    // delete productionMedia
                    $productionMedias = new ProductionMedia;
                    $productionMedias->hardDelete()->where('productionId', $_POST['productionId'])->execute();

                    // delete productionPerson
                    $productionPersons = new ProductionPerson;
                    $productionPersons->hardDelete()->where('productionId', $_POST['productionId'])->execute();

                    // delete Production
                    $production = new ProductionModel();
                    $production->hardDelete()->where('id', $_POST['productionId'])->execute();

                    $response['success'] = true;
                    $response['message'] = 'La production a bien été supprimée';
                } else {
                    $response['success'] = false;
                    $response['message'] = "La production ne peut pas être supprimée : d'autres productions y sont rattachées (saisons ou épisodes)";
                }
            } else {
                $response['success'] = false;
                $response['message'] = "La production ne peut pas être supprimée : des articles y sont rattachés";
            }
            echo json_encode($response);
        }
    }
    
}