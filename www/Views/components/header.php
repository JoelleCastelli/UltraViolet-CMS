<?php

use App\Core\Helpers;
use App\Core\Request;
?>

<header class="header">
    <h1><?= $title ?? '' ?></h1>
    <div class=left-controls>
        <div id='userImage' class="dropdown dropdown-button user-label">
            <span><?= Request::getUser()->getPseudo() ?></span>
            <img src="<?= Request::getUser()->getMedia()->getPath() ?>" alt="Photo de profil">
            <div class="dropdown-content dropdown-user">
                <a href="#">Paramètres</a>
                <a href="<?= Helpers::callRoute('front_home') ?>"><?= APP_NAME ?></a>
                <a href="<?= Helpers::callRoute('logout') ?>">Déconnexion</a>
            </div>
        </div>
        <!--   <div class="user-label">
            <span><?= \App\Core\Request::getUser()->getPseudo() ?></span>
            <img class="img-profil" src="<?= \App\Core\Request::getUser()->getMedia()->getPath() ?>" alt="Photo de profil">
        </div> -->
    </div>
</header>