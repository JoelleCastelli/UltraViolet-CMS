<ul class="productionInfos">
    <li>Titre : <?= $production->getTitle() ?></li>
    <li>Titre original : <?= $production->getOriginalTitle() ?></li>
    <li>Résumé : <?= $production->getOverview() ?></li>
    <li>Date de sortie : <?= $production->getCleanReleaseDate() ?></li>
    <li>Durée : <?= $production->getCleanRuntime() ?></li>
    <li><img src='<?= $production->getTmdbPosterPath() ?>'/></li>
    <!--//$this->setCast($item->credits->cast);-->
</ul>