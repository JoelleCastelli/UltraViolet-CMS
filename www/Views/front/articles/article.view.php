<?php use App\Core\Helpers; ?>
<?php use App\Core\Request; ?>
<?php use App\Models\Production; ?>

<div class="grid-article">

    <section class="article card">
        <div class="cover">
            <img alt="image de couverture de l'article" src="<?= $article->getMedia()->getPath() ?>">
        </div>
        <h1 class="article__title"><?= $article->getTitle() ?></h1>
        <small class="article__author">
            Ecrit par <?= $article->getPerson()->getPseudo() ?> le <?= $article->getCleanPublicationDate() ?>
            <?php if($article->getCleanContentUpdatedAt() != "") {?>
                <span> (modifié le <?= $article->getCleanContentUpdatedAt()?>) </span>
            <?php } ?>
        </small>
        <div class="article__tags">
            <?php foreach ($article->getCategories() as $category) : ?>
                <a href="<?= Helpers::callRoute('display_category', ['category' => Helpers::slugify($category->getName())]) ?>">
                    <div class="tag-item tagsBackground tagsColor"><?= $category->getName() ?></div>
                </a>
            <?php endforeach; ?>
        </div>
        <article>
            <?= $article->getContent() ?>
        </article>
    </section>


    <?php if (!empty($production)) :  ?>
        <section id="production-card" class="production card">
            <img class="production__image" src="<?= $production->getProductionPosterPath() ?>">
            <h2 class="production__title"><?= $production->getTitle() ?></h2>
            <p class="production__type tag-item tagsBackground tagsColor"><?= $production->getTranslatedType() ?></p>
            <p class="production__release-date">Date de sortie : <?= $production->getCleanReleaseDate() ?></p>
            <p></p>
        </section>
    <?php endif; ?>


    <section class="comments card">

        <h2 class="title-section">Commentaires</h2>

        <!--Comment form only for logged users-->
        <?php if(Request::getUser()->isLogged()) { ?>
            <div id="add-btn" class="title-btn">
                <button class="btn title-btn tagsBackground tagsColor">Commenter
                    <div class="add-btn"></div>
                </button>
            </div>

            <div id="test-comment" class="test-comment">
                <?php if (isset($form)) App\Core\FormBuilder::render($form); ?>
            </div>
        <?php } else { ?>
            <div class="noComment">
                <a target="_blank" href="<?= Helpers::callRoute('login') ?>">Connectez-vous ou inscrivez-vous pour commenter cet article</a>
            </div>
        <?php } ?>

        <?php
        if(!empty($comments)) {
            foreach ($comments as $comment) { ?>
                <div class="comment">
                    <img class="comment__profile-picture" src="<?= $comment->getPerson()->getMedia()->getPath() ?>">
                    <h3 class="comment__title">Ecrit par <?= $comment->getPerson()->getPseudo() ?? "Anonyme" ?> le <?= $comment->getCleanCreationDate() ?></h3>
                    <p class="comment__content"><?= $comment->getContent() ?></p>
                </div>
            <?php }
        } else { ?>
            <p>Aucun commentaire sur cet article</p>
        <?php }?>


    </section>

</div>

<!-- PRODUCTION MODAL -->

