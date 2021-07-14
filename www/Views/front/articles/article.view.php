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

    <div class="production card"></div>

</div>