<?php

use App\Core\Helpers; ?>

<footer id="footer-front">

    <article class="logo-section">
        <a href="<?= Helpers::callRoute('front_home') ?>" class="brandLogo">
            <img src='<?= PATH_TO_IMG ?>logo_uv.png' alt='ultraviolet logo'>
        </a>
    </article>

    <article class="path-section">
        <?php foreach ($pages as $page) : ?>
            <a href="<?= Helpers::callRoute('display_static_page', ['page' => $page->getSlug()]) ?>"><?= $page->getTitle() ?></a>
        <?php endforeach; ?>
    </article>

    <article class="path-section">
        <?php foreach ($pages as $page) : ?>
            <a href="<?= Helpers::callRoute('display_static_page', ['page' => $page->getSlug()]) ?>"><?= $page->getTitle() ?></a>
        <?php endforeach; ?>
    </article>

    

</footer>