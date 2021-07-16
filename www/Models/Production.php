<?php

namespace App\Models;

use App\Core\Database;
use App\Core\FormBuilder;
use App\Core\Helpers;
use App\Core\View;

class Production extends Database
{
    private ?int $id = null;
    protected ?int $tmdbId = null;
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
    private ?Production $parentProduction = null;
    protected ?int $parentProductionId = null;
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

    public function setTotalSeasons(?int $totalSeasons): void
    {
        $this->totalSeasons = $totalSeasons;
    }

    public function getTotalEpisodes(): ?int
    {
        return $this->totalEpisodes;
    }

    public function setTotalEpisodes(?int $totalEpisodes): void
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

    public function getTranslatedType(): string
    {
        switch ($this->getType()) {
            case 'movie':
                return 'Film';
            case 'season':
                return 'Saison';
            case 'series':
                return 'Série';
            case 'episode':
                return 'Episode';
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

    public function getCleanRuntime(): string
    {
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

    /**
     * @return array
     */
    public function getActors(): array
    {
        return $this->actors;
    }

    public function getRelatedActors(): array
    {
        $productionPersons = new ProductionPerson();
        $productionPersons = $productionPersons->select()->where('productionId', $this->getId())
                                                         ->andWhere('department', 'actor')->get();
        $actors = [];
        foreach ($productionPersons as $productionPerson) {
            $actor = new Person();
            $actor->setId($productionPerson->getPersonId());
            $actor->getMedia();
            $actors[$actor->getId()]['fullName'] = $actor->getFullName();
            $actors[$actor->getId()]['photo'] = $actor->getMedia()->getPath();
            $actors[$actor->getId()]['role'] = $productionPerson->getCharacter();
        }

        return $actors;
    }

    public function setActors(array $actors): void
    {
        $this->actors = $actors;
    }

    public function getActorsFromTmdb($tmdbCast): array
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
        return $actors;
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

    public function setPoster(?string $tmdbPoster): void
    {
        $media = new Media();
        if($tmdbPoster != '')
            $media->setTmdbPosterPath(TMDB_IMG_PATH.$tmdbPoster);
        $imgPath = PATH_TO_IMG_POSTERS.$this->getType().'/'.$this->getId().".png";
        $media->setPath($imgPath);
        $media->setTitle("poster_".$this->getTmdbId());
        $this->poster = $media;
    }

    public function savePoster() {
        $mediaId = $this->saveMedia();
        $productionID = $this->getLastInsertId();
        $this->saveProductionMedia($mediaId, $productionID, true);
    }

    public function saveMedia() {
        // Save poster file
        $productionImgPath = PATH_TO_IMG_POSTERS.$this->getType().'/'.$this->getLastInsertId().".png";
        if(!empty($this->poster->getTmdbPosterPath()) && $this->poster->getTmdbPosterPath() != TMDB_IMG_PATH)
            file_put_contents(getcwd().$productionImgPath, file_get_contents($this->poster->getTmdbPosterPath()));

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

    public function getParentProduction(): ?Production
    {
        return $this->parentProduction;
    }

    public function getGrandParentProduction(): ?Production
    {
        return $this->parentProduction->parentProduction;
    }

    public function setParentProduction(?Production $parentProduction): void
    {
        $this->parentProduction = $parentProduction;
    }

    public function getParentProductionId(): ?int
    {
        return $this->parentProductionId;
    }

    public function setParentProductionId(?int $parentProductionId): void
    {
        $this->parentProductionId = $parentProductionId;
    }

    /**
     * @return array
     */
    public function getDirectors(): array
    {
        return $this->directors;
    }

    public function getRelatedDirectors(): array
    {
        $productionPersons = new ProductionPerson();
        $productionPersons = $productionPersons->select()->where('productionId', $this->getId())
            ->andWhere('department', 'director')->get();
        $directors = [];
        foreach ($productionPersons as $productionPerson) {
            $director = new Person();
            $director->setId($productionPerson->getPersonId());
            $director->getMedia();
            $directors[$director->getId()]['fullName'] = $director->getFullName();
            $directors[$director->getId()]['photo'] = $director->getMedia()->getPath();
        }
        return $directors;
    }

    public function setDirectors(array $directors): void
    {
        $this->directors = $directors;
    }

    public function getDirectorsFromTmdb($crewTeam): array
    {
        $directors = [];
        foreach ($crewTeam as $crew) {
            if ($crew->name != "" && $crew->job == 'Director') {
                $person = new Person();
                $person->setRole('vip');
                $person->setFullName($crew->name);
                $person->setTmdbId($crew->id);
                if($crew->profile_path != '')
                    $person->media->setTmdbPosterPath(TMDB_IMG_PATH.$crew->profile_path);
                $directors[] = $person;
            }
        }
        return $directors;
    }

    /**
     * @return array
     */
    public function getWriters(): array
    {
        return $this->writers;
    }

    public function getRelatedWriters(): array
    {
        $productionPersons = new ProductionPerson();
        $productionPersons = $productionPersons->select()->where('productionId', $this->getId())
            ->andWhere('department', 'writer')->get();
        $writers = [];
        foreach ($productionPersons as $productionPerson) {
            $writer = new Person();
            $writer->setId($productionPerson->getPersonId());
            $writer->getMedia();
            $writers[$writer->getId()]['fullName'] = $writer->getFullName();
            $writers[$writer->getId()]['photo'] = $writer->getMedia()->getPath();
        }
        return $writers;
    }

    public function setWriters(array $writers): void
    {
        $this->writers = $writers;
    }

    public function getWritersFromTmdb($crewTeam): array
    {
        $writers = [];
        foreach ($crewTeam as $crew) {
            if ($crew->name != "" && $crew->job == 'Screenplay') {
                $person = new Person();
                $person->setRole('vip');
                $person->setFullName($crew->name);
                $person->setTmdbId($crew->id);
                if($crew->profile_path != '')
                    $person->media->setTmdbPosterPath(TMDB_IMG_PATH.$crew->profile_path);
                $writers[] = $person;
            }
        }
        return $writers;
    }

    /**
     * @return array
     */
    public function getCreators(): array
    {
        return $this->creators;
    }

    public function getRelatedCreators(): array
    {
        $productionPersons = new ProductionPerson();
        $productionPersons = $productionPersons->select()->where('productionId', $this->getId())
            ->andWhere('department', 'creator')->get();
        $creators = [];
        foreach ($productionPersons as $productionPerson) {
            $creator = new Person();
            $creator->setId($productionPerson->getPersonId());
            $creator->getMedia();
            $creators[$creator->getId()]['fullName'] = $creator->getFullName();
            $creators[$creator->getId()]['photo'] = $creator->getMedia()->getPath();
        }
        return $creators;
    }

    public function setCreators(array $creators): void
    {

        $this->creators = $creators;
    }

    public function getCreatorsFromTmdb($tmdbCreators): array
    {
        $creators = [];
        foreach ($tmdbCreators as $creator) {
            $person = new Person();
            $person->setRole('vip');
            $person->setFullName($creator->name);
            $person->setTmdbId($creator->id);
            if($creator->profile_path != '')
                $person->media->setTmdbPosterPath(TMDB_IMG_PATH.$creator->profile_path);
            $creators[] = $person;
        }
        return $creators;
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

    public function getParentSeriesName(): string
    {
        $series = new Production();
        if($this->getType() == 'episode') {
            $season = new Production();
            $season = $season->select()->where('type', 'season')->andWhere('id', $this->getParentProductionId())->first();
            $series = $series->select()->where('type', 'series')->andWhere('id', $season->getParentProductionId())->first();
        } elseif ($this->getType() == 'season') {
            $series = $series->select()->where('type', 'series')->andWhere('id', $this->getParentProductionId())->first();
        } else {
            return '';
        }
        return $series->getTitle();
    }

    public function getParentSeasonName(): string
    {
        if($this->getType() == 'episode') {
            $season = new Production();
            $season = $season->select()->where('type', 'season')->andWhere('id', $this->getParentProductionId())->first();
            return $season->getTitle();
        }
        return '';
    }

    public function formBuilderAddProduction(): array
    {
        $series = new Production();
        $series = $series->selectWhere('type', 'series');
        if(empty($series)) $series['empty'] = "Aucune série disponible";
        $seriesOptions = [];
        $i = 0;
        if(!isset($series['empty'])) {
            foreach ($series as $serie) {
                $seriesOptions[$i]["value"] = $serie->getId();
                $seriesOptions[$i++]["text"] = $serie->getTitle();
            }
        }

        return [
            "config" => [
                "method" => "POST",
                "action" => "",
                "class" => "form_control card",
                "id" => "formAddProduction",
                "submit" => "Valider",
                "enctype" => "multipart/form-data",
                "referer" => Helpers::callRoute('productions_creation')
            ],
            "fields" => [
                "type" => [
                    "type" => "radio",
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
                        ],
                        [
                            "value"=>"season",
                            "text"=>"Saison",
                        ],
                        [
                            "value"=>"episode",
                            "text"=>"Episode",
                        ]
                    ],
                ],
                "title" => [
                    "type" => "text",
                    "placeholder" => "",
                    "label" => "Titre",
                    "required" => true,
                    "class" => "search-bar",
                    "minLength" => 1,
                    "maxLength" => 100,
                    "error" => "Le titre doit faire entre 1 et 100 caractères"
                ],
                "originalTitle" => [
                    "type" => "text",
                    "placeholder" => "",
                    "label" => "Titre original",
                    "class" => "search-bar",
                    "maxLength" => 100,
                    "error" => "Le titre doit faire entre 1 et 100 caractères"
                ],
                "releaseDate" => [
                    "type" => "date",
                    "label" => "Date de sortie",
                    "class" => "search-bar",
                    "minLength" => 10,
                    "maxLength" => 10,
                    "error" => "Le format de la date est incorrect"
                ],
                "runtime" => [
                    "type" => "number",
                    "placeholder" => "",
                    "label" => "Durée (du film ou d'un épisode)",
                    "class" => "search-bar",
                    "error" => "Le résumé ne peut pas dépasser 1000 caractères"
                ],
                "series" => [
                    "type" => "select",
                    "placeholder" => "",
                    "label" => "Nom de la série",
                    "class" => "search-bar",
                    "options" => $seriesOptions
                ],
                "season" => [
                    "type" => "select",
                    "placeholder" => "",
                    "label" => "Numéro de la saison",
                    "class" => "search-bar",
                    "options" => [
                        [
                            "value"=>"movie",
                            "text"=>"Film",
                        ],
                        [
                            "value"=>"series",
                            "text"=>"Série",
                        ],
                        [
                            "value"=>"season",
                            "text"=>"Saison",
                        ],
                        [
                            "value"=>"episode",
                            "text"=>"Episode",
                        ]
                    ],
                ],
                "number" => [
                    "type" => "number",
                    "placeholder" => "",
                    "label" => "Numéro",
                    "class" => "search-bar",
                ],
                "poster" => [
                    "type" => "file",
                    "accept" => ".jpg, .jpeg, .png",
                    "label" => "Poster (uniquement des fichiers JPG, JPEG ou PNG)",
                    "class" => "search-bar",
                ],
                "overview" => [
                    "type" => "textarea",
                    "placeholder" => "",
                    "label" => "Résumé",
                    "class" => "search-bar",
                    "maxLength" => 1000,
                    "error" => "Le résumé ne peut pas dépasser 1000 caractères"
                ],
                "csrfToken" => [
                    "type"=>"hidden",
                    "value"=> FormBuilder::generateCSRFToken(),
                ]
            ]

        ];
    }

    public function formBuilderAddProductionTmdb(): array
    {
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

    public function formBuilderUpdateProduction($id): array
    {
        $production = new Production();
        $production = $production->findOneBy('id', $id);
        if($production) {
            return [
                "config" => [
                    "method" => "POST",
                    "action" => "",
                    "class" => "form_control card",
                    "id" => "formUpdateProductionTmdb",
                    "submit" => "Valider",
                    "referer" => Helpers::callRoute('production_update', ['id' => $id])
                ],
                "fields" => [
                    "id" => [
                        "type" => "hidden",
                        "value" => $production->getId()
                    ],
                    "type" => [
                        "type" => "hidden",
                        "value" => $production->getType()
                    ],
                    "tmdbID" => [
                        "type" => "number",
                        "class" => "search-bar",
                        "value" => $production->getTmdbId(),
                        "label" => "ID TMDB :",
                        "readonly" => true
                    ],
                    "title" => [
                        "type" => "text",
                        "class" => "search-bar",
                        "value" => $production->getTitle(),
                        "label" => "Titre :"
                    ],
                    "originalTitle" => [
                        "type" => "text",
                        "class" => "search-bar",
                        "value" => $production->getOriginalTitle(),
                        "label" => "Titre original :"
                    ],
                    "runtime" => [
                        "type" => "number",
                        "class" => "search-bar",
                        "label" => "Durée (en minutes) :",
                        "value" => $production->getRuntime(),
                    ],
                    "releaseDate" => [
                        "type" => "date",
                        "class" => "search-bar",
                        "label" => "Date de sortie :",
                        "value" => $production->getReleaseDate(),
                    ],
                    "overview" => [
                        "type" => "textarea",
                        "class" => "search-bar",
                        "value" => $production->getOverview(),
                        "label" => "Résumé :",
                        "maxLength" => 1000,
                        "error" => "Le résumé ne peut pas dépasser 1000 caractères"
                    ],
                    "csrfToken" => [
                        "type" => "hidden",
                        "value" => FormBuilder::generateCSRFToken(),
                    ]
                ],
            ];
        }
        return [];
    }

    public function populateFromTmdb($post, $jsonResponseArray): bool
    {
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
        $this->setActors($this->getActorsFromTmdb($item->credits->cast));
        $this->setPoster($item->poster_path);

        switch ($post['productionType']) {
            case 'movie':
                $this->setDirectors($this->getDirectorsFromTmdb($item->credits->crew));
                $this->setWriters($this->getWritersFromTmdb($item->credits->crew));
                break;
            case 'series':
                $this->setCreators($this->getCreatorsFromTmdb($item->created_by));
                $this->setTotalSeasons(sizeof($item->seasons));
                $nbEpisodes = 0;
                foreach ($item->seasons as $season) { $nbEpisodes += $season->episode_count; }
                $this->setTotalEpisodes($nbEpisodes);

                // Season
                if(!empty($_POST['seasonNb'])) {
                    if(isset($item->seasons[$post['seasonNb']])) {
                        $this->setParentProduction(clone $this);
                        $this->setType('season');
                        $this->setTmdbId($item->seasons[$post['seasonNb']]->id);
                        $this->setTitle($item->seasons[$post['seasonNb']]->name);
                        $this->setOriginalTitle(null);
                        $this->setTotalSeasons(null);
                        $this->setTotalEpisodes($item->seasons[$post['seasonNb']]->episode_count);
                        $this->setNumber($post['seasonNb']);
                        $this->setOverview($item->seasons[$post['seasonNb']]->overview);
                        $this->setPoster($item->seasons[$post['seasonNb']]->poster_path);

                        // Episode
                        if(!empty($episode)) {
                            $this->setParentProduction(clone $this);
                            $this->setType('episode');
                            $this->setTmdbId($episode->id);
                            $this->setTitle($episode->name);
                            $this->setOriginalTitle(null);
                            $this->setOverview($episode->overview);
                            $this->setNumber($_POST['episodeNb']);
                            $this->setTotalEpisodes(null);
                            $this->setTotalSeasons(null);
                            $this->setReleaseDate($episode->air_date);
                            $this->setPoster($episode->still_path);
                        }
                    } else {
                        echo '<p class="error-message-form">La série "'.$this->getTitle().'" ne contient pas de saison n°'.$_POST['seasonNb']."</p>";
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

    public function save() {
        if($this->getType() == "episode") {
            $seriesId = $this->saveParentSeries();
            $seasonId = $this->saveParentSeason($seriesId);
            $this->saveEpisode($seasonId);
        } elseif ($this->getType() == "season") {
            $seriesId = $this->saveParentSeries();
            $this->setParentProductionId($seriesId);
            $this->dbSave();
        } else {
            // Movie or Series
            $this->dbSave();
            if($this->getPoster()->getPath() != null) {
                $this->savePoster();
            }
            $this->saveCrew('actors');
            if($this->getType() == 'movie') {
                $this->saveCrew('writers');
                $this->saveCrew('directors');
            } else {
                $this->saveCrew('creators');
            }
        }
    }

    public function saveParentSeries(){
        $existingSeries = new Production();
        $getProductionAbove = 'getParentProduction';
        if($this->getType() == 'episode') $getProductionAbove = 'getGrandParentProduction';

        $existingSeries = $existingSeries->select()->where('type', 'series')->andWhere('tmdbId', $this->$getProductionAbove()->getTmdbId())->first();
        if($existingSeries) {
            $existingSeries->dbSave();
            $seriesId = $existingSeries->getId();
        } else {
            $this->$getProductionAbove()->dbSave();
            $this->$getProductionAbove()->savePoster();
            $this->$getProductionAbove()->saveCrew('creators');
            $this->$getProductionAbove()->saveCrew('actors');
            $seriesId = $this->getLastInsertId();
        }
        return $seriesId;
    }

    public function saveParentSeason($seriesId){
        $existingSeason = new Production();
        $existingSeason = $existingSeason->select()->where('type', 'season')->andWhere('tmdbId', $this->getParentProduction()->getTmdbId())->first();
        if($existingSeason) {
            $existingSeason->dbSave();
            $seasonId = $existingSeason->getId();
        } else {
            $this->getParentProduction()->setParentProductionId($seriesId);
            $this->getParentProduction()->dbSave();
            $this->getParentProduction()->savePoster();
            $this->getParentProduction()->saveCrew('actors');
            $seasonId = $this->getLastInsertId();
        }
        return $seasonId;
    }

    public function saveEpisode($seasonId){
        // Save episode
        $existingEpisode = new Production();
        $existingEpisode = $existingEpisode->select()->where('type', 'episode')->andWhere('tmdbId', $this->getTmdbId())->first();
        if($existingEpisode) {
            $existingEpisode->dbSave();
        } else {
            $this->setParentProductionId($seasonId);
            $this->dbSave();
            $this->savePoster();
        }
    }

    public function dbSave() {
        parent::save();
    }


    public function getProductionPosterPath(): ?string
    {
        // Find production key art media
        $productionMedia = new ProductionMedia();
        $productionMedia = $productionMedia->select()->where('productionId', $this->getId())->andWhere('keyArt', 1)->first();
        if($productionMedia) {
            $this->getPoster()->setId($productionMedia->getMediaId());
            if(file_exists(getcwd().$this->getPoster()->getPath()))
                return $this->getPoster()->getPath();
        }
        // Display default image if no key art is associated or if file is not found
        return PATH_TO_IMG.'default_poster.jpg';
    }

}