<header class="header">
    <h1><?php if (isset($title)) echo $title; else echo "Titre top bar"; ?></h1>
    <div class=left-controls>
        <i class="fas fa-bell"></i>
        <div class="user-label">
            <span><?php if (isset($user)) echo $user; else echo "Nom prénom"; ?></span>
            <img src="https://randomuser.me/api/portraits/men/93.jpg" alt="profile-picture">
        </div>
    </div>
</header>