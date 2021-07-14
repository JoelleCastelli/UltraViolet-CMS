<!-- <h1><?= $article->getTitle() ?></h1>

<h2>Tags</h2>
<?php foreach($categories as $category) : ?>
    <p><?= $category->getName() ?></p>
<?php endforeach; ?>

<ul>
    <li> <img src="<?= $article->getMedia()->getPath(); ?>"></li>
    <li><?= $article->getContent() ?></li>
</ul> -->

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

    <section class="production card">

        <div class="production__image"></div>
        <h2 class="production__title">Le seigneur des anneaux</h2>
        <ul class="production__actors">
            <li class="production__actors__actor">Jean Michel</li>
            <li class="production__actors__actor">Crapoto del mare</li>
            <li class="production__actors__actor">Inspector Boudacheh</li>
        </ul>

    </section>

    <section class="comments card">

        <h2 class="title-section">Section commentaire</h2>

        <!-- <?php if (isset($form)) App\Core\FormBuilder::render($form); ?> -->

        <?php foreach($comments as $comment) : ?>
        <div class="comment">
            <img class="comment__profile-picture" src="<?=PATH_TO_IMG?>default_user.jpg"></img>
            <h3 class="comment__title">Ecrit par <?= $comment->getPerson()->getPseudo() ?> le <?= $comment->getCreatedAt() ?></h3>
            <p class="comment__content"><?= $comment->getContent() ?></p>
        </div>
        <?php endforeach; ?>


    </section>

</div>

