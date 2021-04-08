<?php

if (empty($productions)) {
    echo "Déso y'a rien dans la DB";
} else {
    foreach ($productions as $production) {
        echo "<div>";
            echo "<div>Type : ".$production->getType()."</div>";
            echo "<div>Titre : ".$production->getTitle()."</div>";
            echo "<div>Titre original : ".$production->getOriginalTitle() ?? $production->getTitle()."</div>";
            echo "<div>Date de sortie : ".$production->getReleaseDate() ?? "inconnue"."</div>";
            echo "<div>Durée : ".$production->getRuntime() ?? "y'a rien"."</div>";
            echo "<div>Résumé : ".$production->getOverview() ?? "y'a rien"."</div>";

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
        echo "</div>";
    }
}
