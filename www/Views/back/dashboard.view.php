<?php
use App\Core\Helpers;
use App\Core\Request;
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
                        <div id="article-<?=$article->getId()?>" class='articleCard'>
                            <div class="articleInfos">
                                <div class="articleTitle"><?= $article->getTitle() ?></div>
                                <div class="articleDetails">Publié le <?= $article->getCleanPublicationDate() ?> par <?= $article->getPerson()->getPseudo() ?></div>
                            </div>
                            <div class="articleActions">
                                <div class="comments">
                                    <i class="fas fa-comment-dots"></i>
                                    <span class="commentsNb"><?= count($article->getComments()) ?></span>
                                </div>
                                <?php if(!Request::getUser()->isModerator()) {
                                    echo $article->generateActionsMenu();
                                } ?>
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
                        <div class='commentCard'>
                            <div class="userPicture">
                                <img src="<?= $comment->getPerson()->getMedia()->getPath() ?>" alt="Photo de profil">
                            </div>
                            <div class="commentDetails">
                                <div class="commentHeader">
                                    <span class="username">
                                        <?= $comment->getPerson()->getPseudo() ?>
                                    </span>
                                    <span class="articleTitle">
                                        <?= $comment->getArticle()->getTitle() ?>
                                    </span>
                                </div>
                                <div class="commentPreview">
                                    <?= $comment->getContent() ?>
                                </div>
                            </div>

                            <?= $comment->generateActionsMenu() ?>

                        </div>
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
            <div id='statisticsList'>
                <div class='statisticsCard'>
                    <span class="title">
                        Nombres d'articles total
                    </span>
                    <span class="stat">
                        <?= $nbArticles ?>
                    </span>
                </div>
                <div class='statisticsCard'>
                    <span class="title">
                        Nombres de commentaire total
                    </span>
                    <span class="stat">
                        <?= $nbComments ?>
                    </span>
                </div>
                <div class='statisticsCard'>
                    <span class="title">
                        Nombres d'utilisateur total
                    </span>
                    <span class="stat">
                        <?= $nbUsers ?>
                    </span>
                </div>
                <div class='statisticsCard'>
                    <span class="title">
                        Nombres de vues total
                    </span>
                    <span class="stat">
                        <?= $nbViews ?>
                    </span>
                </div>
            </div>
        <div class="linkButton">
            <a href='<?= Helpers::callRoute('stats') ?>'><button class='btn'>Voir toutes les statistiques</button></a>
        </div>
    </div>

</div>