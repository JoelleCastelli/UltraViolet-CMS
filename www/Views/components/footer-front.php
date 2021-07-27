<?php
    use App\Core\Helpers;
    use App\Models\Page;
    use App\Models\Settings;
    $pages = Page::getStaticPages();
?>

<footer id="footer-front" class="footerBackground">

    <article class="logo-section">
        <a href="<?= Helpers::callRoute('front_home') ?>" class="brandLogo">
            <img src='<?= PATH_TO_IMG ?>logo/logo.png' alt='Logo <?= Settings::getAppName() ?>'>
        </a>
    </article>

    <div class="path-section">
        <article>
            <?php foreach ($pages as $page) : ?>
                <a class="footerColor footerColorHover" href="<?= Helpers::callRoute('display_static_page', ['page' => $page->getSlug()]) ?>"><?= $page->getTitle() ?></a>
            <?php endforeach; ?>
        </article>

        <article class="footerColor">
            <p>©2021 UltraViolet Technologies Inc.</p>
            <p>Ce site est protégé par Joëlle CASTELLI. Par ailleurs, la Politique de confidentialité et les Conditions d'utilisation de Joëlle s'appliquent.</p>
        </article>
    </div>
</footer>