<?php if (!empty($production)) :  ?>

    <div id="details-modal" class="background-modal-production">
        <div class="clickable-bg"></div>
        <div class="modal-production-details prod">

            <img class="prod__cover" src="<?= $production->getProductionPosterPath() ?>">
            <article class="prod__details">
                <h1 class="prod__details_title"><?= $production->getTitle() ?></h1>
                <?php if($production->getType() == 'season') {
                    $series = new Production();
                    $series = $series->select()->where('id', $production->getParentProductionId())->first() ?>
                    <p class="prod__details_type tag-item"><b>Série :</b> <?= $series->getTitle() ?></p>
                <?php } ?>
                <?php if($production->getType() == 'episode') {
                    $season = new Production();
                    $season = $season->select()->where('id', $production->getParentProductionId())->first();
                    $series = new Production();
                    $series = $series->select()->where('id', $season->getParentProductionId())->first(); ?>

                    <p class="prod__details_type tag-item"><b>Série :</b> <?= $series->getTitle() ?></p>
                    <p class="prod__details_type tag-item"><b>Saison :</b> <?= $season->getTitle() ?></p>
                <?php } ?>
                <p class="prod__details_type tag-item"><b>Type :</b> <?= $production->getTranslatedType() ?></p>
                <?php if($production->getOriginalTitle()) { ?>
                    <p class="prod__details_type"><b>Titre original :</b> <?= $production->getOriginalTitle() ?></p>
                <?php } ?>
                <p class="prod__details_date"><b>Date de sortie :</b> <?= $production->getCleanReleaseDate() ?></p>
                <p class="prod__details_date"><b>Durée :</b> <?= $production->getRuntime() ?> minutes</p>
                <?php if($production->getOverview()) { ?>
                    <small class="prod__details_resume"><b>Résumé :</b> <br><?= $production->getOverview() ?></small>
                <?php } ?>
            </article>


            <?php if(!empty($actors)) { ?>
                <article class="prod__actors">
                    <h1 class="prod-modal-title" >Casting</h1>
                    <?php foreach ($actors as $actor) {
                        echo "<div class='person-card'>";
                            if(!file_exists(getcwd().$actor["photo"]))
                                echo "<img class='person-img' src='" . PATH_TO_IMG . "default_poster.jpg'/>";
                            else
                                echo "<img class='person-img' src='" . $actor["photo"] . "'/>";
                            echo "<div class='person-name'>".$actor["fullName"]."</div>";
                            echo "<div class='character'>".$actor["role"]."</div>";
                        echo "</div>";
                    } ?>
                </article>
            <?php } ?>

            <?php if(!empty($directors)) { ?>
                <article class="prod__directors">
                    <h1 class="prod-modal-title">Réalisation</h1>
                    <?php foreach ($directors as $director) {
                        echo "<div class='person-card'>";
                        if(!file_exists(getcwd().$director["photo"]))
                            echo "<img class='person-img' src='" . PATH_TO_IMG . "default_poster.jpg'/>";
                        else
                            echo "<img class='person-img' src='" . $director["photo"] . "'/>";
                        echo "<div class='person-name'>".$director["fullName"]."</div>";
                        echo "</div>";
                    } ?>
                </article>
            <?php } ?>

            <?php if(!empty($writers)) { ?>
                <article class="prod__writers">
                    <h1 class="prod-modal-title">Scénario</h1>
                    <?php foreach ($writers as $writer) {
                        echo "<div class='person-card'>";
                        if(!file_exists(getcwd().$writer["photo"]))
                            echo "<img class='person-img' src='" . PATH_TO_IMG . "default_poster.jpg'/>";
                        else
                            echo "<img class='person-img' src='" . $writer["photo"] . "'/>";
                        echo "<div class='person-name'>".$writer["fullName"]."</div>";
                        echo "</div>";
                    } ?>
                </article>
            <?php } ?>

            <?php if(!empty($creators)) { ?>
                <article class="prod__creators">
                    <h1 class="prod-modal-title">Création</h1>
                    <?php foreach ($creators as $creator) {
                        echo "<div class='person-card'>";
                        if(!file_exists(getcwd().$creator["photo"]))
                            echo "<img class='person-img' src='" . PATH_TO_IMG . "default_poster.jpg'/>";
                        else
                            echo "<img class='person-img' src='" . $creator["photo"] . "'/>";
                        echo "<div class='person-name'>".$creator["fullName"]."</div>";
                        echo "</div>";
                    } ?>
                </article>
            <?php } ?>

        </div>
    </div>

<?php endif; ?>