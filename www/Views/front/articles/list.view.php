<?php

use App\Core\Helpers; ?>

<?php if (empty($articles)) : ?>
    <p>Aucun article associés à cette catégorie.</p>
<?php else : ?>
    <ul>

        <?php foreach ($articles as $article) : ?>

            <li>

                Image : <img style="height: 6rem; width: 10rem" src="<?= $article->getMedia()->getPath(); ?>"><br>
                Dernière MAJ : <?= $article->getContentUpdatedAt(); ?><br>
                Titre : <?= $article->getTitle(); ?><br>
                Description : <?= $article->getDescription(); ?><br>
                Contenu : <?= $article->getContent(); ?><br>

            </li>

        <?php endforeach; ?>
    </ul>
<?php endif; ?>