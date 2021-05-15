<?php

namespace App\Controller;

use App\Core\FormValidator;
use App\Core\Helpers;
use App\Core\View;
use App\Models\Production as ProductionModel;

class Production
{


    protected $columnsTable;

    public function __construct() {
        $this->columnsTable = [
            "title" => 'Titre',
            "originalTitle" => 'Titre original',
            "releaseDate" => 'Date de sortie',
            "runtime" => 'Durée',
            "overview" => 'Résumé',
            "actions" => 'Actions'
        ];
    }

    public function showAllAction() {
        $productions = new ProductionModel();
        $productions = $productions->findAll();

        if(!$productions) $productions = [];

        foreach ($productions as $production) {
            $production->cleanReleaseDate();
            $production->translateType();
            $production->cleanRuntime();
        }

        $view = new View("productions/list");
        $view->assign("productions", $productions);
        $view->assign('title', 'Productions');
        $view->assign('columnsTable', $this->columnsTable);
        $view->assign('headScript', Helpers::urlJS('headScripts/productions'));
    }

    public function addProductionAction() {
        $production = new ProductionModel();
        $form = $production->formBuilderAddProduction();
        $view = new View("productions/add-production");
        $view->assign("form", $form);

        if(!empty($_POST)) {
            $errors = FormValidator::check($form, $_POST);

            if(empty($errors)) {
                // Mandatory
                $production->setTitle(htmlspecialchars($_POST["title"]));
                $production->setType(htmlspecialchars($_POST["type"]));

                // Optional
                $production->setOriginalTitle(htmlspecialchars($_POST["originalTitle"]) ?? '');
                $production->setReleaseDate(htmlspecialchars($_POST["releaseDate"]) ?? '');
                $production->setOverview(htmlspecialchars($_POST["overview"]) ?? '');
                $production->setRuntime(htmlspecialchars($_POST["runtime"]) ?? '');
                $production->setNumber(htmlspecialchars($_POST["number"]) ?? '');

                $production->save();
            } else {
                $view->assign("errors", $errors);
            }
        }
    }

    public function addProductionTmdbAction() {
        $production = new ProductionModel();
        $form = $production->formBuilderAddProductionTmdb();
        $view = new View("productions/add-production-tmdb");
        $view->assign("form", $form);
        $view->assign("title", "Ajout d'une production");
        $view->assign('headScripts', [PATH_TO_SCRIPTS.'headScripts/addProduction.js']);
    }

    public function tmdbRequestAction() {
        if(!empty($_POST['productionType']) && !empty($_POST['productionID'])) {
            if($_POST['productionType'] === 'movie' && (!empty($_POST['seasonNb']) || !empty($_POST['episodeNb']))) {
                echo "Un film ne peut pas avoir de numéro de saison ou d'épisode";
            } else {
                $urlArray = $this->getTmdbUrl($_POST);
                $jsonResponseArray = $this->getApiResponse($urlArray);
                if (!empty($jsonResponseArray)) {
                    $this->showProductionPreview($_POST, $jsonResponseArray);
                } else {
                    echo "La recherche ne correspond à aucun résultat sur TMDB";
                }
            }
        } else {
            echo "Un type et un ID de film ou de série sont nécessaires";
        }
    }

