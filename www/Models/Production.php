<?php

namespace App\Models;

use App\Core\Database;
use App\Core\FormBuilder;
use App\Core\Helpers;
use App\Core\View;

class Production extends Database
{
    private ?int $id = null;
    protected ?int $tmdbId;
    protected string $title;
    protected ?string $originalTitle;
    protected ?string $releaseDate;
    protected string $type;
    protected ?string $overview;
    protected ?int $runtime;
    protected ?int $number;
    protected ?int $totalSeasons;
    protected ?int $totalEpisodes;
    private array $actors = [];
    private array $directors = [];
    private array $writers = [];
    private array $creators = [];
    protected Media $poster;
    private string $tmdbPosterPath;
    protected ?string $deletedAt = null;
    private string $createdAt;
    private ?string $updatedAt;
    private ?array $actions = [];

    public function __construct()
    {
        parent::__construct();
        $this->poster = new Media();
        $this->actions = [
            ['name' => 'Modifier', 'action' => 'modify', 'url' => Helpers::callRoute('production_update', ['id' => $this->id])],
            ['name' => 'Supprimer', 'action' => 'delete', 'url' => Helpers::callRoute('production_delete', ['id' => $this->id]), 'role' => 'admin'],
        ];
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId($id): void
    {
        $this->id = $id;
    }

    public function getTmdbId(): ?int
    {
        return $this->tmdbId;
    }

    public function setTmdbId($tmdbId): void
    {
        $this->tmdbId = $tmdbId;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle($title): void
    {
        $this->title = $title;
    }

    public function getOriginalTitle(): ?string
    {
        return $this->originalTitle;
    }

    public function setOriginalTitle($originalTitle): void
    {
        $this->originalTitle = $originalTitle;
    }

    public function getReleaseDate()
    {
        return $this->releaseDate;
    }

    public function setReleaseDate($releaseDate): void
    {
        $this->releaseDate = $releaseDate;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function setType($type): void
    {
        $this->type = $type;
    }

    public function getOverview(): ?string
    {
        return $this->overview;
    }

    public function setOverview($overview): void
    {
        $this->overview = $overview;
    }

    public function getRuntime(): ?int
    {
        return $this->runtime;
    }

    public function setRuntime($runtime): void
    {
        $this->runtime = $runtime;
    }

    public function getNumber(): ?int
    {
        return $this->number;
    }

    public function setNumber($number): void
    {
        $this->number = $number;
    }

    public function getTotalSeasons(): ?int
    {
        return $this->totalSeasons;
    }

    public function setTotalSeasons(int $totalSeasons): void
    {
        $this->totalSeasons = $totalSeasons;
    }

    public function getTotalEpisodes(): ?int
    {
        return $this->totalEpisodes;
    }

    public function setTotalEpisodes(int $totalEpisodes): void
    {
        $this->totalEpisodes = $totalEpisodes;
    }

    public function getCreatedAt(): string
    {
        return $this->createdAt;
    }

    public function getDeletedAt(): ?string
    {
        return $this->deletedAt;
    }

    public function setDeletedAt($deletedAt): void
    {
        $this->deletedAt = $deletedAt;
    }

    public function getActions(): ?array {
        return $this->actions;
    }

    public function setActions(?array $actions): void {
        $this->actions = $actions;
    }

    public function cleanReleaseDate() {
        $this->setReleaseDate(date("d/m/Y", strtotime($this->getReleaseDate())));
    }

    public function getCleanReleaseDate() {
        if ($this->getReleaseDate() != '') {
            return date("d/m/Y", strtotime($this->getReleaseDate()));
        } else {
            return "-";
        }
    }

    public function translateType() {
        switch ($this->getType()) {
            case 'movie':
                $this->setType('Film');
                break;
            case 'season':
                $this->setType('Saison');
                break;
            case 'series':
                $this->setType('Série');
                break;
            case 'episode':
                $this->setType('Episode');
                break;
        }
    }

    public function cleanRuntime() {
        if ($this->getRuntime() >= 60) {
            $hours = floor($this->getRuntime() / 60);
            $minutes = floor($this->getRuntime() % 60);
            $this->setRuntime($hours."h");
            if($minutes) {
                if($minutes < 10) {
                    $minutes = "0".$minutes;
                }
                $this->setRuntime($this->getRuntime().$minutes);
            }
        } else {
            $this->setRuntime($this->getRuntime()." minutes");
        }
    }

    public function getCleanRuntime() {
        if($this->getRuntime() != '') {
            return $this->getRuntime()." minutes";
        } else {
            return "-";
        }
    }

    public function getCleanCreatedAt(): string
    {
        return date("d/m/Y",strtotime($this->getCreatedAt()));
    }

    public function getActors(): array
    {
        return $this->actors;
    }

    public function setActors($tmdbCast): void
    {
        $actors = [];
        for($i = 0; $i < 5 ; $i++) {
            if(isset($tmdbCast[$i])) {
                $person = new Person();
                $person->setRole('vip');
                $person->setCharacter($tmdbCast[$i]->character ?? '');
                $person->setTmdbId($tmdbCast[$i]->id ?? null);
                $name = $tmdbCast[$i]->name ?? '';
                $person->setFullName($name);
                if($tmdbCast[$i]->profile_path != '') {
                    $person->media->setTmdbPosterPath(TMDB_IMG_PATH.$tmdbCast[$i]->profile_path);
                }
                $actors[] = $person;
            }
        }
        $this->actors = $actors;
    }

    public function saveActors() {
        $actors = $this->getActors();
        foreach ($actors as $actor) {
            $mediaId = $actor->saveMedia();
            $actorID = $actor->saveVip($mediaId);
            $actor->saveProductionPerson($actorID, $this->getLastInsertId(), 'cast');
        }
    }

    public function getPoster(): Media
    {
        return $this->poster;
    }

    public function setPoster($tmdbId, $productionType): void
    {
        $media = new Media();
        $imgPath = PATH_TO_IMG_POSTERS ."/$productionType/poster_$tmdbId.png";
        $media->setPath($imgPath);
        $media->setTitle("poster_$tmdbId");
    }

    public function savePoster() {
        $mediaId = $this->saveMedia();
        $productionID = $this->getLastInsertId();
        $this->saveProductionMedia($mediaId, $productionID, true);
    }

    public function saveMedia() {
        // Save poster file
        $productionImgPath = PATH_TO_IMG_POSTERS.$this->getTmdbId().'_'.Helpers::slugify($this->getTitle());
        if(!empty($this->poster->getTmdbPosterPath()) && $this->poster->getTmdbPosterPath() != TMDB_IMG_PATH) {
            file_put_contents(getcwd().$productionImgPath, file_get_contents($this->poster->getTmdbPosterPath()));
        }

        // Save or update poster in database
        $existingMedia = new Media();
        $existingMedia = $existingMedia->findOneBy('path', $productionImgPath);
        if($existingMedia) {
            $existingMedia->setTitle($this->getTitle());
            $existingMedia->save();
            $mediaId = $existingMedia->getId();
        } else {
            $media = new Media();
            $media->setTitle($this->getTitle());
            $media->setPath($productionImgPath);
            $media->save();
            $mediaId = $media->getLastInsertId();
        }
        return $mediaId;
    }

    public function saveProductionMedia($mediaId, $productionId, $isKeyArt) {
        // Save or update production person in database
        $existingProductionMedia = new ProductionMedia();
        $existingProductionMedia = $existingProductionMedia->select()
                                    ->where('mediaId', $mediaId)
                                    ->andWhere('productionId', $productionId)
                                    ->get();
        if($existingProductionMedia) {
            $existingProductionMedia->setKeyArt($isKeyArt);
            $existingProductionMedia->save();
        } else {
            $productionMedia = new ProductionMedia();
            $productionMedia->setProductionId($productionId);
            $productionMedia->setMediaId($mediaId);
            $productionMedia->setKeyArt($isKeyArt);
            $productionMedia->save();
        }
    }

    public function getTmdbPosterPath(): string
    {
        return $this->tmdbPosterPath;
    }

    public function setTmdbPosterPath(string $tmdbPosterPath): void
    {
        $this->tmdbPosterPath = $tmdbPosterPath;
    }

    public function getDirectors(): array
    {
        return $this->directors;
    }

    public function setDirectors(array $crewTeam): void
    {
        $directors = [];
        foreach ($crewTeam as $crew) {
            if ($crew->name != "" && $crew->job == 'Director') {
                $person = new Person();
                $person->setRole('vip');
                $person->setFullName($crew->name);
                $person->setTmdbId($crew->id);
                $directors[] = $person;
            }
        }
        $this->directors = $directors;
    }

    public function getWriters(): array
    {
        return $this->writers;
    }

    public function setWriters(array $crewTeam): void
    {
        $writers = [];
        foreach ($crewTeam as $crew) {
            if ($crew->name != "" && $crew->job == 'Screenplay') {
                $person = new Person();
                $person->setRole('vip');
                $person->setFullName($crew->name);
                $person->setTmdbId($crew->id);
                $writers[] = $person;
            }
        }
        $this->writers = $writers;
    }

    public function getCreators(): array
    {
        return $this->creators;
    }

    public function setCreators(array $creators): void
    {
        $creators = [];
        foreach ($creators as $creator) {
            $person = new Person();
            $person->setRole('vip');
            $person->setFullName($creator->name);
            $person->setTmdbId($creator->id);
            $creators[] = $person;
        }
        $this->creators = $creators;
    }

    public function saveCrew($department) {
        $functionName = "get".ucfirst($department);
        $crew = $this->$functionName();
        foreach ($crew as $crewMember) {
            $mediaId = $crewMember->saveMedia();
            $writerID = $crewMember->saveVip($mediaId);
            $crewMember->saveProductionPerson($writerID, $this->getLastInsertId(), mb_substr($department, 0, -1));
        }
    }

    public function formBuilderAddProduction(){
        return [
            "config" => [
                "method" => "POST",
                "action" => "",
                "class" => "form_control",
                "id" => "formAddProduction",
                "submit" => "Valider",
                "referer" => '/admin/productions/creation'
            ],
            "fields" => [
                "type" => [
                    "type" => "select",
                    "label" => "Type",
                    "required" => true,
                    "class" => "form_input",
                    "error" => "Un type de production est nécessaire",
                    "options" => [
                        [
                            "value"=>"movie",
                            "text"=>"Film",
                        ],
                        [
                            "value"=>"series",
                            "text"=>"Série",
                        ]
                    ],
                ],
                "title" => [
                    "type" => "text",
                    "placeholder" => "",
                    "label" => "Titre",
                    "required" => true,
                    "class" => "form_input",
                    "minLength" => 1,
                    "maxLength" => 100,
                    "error" => "Le titre doit faire entre 1 et 100 caractères"
                ],
                "originalTitle" => [
                    "type" => "text",
                    "placeholder" => "",
                    "label" => "Titre original",
                    "class" => "form_input",
                    "maxLength" => 100,
                    "error" => "Le titre doit faire entre 1 et 100 caractères"
                ],
                "releaseDate" => [
                    "type" => "date",
                    "label" => "Date de sortie",
                    "class" => "form_input",
                    "minLength" => 10,
                    "maxLength" => 10,
                    "error" => "Le format de la date est incorrect"
                ],
                "overview" => [
                    "type" => "text",
                    "placeholder" => "",
                    "label" => "Résumé",
                    "class" => "form_input",
                    "maxLength" => 1000,
                    "error" => "Le résumé ne peut pas dépasser 1000 caractères"
                ],
                "runtime" => [
                    "type" => "number",
                    "placeholder" => "",
                    "label" => "Durée (du film ou d'un épisode)",
                    "class" => "form_input",
                    "error" => "Le résumé ne peut pas dépasser 1000 caractères"
                ],
                "number" => [
                    "type" => "number",
                    "placeholder" => "",
                    "label" => "Numéro",
                    "class" => "form_input",
                ],
                "csrfToken" => [
                    "type"=>"hidden",
                    "value"=> FormBuilder::generateCSRFToken(),
                ]
            ]

        ];
    }

    public function formBuilderAddProductionTmdb(){
        return [
            "config" => [
                "method" => "POST",
                "action" => "",
                "class" => "form_control card",
                "id" => "formAddProductionTmdb",
                "submit" => "Valider",
                "referer" => Helpers::callRoute('productions_creation_tmdb')
            ],
            "fields" => [
                "productionType" => [
                    "type" => "radio",
                    "options" => [
                        [
                            "value"=>"movie",
                            "text"=>"Film",
                            "checked"=>true
                        ],
                        [
                            "value"=>"series",
                            "text"=>"Série"
                        ]
                    ],
                    "label" => "Type",
                    "class" => "form_select",
                    "error" => "Le type doit être film, série, saison ou épisode"
                ],
                "productionID" => [
                    "type" => "number",
                    "min" => 1,
                    "label" => "ID du film ou de la série",
                    "class" => "search-bar",
                    "error" => "Un ID TMDB est nécessaire"
                ],
                "seasonNb" => [
                    "type" => "number",
                    "min" => 0,
                    "label" => "Numéro de la saison",
                    "class" => "search-bar",
                    "error" => "Le numéro de saison doit être supérieur ou égal à 0",
                    "disabled" => true
                ],
                "episodeNb" => [
                    "type" => "number",
                    "min" => 0,
                    "label" => "Numéro de l'épisode",
                    "class" => "search-bar",
                    "error" => "Le numéro d'épisode doit être supérieur ou égal à 0",
                    "disabled" => true
                ],
                "productionPreviewRequest" => [
                    "type" => "button",
                    "class" => "btn preview",
                    "value" => "Preview"
                ],
                "csrfToken" => [
                    "type"=>"hidden",
                    "value"=> FormBuilder::generateCSRFToken(),
                ]
            ],
        ];
    }

    public function populateFromTmdb($post, $jsonResponseArray) {

        // index 0: movie or series
        $item = json_decode($jsonResponseArray[0]);
        // index 1: episode
        if(isset($jsonResponseArray[1]))
            $episode = json_decode($jsonResponseArray[1]);

        $this->setTmdbId($item->id);
        $this->setType(htmlspecialchars($post['productionType']));
        $this->setTitle($item->title ?? $item->name);
        $this->setOriginalTitle($item->original_title ?? $item->original_name);
        $this->setOverview($item->overview);
        $this->setReleaseDate($item->release_date ?? $item->first_air_date);
        $this->setRuntime($item->runtime ?? $item->episode_run_time[0] ?? '0');
        $this->setActors($item->credits->cast);
        if($item->poster_path != '')
            $this->poster->setTmdbPosterPath(TMDB_IMG_PATH.$item->poster_path);

        //$production0['genres'] = $item->genres; TODO

        switch ($post['productionType']) {
            case 'movie':
                $this->setDirectors($item->credits->crew);
                $this->setWriters($item->credits->crew);
                $this->setPoster($item->id, 'movie');
                break;
            case 'series':
                $this->setCreators($item->created_by);
                $this->setTotalSeasons(sizeof($item->seasons));
                $nbEpisodes = 0;
                foreach ($item->seasons as $season) { $nbEpisodes += $season->episode_count; }
                $this->setTotalEpisodes($nbEpisodes);
                $this->setPoster($item->id, 'series');

                // Season
                if(!empty($_POST['seasonNb'])) {
                    if(isset($item->seasons[$post['seasonNb']])) {
                        $this->setTotalEpisodes($item->seasons[$post['seasonNb']]->episode_count);
                        $this->setOverview($item->seasons[$post['seasonNb']]->overview);
                        $this->setPoster($item->id, 'season');
                        if($item->seasons[$post['seasonNb']]->poster_path != '')
                            $this->poster->setTmdbPosterPath(TMDB_IMG_PATH.$item->seasons[$post['seasonNb']]->poster_path);

                        // Episode
                        if(!empty($episode)) {
                            $this->setTitle($episode->name);
                            $this->setOverview($episode->overview);
                            $this->setReleaseDate($episode->air_date);
                            $this->setPoster($item->id, 'episode');
                            if($item->seasons[$post['seasonNb']]->poster_path != '')
                                $this->poster->setTmdbPosterPath(TMDB_IMG_PATH . $item->seasons[$post['seasonNb']]->poster_path);
                        }
                    } else {
                        echo '<div class="error">La série "'.$this->getTitle().'" ne contient pas de saison n°'.$_POST['seasonNb']."</div>";
                        return false;
                    }
                }
                break;
        }
        return true;
    }

    public function displayPreview() {
        $view = new View("productions/tmdbPreview", null);
        $view->assign('production', $this);
    }

}