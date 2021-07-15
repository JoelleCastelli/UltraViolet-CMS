<?php use App\Core\Helpers; ?>

<footer id="footer-front" >
    <?php foreach ($pages as $page) : ?>
        <a href="<?= Helpers::callRoute('display_page', ['page', $page->getSlug()]) ?>"><?= $page->getTitle() ?></a>
    <?php endforeach; ?>
</footer>