    public function showProductionPreview($post, $jsonResponseArray) {
        // index 0: movie or series
        $item = json_decode($jsonResponseArray[0]);
        // index 1 if it exists: episode
        if(isset($jsonResponseArray[1]))
            $episode = json_decode($jsonResponseArray[1]);

        $production['idTmdb'] = $item->id;
        $production['productionType'] = $post['productionType'];
        $production['title'] = $item->title ?? $item->name;
        $production['originalTitle'] = $item->original_title ?? $item->original_name;
        $production['overview'] = $item->overview;
        $production['genres'] = $item->genres;
        $production['cast'] = $item->credits->cast;
        $production['image'] = "<img src='https://image.tmdb.org/t/p/w200$item->poster_path' />";
        $production['releaseDate'] = $item->release_date ?? $item->first_air_date;
        $production['runtime'] = $item->runtime ?? $item->episode_run_time[0];

        switch ($post['productionType']) {
            case 'movie':
                $production['directors'] = '';
                $production['writers'] = '';
                foreach ($item->credits->crew as $crew) {
                    if ($crew->job == 'Director' || $crew->job == 'Screenplay') {
                        if ($crew->job == 'Director') {
                            $production['directors'] .= $crew->name;
                        } else {
                            $production['writers'] .= $crew->name;
                        }
                    }
                }
                break;
            case 'series':
                $production['nbSeasons'] = $item->seasons[0]->name === "Épisodes spéciaux" ? sizeof($item->seasons) - 1: sizeof($item->seasons);
                $production['nbEpisodes'] = 0;
                foreach ($item->seasons as $season) {
                    $production['nbEpisodes'] += $season->episode_count;
                }
                $production['creators'] = '';
                foreach ($item->created_by as $creator) {
                    $production['creators'] .= $creator->name.' ';
                }
                // Season
                if(!empty($_POST['seasonNb']) && isset($item->seasons[$post['seasonNb']])) {
                    $production['nbEpisodes'] = $item->seasons[$post['seasonNb']]->episode_count;
                    $production['image'] = "<img src='https://image.tmdb.org/t/p/w200".$item->seasons[$post['seasonNb']]->poster_path."' />";
                    $production['overview'] = $item->seasons[$post['seasonNb']]->overview;
                    // Episode
                    if(!empty($episode)) {
                        $production['title'] = $episode->name;
                        $production['overview'] = $episode->overview;
                        $production['image'] = "<img src='https://image.tmdb.org/t/p/w200$episode->still_path' />";
                        $production['releaseDate'] = $episode->air_date;
                    }
                } else {
                    echo "La saison existe pas";
                }
                break;
        }


        foreach ($production as $key => $value) {
            if($key === 'genres') {
                echo "- $key : ";
                foreach ($value as $id => $genre) {
                    echo "$genre->name ";
                }
            } elseif ($key === 'cast') {
                $count = 1;
                foreach ($value as $id => $cast) {
                    if($count++ <= 3) {
                        echo "<div>$cast->name joue le rôle de $cast->character<br>";
                        echo "<img src='https://image.tmdb.org/t/p/w200$cast->profile_path' /></div>";
                    }
                }
            } else {
                echo "<div>- $key : $value</div>";
            }
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

    public function getProductionsAction() {

        if(empty($_POST['productionType'])) // get all productions
        {
            $productions = new ProductionModel();
            $productions = $productions->findAll();

            if(!$productions) $productions = [];

        }else { // get productions by type

            $production = new ProductionModel();
            $productions = $production->selectWhere('type',htmlspecialchars($_POST['productionType']));

        }

        // populate in arrays
        $productionArray = [];

        foreach ($productions as $production) {
            $production->cleanReleaseDate();
            $production->translateType();
            $production->cleanRuntime();

            /*$productionArray[] = array(
                "id" => $production->getId(),
                "title" => $production->getTitle(),
                "originalTitle" => $production->getOriginalTitle(),
                "overview" => $production->getOverview(),
                "releaseDate" => $production->getReleaseDate(),
                "number" => $production->getNumber(),
                "type" => $production->getType(),
                "runtime" => $production->getRuntime(),
                "createdAt" => $production->getCreatedAt(),
                "delatedAt" => $production->getDeletedAt(),
                "tmdbId" => $production->getTmdbId(),
            );*/

            $productionArray[] = array(
                $this->columnsTable['title'] => $production->getTitle(),
                $this->columnsTable['originalTitle'] => $production->getOriginalTitle(),
                $this->columnsTable['overview'] => $production->getOverview(),
                $this->columnsTable['releaseDate'] => $production->getReleaseDate(),
                $this->columnsTable['runtime'] => $production->getRuntime(),
            );
        }

        // send data
        $data = array(
            "productions" => $productionArray,
            "columns" => $this->columnsTable,
            "type" => $_POST['productionType']??""
        );

        echo json_encode($data);

    }


}