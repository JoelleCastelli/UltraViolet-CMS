<?php use App\Core\Helpers; ?>

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
                <a href="<?= Helpers::callRoute('display_category', ['category' => Helpers::slugify($category->getName())]) ?>"><div class="article__tags__category tag-item"><?= $category->getName() ?></div></a>
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
            <p class="production__type tag-item"><?= $production->getTranslatedType() ?></p>
            <p class="production__release-date">Date de sortie : <?= $production->getCleanReleaseDate() ?></p>
            <p></p>
        </section>
    <?php endif; ?>


    <section class="comments card">

        <h2 class="title-section">Commentaires</h2>

        <div id="add-btn" class="title-btn">
            <button class="btn title-btn">Commenter
                <div class="add-btn"></div>
            </button>
        </div>


        <div id="test-comment" class="test-comment">
            <?php if (isset($form)) App\Core\FormBuilder::render($form); ?>
        </div>

        <?php foreach ($comments as $comment) : ?>
            <div class="comment">
                <img class="comment__profile-picture" src="<?= PATH_TO_IMG ?>default_user.jpg">
                <h3 class="comment__title">Ecrit par <?= $comment->getPerson()->getPseudo() ?? "Anonyme" ?> le <?= $comment->getCleanCreationDate() ?></h3>
                <p class="comment__content"><?= $comment->getContent() ?></p>
            </div>
        <?php endforeach; ?>


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
                <p class="prod__details_type tag-item"><b>Type :</b> <?= $production->getTranslatedType() ?></p>
                <p class="prod__details_type"><b>Titre original :</b> <?= $production->getOriginalTitle() ?></p>
                <p class="prod__details_date"><b>Date de sortie :</b> <?= $production->getCleanReleaseDate() ?></p>
                <p class="prod__details_date"><b>Durée :</b> <?= $production->getRuntime() ?> minutes</p>
                <small class="prod__details_resume"><b>Résumé :</b> <br><?= $production->getOverview() ?></small>
            </article>


            <?php if(!empty($actors)) { ?>
                <article class="prod__actors">
                    <h1 class="prod-modal-title" >Casting</h1>
                    <?php foreach ($actors as $actor) {
                        echo "<div class='person-card'>";
                            if ($actor["photo"] == '')
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
                        if ($director["photo"] == '')
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
                        if ($writer["photo"] == '')
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
                        if ($creator["photo"] == '')
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