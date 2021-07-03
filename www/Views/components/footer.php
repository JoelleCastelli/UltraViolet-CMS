<?php use App\Core\Helpers; ?>

<a href="<?= Helpers::callRoute('admin') ?>">
    <i class="fas fa-circle-notch fa-fw"></i>
    <div class="navLabel">Tableau de bord</div>
</a>
<a href="<?= Helpers::callRoute('pages_list') ?>">
    <i class="fas fa-pager fa-fw"></i>
    <div class="navLabel">Pages</div>
</a>
<a href="<?= Helpers::callRoute('articles_list') ?>">
    <i class="fas fa-newspaper fa-fw"></i>
    <div class="navLabel">Articles</div>
</a>