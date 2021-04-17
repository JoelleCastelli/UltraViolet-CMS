<?php

if (empty($productions)) {
    echo "Déso y'a rien dans la DB";
} else {
    foreach ($productions as $production) { ?>
        <div>
            <div>Type : <?= $production->getType() ?></div>
            <div>Titre : <?= $production->getTitle()?></div>
            <div>Titre original : <?= $production->getOriginalTitle() ?? $production->getTitle() ?></div>
            <div>Date de sortie : <?= $production->getReleaseDate() ?? "inconnue" ?></div>
            <div>Durée : <?= $production->getRuntime() ?? "y'a rien" ?></div>
            <div>Résumé : <?= $production->getOverview() ?? "y'a rien" ?></div>

            <?php
                if($production->getType() != ("movie" || "series")) {
                    echo "<div>Numéro : ".($production->getNumber() ?? "y'a rien" )."</div>";
                }

                if($production->getType() == "season") {
                    // n° de la saison
                    // nombre d'épisodes ?
                }

                if($production->getType() == "episode") {
                    // numéro de l'épisode
                    // numéro de la saison
                }
            ?>
        </div>
    <?php }
}
