<?php

use App\Models\Category;
use App\Core\Helpers;
use App\Core\Request;

$user = Request::getUser();
$categoriesNavbar = Category::getMenuCategories();
?>

<nav id="navbar-front">
    <a href="<?= Helpers::callRoute('front_home') ?>" class="brandLogo">
        <img src='<?= PATH_TO_IMG ?>logo/logo.png' alt='Logo <?= APP_NAME ?>'>
    </a>
    
    
    <?php foreach ($categoriesNavbar['main'] as $mainCategory) : ?>
        <a href="<?= Helpers::callRoute('display_category', ['category' => Helpers::slugify($mainCategory->getName())]) ?>"><?= $mainCategory->getName() ?></a>
    <?php endforeach; ?>
    

    <?php if (!empty($categoriesNavbar['other'])) : ?>
        <div id='otherCategories' class="dropdown dropdown-button">
            <span>Toutes les catégories</span>
            <div class="dropdown-content">
                <?php foreach ($categoriesNavbar['other']  as $otherCategory) : ?>
                    <a href="<?= Helpers::callRoute('display_category', ['category' => Helpers::slugify($otherCategory->getName())]) ?>"><?= $otherCategory->getName() ?></a>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endif; ?>

    <?php

    if ($user && $user->isLogged()) : ?>
        <div id='userImage' class="dropdown dropdown-button">
            <img src="<?= Request::getUser()->getMedia()->getPath() ?>" alt="Photo de profil">
            <div class="dropdown-content dropdown-user">
                <a href="<?= Helpers::callRoute('user_update') ?>">Paramètres</a>
                <?php if ($user->canAccessBackOffice()) : ?>
                    <a href="<?= Helpers::callRoute('admin') ?>">Administration</a>
                <?php endif; ?>
                <a href="<?= Helpers::callRoute('logout') ?>">Déconnexion</a>
            </div>
        </div>

    <?php else : ?>
        <a href="<?= Helpers::callRoute('register') ?>"><button class="btn btn-register">Inscription</button></a>
        <a href="<?= Helpers::callRoute('login') ?>"><button class="btn btn-login">Connexion</button></a>
    <?php endif; ?>

</nav>