<?php
use App\Core\Helpers;
if(isset($errors)) {
    foreach ($errors as $error) {
        echo "<li>$error</li>";
    }
}
?>

<div id="dashboard">
    <div class="card">
        <div class="cardTitle">Derniers articles</div>
        <div class="cardContent">
            <?php
                if($articles) {
                    echo "Il y a des articles";
                } else {
                    echo "<a href='".Helpers::callRoute('article_creation')."'>
                        <button class='btn'>Ecrire un article</button>
                      </a>";
                }
            ?>

        </div>
    </div>
    <div class="card">
        <div class="cardTitle">Derniers commentaires</div>
        <div class="cardContent">
            <?php
                if($comments) {
                    echo "Il y a des commentaires";
                } else {
                    echo "Aucun commentaire reçu";
                }
            ?>
        </div>
    </div>
    <div class="card">
        <div class="cardTitle">Dernières productions</div>
        <div id="productions" class="cardContent">
            <?php
            if($productions) {
                echo "<div id='productionsList'>";
                    foreach ($productions as $production) {
                        echo "<div class='productionCard'>";
                            echo "<div class='productionImg' style=\"background-image: url('".$production->getPoster()->getPath()."')\"></div>";
                            echo "<div class='productionName'>";
                                if($production->getParentProduction()) {
                                    if($production->getParentProduction()->getParentProduction()) {
                                        echo $production->getParentProduction()->getParentProduction()->getTitle().' - ';
                                    }
                                    echo $production->getParentProduction()->getTitle().' - ';
                                }

                                echo $production->getTitle();
                            echo "</div>";
                        echo "</div>";
                    }
                echo "</div>";
            }
            echo "<a href='".Helpers::callRoute('productions_creation_tmdb')."'><button class='btn'>Ajouter une production</button></a>";
            ?>
        </div>
    </div>
    <div class="card">
        <div class="cardTitle">Statistiques</div>
        <div class="cardContent"></div>
    </div>
</div>