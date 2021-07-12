<h1><?= $article->getTitle() ?></h1>

<h2>Tags</h2>
<?php foreach($categories as $category) : ?>
    <p><?= $category->getName() ?></p>
<?php endforeach; ?>

<ul>
    <li> <img src="<?= $article->getMedia()->getPath(); ?>"></li>
    <li><?= $article->getContent() ?></li>
</ul>