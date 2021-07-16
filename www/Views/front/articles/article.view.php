<?php use App\Core\Helpers; ?>

<div class="grid-article">

    <div class="cover">
        <img alt="imge de couverture de l'article" src="<?= $article->getMedia()->getPath() ?>"></img>
    </div>

    <section class="article card">
        <h1 class="article__title"><?= $article->getTitle() ?></h1>
        <small class="article__author">Ecrit par <?= $article->getPerson()->getPseudo() ?> le <?= $article->getPublicationDate() ?></small>
        <div class="article__tags">
            <?php foreach($article->getCategories() as $category) : ?>
                <div class="article__tags__category"><?= $category->getName() ?></div>
            <?php endforeach; ?>
        </div>
        <article>
            <?= $article->getContent() ?>
        </article>
    </section>
    
    <?php foreach($article->getProductions() as $production) : ?>
        <section class="production card">
            <img class="production__image" src="<?= $production->getProductionPosterPath() ?>"></img>
            <h2 class="production__title"><?= $production->getTitle() ?></h2>
            <p class="production__type"><?= $production->getType() ?></p>
            <p class="production__release-date"><?= $production->getReleaseDate() ?></p>
            <p></p>
        </section>
    <?php endforeach; ?>

    <section class="comments card">

        <h2 class="title-section">Section commentaire</h2>

        <div id="add-btn" class="title-btn">
            <button class="btn title-btn">Commenter
                <div class="add-btn"></div>
            </button>
        </div>
        

        <div id="test-comment" class="test-comment">
            <?php if (isset($form)) App\Core\FormBuilder::render($form); ?>
        </div>

        <?php foreach($comments as $comment) : ?>
        <div class="comment">
            <img class="comment__profile-picture" src="<?=PATH_TO_IMG?>default_user.jpg"></img>
            <h3 class="comment__title">Ecrit par <?= $comment->getPerson()->getPseudo() ?> le <?= $comment->getCreatedAt() ?></h3>
            <p class="comment__content"><?= $comment->getContent() ?></p>
        </div>
        <?php endforeach; ?>


    </section>

</div>

