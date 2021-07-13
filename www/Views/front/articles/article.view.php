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

    <img class="cover" alt="imge de couverture de l'article" src="<?= $article->getMedia()->getPath() ?>"></img>

    <div class="article">
        <h1><?= $article->getTitle() ?></h1>
        <small>Ecrit par <?= $article->getPerson()->getPseudo() ?> le <?= $article->getPublicationDate() ?></small>
    </div>

    <div class="production"></div>

</div>