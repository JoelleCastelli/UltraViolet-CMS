<header class="header">
    <h1><?= $title ?? ''?></h1>
    <div class=left-controls>
        <div class="user-label">
            <span><?= \App\Core\Request::getUser()->getPseudo() ?></span>
            <img src="<?= \App\Core\Request::getUser()->getMedia()->getPath() ?>" alt="Photo de profil">
        </div>
    </div>
</header>