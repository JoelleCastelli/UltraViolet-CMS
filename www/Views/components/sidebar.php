<?php
    use App\Core\Helpers;
    use App\Core\Request;
    $user = Request::getUser();
?>
<nav id="sidebar">
    <a href="<?= Helpers::callRoute('admin') ?>" class="brandLogo">
        <img src='<?= PATH_TO_IMG ?>logo_uv.png' alt='ultraviolet logo'>
    </a>
    <span id="cta-toggle-sidebar" onclick="toggleSidebar()">
        <i class="fas fa-angle-left fa-fw"></i>
    </span>
    <a href="<?= Helpers::callRoute('admin') ?>">
        <i class="fas fa-circle-notch fa-fw"></i>
        <div class="navLabel">Tableau de bord</div>
    </a>
    <a href="<?= Helpers::callRoute('pages_list') ?>">
        <i class="fas fa-pager fa-fw"></i>
        <div class="navLabel">Pages</div>
    </a>
    <a href="<?= Helpers::callRoute('categories_list') ?>">
        <i class="fas fa-tags fa-fw"></i>
        <div class="navLabel">Catégories</div>
    </a>
    <a href="<?= Helpers::callRoute('articles_list') ?>">
        <i class="fas fa-newspaper fa-fw"></i>
        <div class="navLabel">Articles</div>
    </a>
    <a href="<?= Helpers::callRoute('comments_list') ?>">
        <i class="fas fa-comments fa-fw"></i>
        <div class="navLabel">Commentaires</div>
    </a>
    <a href="<?= Helpers::callRoute('productions_list') ?>">
        <i class="fas fa-book fa-fw"></i>
        <div class="navLabel">Productions</div>
    </a>
    <a href="<?= Helpers::callRoute('media_list') ?>">
        <i class="fas fa-photo-video"></i>
        <div class="navLabel">Médias</div>
    </a>
    <a href="<?= Helpers::callRoute('users_list') ?>">
        <i class="fas fa-users fa-fw"></i>
        <div class="navLabel">Utilisateurs</div>
    </a>
    <a href="<?= Helpers::callRoute('templates_lists') ?>">
        <i class="fas fa-paste fa-fw"></i>
        <div class="navLabel">Templates</div>
    </a>
    <a href="<?= Helpers::callRoute('stats') ?>">
        <i class="fas fa-chart-line fa-fw"></i>
        <div class="navLabel">Statistiques</div>
    </a>
    <?php if ($user && $user->isLogged() && $user->isAdmin()) { ?>
        <a href="<?= Helpers::callRoute('settings') ?>">
            <i class="fas fa-cogs fa-fw"></i>
            <div class="navLabel">Paramètres utilisateur</div>
        </a>
    <?php } ?>
</nav>