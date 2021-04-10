<?php

if (empty($pages)) {
    echo "Déso y'a rien dans la DB";
} else {
    foreach ($pages as $page) {
        echo "<div>";
            echo "<div>Titre : ".$page->getTitle()."</div>";
            echo "<div>Type : ".$page->getSlug()."</div>";
            echo "<div>Titre original : ".$page->getPosition() ?? "inconnue"."</div>";
            echo "<div>Durée : ".$page->getPublictionDate() ?? "y'a rien"."</div>";
            echo "<div>Résumé : ".$page->getState() ?? "y'a rien"."</div>";
        echo "</div>";
        echo "&nbsp;";
    }
}
?>
<a href="ajout-d-une-page">add page</a>