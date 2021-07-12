<?php
use App\Core\Helpers;
if(isset($errors)) {
    foreach ($errors as $error) {
        echo "<li>$error</li>";
    }
}
?>

<div id="dashboard">

    <!--ARTICLES-->
    <div class="card">
        <div class="cardTitle">Derniers articles</div>
        <div id="articles">
            <?php if($articles) { ?>
                <div id='articlesList'>
                    <?php foreach ($articles as $article) { ?>
                        <div class='articleCard'>
                            <div class="articleInfos">
                                <div class="articleTitle"><?= $article['content']->getTitle() ?></div>
                                <div class="articleDetails">Publié le <?= $article['content']->getCleanPublicationDate() ?> par <?= $article['content']->getPerson()->getPseudo() ?></div>
                            </div>
                            <div class="articleActions">
                                <div class="comments">
                                    <i class="fas fa-comment-dots"></i>
                                    <span class="commentsNb"><?= $article['comments'] ?></span>
                                </div>
                                <div class="bubble-actions"></div>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            <?php } else { ?>
                <p>
                    <a href='<?= Helpers::callRoute('article_creation') ?>'><button class='btn'>Ecrire un article</button></a>
                </p>
            <?php } ?>
        </div>
        <div class="linkButton">
            <?php if($articles) { ?>
                <a href='<?= Helpers::callRoute('articles_list') ?>'><button class='btn'>Voir tous les articles</button></a>
            <?php } ?>
        </div>
    </div>

    <!--COMMENTS-->
    <div class="card">
        <div class="cardTitle">Derniers commentaires</div>
        <div id="comments">
            <?php if($comments) { ?>
                <div id='commentsList'>
                    <?php foreach ($comments as $comment) { ?>

                    <?php } ?>
                </div>
            <?php } else { ?>
                <p>Aucun commentaire</p>
            <?php } ?>
        </div>
        <div class="linkButton">
            <a href='<?= Helpers::callRoute('comments_list') ?>'><button class='btn'>Voir tous les commentaires</button></a>
        </div>
    </div>

    <!--PRODUCTIONS-->
    <div class="card">
        <div class="cardTitle">Dernières productions</div>
        <div id="productions">
            <?php if($productions) { ?>
                <div id='productionsList'>
                    <?php foreach ($productions as $production) { ?>
                        <div class='productionCard'>
                            <?php if (file_exists(getcwd().$production->getPoster()->getPath())) { ?>
                                <div class='productionImg' style="background-image: url('<?= $production->getPoster()->getPath() ?>')"></div>
                            <?php } else { ?>
                                <div class='productionImg' style="background-image: url('<?= PATH_TO_IMG."default_poster.jpg" ?>')"></div>
                            <?php } ?>

                            <div class='productionName'>
                                <?php
                                    if($production->getParentProduction()) {
                                        if($production->getParentProduction()->getParentProduction()) {
                                            echo $production->getParentProduction()->getParentProduction()->getTitle().' - ';
                                        }
                                        echo $production->getParentProduction()->getTitle().' - ';
                                    }
                                    echo $production->getTitle();
                                ?>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            <?php } else { ?>
                <p>
                    <a href='<?= Helpers::callRoute('productions_creation_tmdb') ?>'><button class='btn'>Ajouter une production</button></a>
                </p>
            <?php } ?>
        </div>

        <div class="linkButton">
            <?php if($productions) { ?>
                <a href='<?= Helpers::callRoute('productions_list') ?>'><button class='btn'>Voir toutes les productions</button></a>
            <?php } ?>
        </div>

    </div>

    <!--STATISTICS-->
    <div class="card">
        <div class="cardTitle">Statistiques</div>
        <div id="statistics">
        </div>
        <div class="linkButton">
            <a href='<?= Helpers::callRoute('stats') ?>'><button class='btn'>Voir toutes les statistiques</button></a>
        </div>
    </div>

</div>