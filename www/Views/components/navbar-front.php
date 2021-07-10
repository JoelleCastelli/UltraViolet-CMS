<?php

use App\Core\Helpers;
use App\Core\Request;

$categories = Helpers::getCategories();
?>

<nav id="navbar-front">
    <a href="<?= Helpers::callRoute('admin') ?>" class="brandLogo">
        <img src='<?= PATH_TO_IMG ?>logo_uv.png' alt='ultraviolet logo'>
    </a>

    <?php foreach ($categories['main'] as $mainCategory) : ?>
        <a href="/categorie/<?= Helpers::slugify($mainCategory->getName()) ?>"><?= $mainCategory->getName() ?></a>
    <?php endforeach; ?>

    <div class="dropdown">
        <span class="dropdown-button">Toutes les catégories</span>
        <div class="dropdown-content">
            <?php foreach ($categories['other']  as $otherCategory) : ?>
                <a href="/categorie/<?= Helpers::slugify($otherCategory->getName()) ?>"><?= $otherCategory->getName() ?></a>
            <?php endforeach; ?>
        </div>
    </div>

    <?php
    $user = Request::getUser();
    if ($user && $user->isLogged()) : ?>
        <a href="<?= Helpers::callRoute('logout') ?>"><button class="btn btn-login">Deconnexion</button></a>
        <?php if ($user->canAccessBackOffice()) : ?>
            <a href="<?= Helpers::callRoute('admin') ?>"><button class="btn btn-register">Administration</button></a>
        <?php endif; ?>

    <?php else : ?>
        <a href="<?= Helpers::callRoute('subscription') ?>"><button class="btn btn-register">S'inscrire</button></a>
        <a href="<?= Helpers::callRoute('login') ?>"><button class="btn btn-login">Connexion</button></a>
    <?php endif; ?>

</nav>