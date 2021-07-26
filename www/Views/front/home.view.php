<?php

use App\Core\Helpers; ?>

<?php if (empty($articles)) : ?>
    <h1>Aucun article récents.</h1>
<?php else : ?>
    <section class="article-list">
        <h1>Les articles récents</h1>
        <?php foreach ($articles as $article) : ?>
            <a href="<?= Helpers::callRoute('display_article', ['article' => $article->getSlug()])  ?>">
                <article class="grid-article-card article-card">
                    <img class="article-card__cover" src="<?= $article->getMedia()->getPath(); ?>" alt="">
                    <h2 class="article-card__title"><?= $article->getTitle(); ?></h2>
                    <p class="article-card__description"><?= $article->getDescription(); ?></p>
                    <small class="article-card__author">
                        <span>par <?= $article->getPerson()->getPseudo(); ?></span>
                        <span>Publié le <?= $article->getCleanPublicationDate(); ?></span>
                    </small>
                </article>
            </a>
        <?php endforeach; ?>
    </section>
<?php endif; ?>