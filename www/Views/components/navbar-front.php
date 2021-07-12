<?php

use App\Models\Category;
use App\Core\Helpers;
use App\Core\Request;

$categories = Category::getMenuCategories();
?>

<nav id="navbar-front">
    <a href="<?= Helpers::callRoute('front_home') ?>" class="brandLogo">
        <img src='<?= PATH_TO_IMG ?>logo_uv.png' alt='ultraviolet logo'>
    </a>

    <?php foreach ($categories['main'] as $mainCategory) : ?>
        <a href="<?= Helpers::callRoute('display_category', ['category' => Helpers::slugify($mainCategory->getName())]) ?>"><?= $mainCategory->getName() ?></a>
    <?php endforeach; ?>

    <div class="dropdown">
        <span class="dropdown-button">Toutes les catégories</span>
        <div class="dropdown-content">
            <?php foreach ($categories['other']  as $otherCategory) : ?>
                <a href="<?= Helpers::callRoute('display_category', ['category' => Helpers::slugify($otherCategory->getName())]) ?>"><?= $otherCategory->getName() ?></a>
            <?php endforeach; ?>
        </div>
    </div>

    <?php
    $user = Request::getUser();
    if ($user && $user->isLogged()) : ?>

        <div class="dropdown">
            <button class="dropdown-button"><img class="img-profile" src="<?= Request::getUser()->getMedia()->getPath() ?>"></button>
            <div class="dropdown-content">
                <a href="#">Paramètres</a>
                <?php if ($user->canAccessBackOffice()) : ?>
                    <a href="<?= Helpers::callRoute('admin') ?>">Administration</a>
                <?php endif; ?>
                <a href="<?= Helpers::callRoute('logout') ?>">Deconnexion</a>
            </div>
        </div>

    <?php else : ?>
        <a href="<?= Helpers::callRoute('register') ?>"><button class="btn btn-register">S'inscrire</button></a>
        <a href="<?= Helpers::callRoute('login') ?>"><button class="btn btn-login">Connexion</button></a>
    <?php endif; ?>

</nav>