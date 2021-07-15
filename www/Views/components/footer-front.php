<?php

use App\Core\Helpers; ?>


<footer id="footer-front">

    <article class="logo-section">
        <a href="<?= Helpers::callRoute('front_home') ?>" class="brandLogo">
            <img src='<?= PATH_TO_IMG ?>logo_uv.png' alt='ultraviolet logo'>
        </a>
    </article>

    <article class="path-section">
        <?php for ($i = 0; $i < 9; $i++) : ?>
            <a href="#">Site <?= $i ?></a>
        <?php endfor; ?>
    </article>

</footer>

<!-- <footer id="footer-front">
    <?php foreach ($pages as $page) : ?>
        <a href="<?= Helpers::callRoute('display_page', ['page', $page->getSlug()]) ?>"><?= $page->getTitle() ?></a>
    <?php endforeach; ?>
</footer> -->