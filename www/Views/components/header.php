<?php

use App\Core\Helpers;
use App\Core\Request;
use App\Models\Settings;
$settings = new Settings();
$appName = $settings->findOneBy('selector', 'appName')->getValue();
?>

<header class="header">
    <h1><?= $title ?? '' ?></h1>
    <div class=left-controls>
        <div id='userImage' class="dropdown dropdown-button user-label">
            <span><?= Request::getUser()->getPseudo() ?></span>
            <img src="<?= Request::getUser()->getMedia()->getPath() ?>" alt="Photo de profil">
            <div class="dropdown-content dropdown-user">
                <a href="<?= Helpers::callRoute('user_update') ?>">Paramètres utilisateur</a>
                <a href="<?= Helpers::callRoute('update_password') ?>">Modifier mot de passe</a>
                <a href="<?= Helpers::callRoute('front_home') ?>"><?= $appName ?></a>
                <a href="<?= Helpers::callRoute('logout') ?>">Déconnexion</a>
            </div>
        </div>
    </div>
</header>