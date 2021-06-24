<div id="productionInfos">
    <?php
        if($production->getPoster()->getTmdbPosterPath() == '')
            echo "<img id='poster' src='".PATH_TO_IMG."default_poster.jpg'/>";
        else
            echo "<img id='poster' src='".$production->getPoster()->getTmdbPosterPath()."'/>";
    ?>
    
    <div><b>Titre :</b> <?= $production->getTitle() ?></div>
    <div><b>Titre original :</b> <?= $production->getOriginalTitle() != '' ? $production->getOriginalTitle() : '?' ?></div>
    <div><b>Résumé :</b> <?= $production->getOverview() != '' ? $production->getOverview() : '?' ?></div>
    <div><b>Date de sortie :</b> <?= $production->getCleanReleaseDate() ?? '?' ?></div>
    <?php $label = $production->getType() == "movie" ? "Durée" : "Durée d'un épisode"; ?>
    <div><b><?= $label ?> :</b> <?= $production->getCleanRuntime() != '' ? $production->getCleanRuntime() : '?' ?></div>

    <?php
        $directors = $production->getDirectors();
        if(!empty($directors)) {
            echo "<div>";
                echo "<b>Réalisation : </b>";
                for ($n = count($directors), $i = 0; $i < $n; $i++) {
                    echo $directors[$i]->getFullName(). ($i < $n-1 ? ', ' : '');
                }
            echo "</div>";
        }
    ?>

    <?php
        $writers = $production->getWriters();
        if(!empty($writers)) {
            echo "<div>";
                echo "<b>Scénario : </b>";
                for ($n = count($writers), $i = 0; $i < $n; $i++) {
                    echo $writers[$i]->getFullName(). ($i < $n-1 ? ', ' : '');
                }
            echo "</div>";
        }
    ?>

    <?php
        $creators = $production->getCreators();
        if(!empty($creators)) {
            echo "<div>";
            echo "<b>Réalisation : </b>";
            for ($n = count($creators), $i = 0; $i < $n; $i++) {
                echo $creators[$i]->getFullName(). ($i < $n-1 ? ', ' : '');
            }
            echo "</div>";
        }
    ?>

    <?php
        $actors = $production->getActors();
        if(!empty($actors)) {
            echo '<b>Acteurs principaux :</b>';
            echo '<div id="actors">';
                for ($n = count($actors), $i = 0; $i < $n; $i++) {
                    echo "<div class='actorCard'>";

                        if($actors[$i]->getMedia()->getTmdbPosterPath() == '')
                            echo "<img class='actorImg' src='".PATH_TO_IMG."default_poster.jpg'/>";
                        else
                            echo "<img class='actorImg' src='".$actors[$i]->getMedia()->getTmdbPosterPath()."'/>";

                        echo "<div class='actorName'>";
                            echo $actors[$i]->getFullName();
                        echo "</div>";
                    echo "</div>";
                }
            echo "</div>";
        }
    ?>

</div>