<h1><?= $article->getTitle() ?></h1>


<ul>
    <li> <img src="<?= $article->getMedia()->getPath(); ?>"></li>
    <li><?= $article->getContent() ?></li>
</ul>