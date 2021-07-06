<div id='installation' class="card">
    <div class="logo">
        <img src='<?= PATH_TO_IMG ?>logo_uv_transparent.png' alt='ultraviolet logo'>
    </div>
    <div class="details">
        <p>
            Bienvenue sur UltraViolet. Avant de nous lancer, nous avons besoin de certaines informations sur votre base de données.
            Il va vous falloir réunir les informations suivantes pour continuer :
        </p>

        <ul>
            <li>Nom de la base de données</li>
            <li>Identifiants MySQL</li>
            <li>Mot de passe de base de données</li>
            <li>Hôte de base de données</li>
            <li>Préfixe de table</li>
        </ul>

        <p>
            Nous allons utiliser ces informations pour créer le fichier .env.
            Si pour une raison ou pour une autre la création automatique du fichier ne fonctionne pas, ne vous inquiétez pas.
            Sa seule action est d’ajouter les informations de la base de données dans un fichier de configuration. Vous pouvez
            aussi simplement ouvrir le fichier ".env.example" dans un éditeur de texte, y remplir vos informations et
            l’enregistrer sous le nom de ".env".
        </p>
    </div>
    <a class="btn" href="<?= \App\Core\Helpers::callRoute('configStep2')?>">Go !</a>
</div>