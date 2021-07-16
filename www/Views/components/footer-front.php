<?php

use App\Core\Helpers; ?>

<footer id="footer-front">

    <article class="logo-section">
        <a href="<?= Helpers::callRoute('front_home') ?>" class="brandLogo">
            <img src='<?= PATH_TO_IMG ?>logo_uv.png' alt='ultraviolet logo'>
        </a>
    </article>

    <div class="path-section">
        <article >
            <?php foreach ($pages as $page) : ?>
                <a href="<?= Helpers::callRoute('display_static_page', ['page' => $page->getSlug()]) ?>"><?= $page->getTitle() ?></a>
            <?php endforeach; ?>
        </article>

        <article>
            <p>©2021 Ultraviolet technologies Inc.</p>
            <p>Ce site est protégé par Joëlle CASTELLI. Par ailleurs, la Politique de confidentialité et les Conditions d'utilisation de Joëlle s'appliquent.</p>
        </article>
    </div>
</footer>