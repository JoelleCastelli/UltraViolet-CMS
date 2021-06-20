<ul class="productionInfos">
    <li>Titre : <?= $production->getTitle() ?></li>
    <li>Titre original : <?= $production->getOriginalTitle() ?? '' ?></li>
    <li>Résumé : <?= $production->getOverview() ?? '' ?></li>
    <li>Date de sortie : <?= $production->getCleanReleaseDate() ?? '' ?></li>
    <li>Durée : <?= $production->getCleanRuntime() ?? '' ?></li>
    <li><img src='<?= $production->getPoster()->getTmdbPosterPath() ?>'/></li>
    <li>
        Acteurs principaux :
        <?php
            $actors = $production->getActors();
            for ($n = count($actors), $i = 0; $i < $n; $i++) {
                echo $actors[$i]->getFullName(). ($i < $n-1 ? ', ' : '');
            }
        ?>
    </li>
    <li>
        Réalisation :
        <?php
        $directors = $production->getDirectors();
        for ($n = count($directors), $i = 0; $i < $n; $i++) {
            echo $directors[$i]->getFullName(). ($i < $n-1 ? ', ' : '');
        }
        ?>
    </li>
    <li>
        Scénario :
        <?php
        $writers = $production->getWriters();
        for ($n = count($writers), $i = 0; $i < $n; $i++) {
            echo $writers[$i]->getFullName(). ($i < $n-1 ? ', ' : '');
        }
        ?>
    </li>
</ul>