<header class="header">
    <h1><?= $title ?? 'Titre de la page'?></h1>
    <div class=left-controls>
        <i class="fas fa-bell"></i>
        <div class="user-label">
            <span><?= $user->name ?? 'Utilisateur inconnu'?></span>
            <img src="https://randomuser.me/api/portraits/men/93.jpg" alt="profile-picture">
        </div>
    </div>
</header>