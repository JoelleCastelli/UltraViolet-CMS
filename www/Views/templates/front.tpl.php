<!DOCTYPE html>
<html lang="fr">
<head>
	<meta charset="UTF-8">
	<title>Template de front</title>
	<meta name="description" content="description de la page de front">
	<link rel="stylesheet" href="../../dist/main.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.2/css/all.min.css" integrity="sha512-HK5fgLBL+xu6dm/Ii3z4xhlSUyZgTT9tuc/hSrtw6uzJOvgRr2a9jyxxT1ely+B+xFAmJKVSTbpM/CuL7qxO8w==" crossorigin="anonymous" />
	
</head>
<body>

	<div class="container">
		<nav id="sidebar">
			<ul>
				<li id="sidebar-label">
					<img src="https://upload.wikimedia.org/wikipedia/commons/thumb/e/e7/Instagram_logo_2016.svg/1200px-Instagram_logo_2016.svg.png" width="30px" alt="logo-site">
					<div>Ultra Violet</div>
				</li>
				<li>
					<div id="cta-toggle-sidebar">
						<i class="fas fa-angle-left"></i>
					</div>
				</li>
				<li>
					<i class="fas fa-circle-notch"></i>
					<div class="grow-separator"></div>
					<div>Overview</div>
				</li>
				<li>
					<i class="fas fa-pager"></i>
					<div class="grow-separator"></div>
					<div>Pages</div>
				</li>
				<li>
					<i class="fas fa-newspaper"></i>
					<div class="grow-separator"></div>
					<div>Article</div>
				</li>
				<li>
					<i class="fas fa-book"></i>
					<div class="grow-separator"></div>
					<div>Collections</div>
				</li>
				<li>
					<i class="fas fa-comments"></i>
					<div class="grow-separator"></div>
					<div>Commentaires</div>
				</li>
				<li>
					<i class="fas fa-paste"></i>
					<div class="grow-separator"></div>
					<div>Templates</div>
				</li>
				<li>
					<i class="fas fa-chart-line"></i>
					<div class="grow-separator"></div>
					<div>Statistiques</div>
				</li>
				<li>
					<hr class="grow-separator">
				</li>
				<li>
					<i class="fas fa-cogs"></i>
					<div class="grow-separator"></div>
					<div>Settings</div>
				</li>
				<li>
					<i class="fas fa-certificate"></i>
					<div class="grow-separator"></div>
					<div>Subscription</div>
				</li>
			</ul>
		</nav>

		<main class="main">
			
			<div class="grid-modify-article">

				<header class="header">
					<h1>Modifier un article</h1>
					<div class=left-controls>
						<i class="fas fa-bell"></i>
						<div class="user-label">
							<span>Kamal Hennou</span>
							<img src="https://randomuser.me/api/portraits/men/93.jpg" alt="profile-picture">
						</div>
					</div>
				</header>

				<section class="card">
					<div class="grid-title-modify-article">
				<!-- FAIRE SA EN VERSION GRILLE PARCE QUE SINON ON REFAIT BOOTRAP... ET BOOTSTRAP C NUL :s -->

					<span class="label-card-1">Titre :</span>
					<div class="search-bar">
						<input type="text" placeholder="Rentrer le nouveau titre...">
					</div>
					<span class="label-card-2">Item concerné :</span>
					<div class="search-bar">
						<i class="fas fa-search"></i>
						<input type="text" placeholder="Selectionner un item...">
					</div>
					<div class="filter-container">
						<button class="filter-tag">
							<span>Nom de l'oeuvre</span>
							<i class="fas fa-times"></i>
						</button>
						<button class="filter-tag">
							<span>Oeuvre 2</span>
							<i class="fas fa-times"></i>
						</button>
						<button class="filter-tag filter-tag--genre">
							<span>Oeuvre 2</span>
							<i class="fas fa-times"></i>
						</button>
						<button class="filter-tag">
							<span>Oeuvre 3 avec un nom vachement plus long</span>
							<i class="fas fa-times"></i>
						</button>
					</div>

					<!-- <input type="text" class="search-bar">

					<span class="label-card-1">Titre :</span>
					<span class="label-card-2">Item concerné :</span>

					<button class="btn">Publier</button>
					<button class="btn btn--remove">Supprimer</button>

					<div class="search-bar">
						<i class="fas fa-search"></i>
						<input type="text" placeholder="Selectionner un item...">
					</div>

					<div class="container-wrap">
						<button class="filter-tag">
							<span>Nom de l'oeuvre</span>
							<i class="fas fa-times"></i>
						</button>
						<button class="filter-tag">
							<span>Oeuvre 2</span>
							<i class="fas fa-times"></i>
						</button>
						<button class="filter-tag filter-tag--genre">
							<span>Oeuvre 2</span>
							<i class="fas fa-times"></i>
						</button>
						<button class="filter-tag">
							<span>Oeuvre 3 avec un nom vachement plus long</span>
							<i class="fas fa-times"></i>
						</button>
					</div> -->
				
			</div>
				</section>
				
				<section class="card">section 2</section>
				<section class="card">section 3</section>

			</div>

		</main>

	</div>
		<!-- <div class="main-content"> -->

		<!-- DEBUT : A partir d'ic, c'est à mettre dans les views -->
			<!-- <main class="grid-modify-article"> -->

				<!-- <header class="header">
					<h1>Modifier un article</h1>
					<div class=left-controls>
						<i class="fas fa-bell"></i>
						<div class="user-label">
							<span>Kamal Hennou</span>
							<img src="https://randomuser.me/api/portraits/men/93.jpg" alt="profile-picture">
						</div>
					</div>
				</header>

				<section class="card">section 1</section>
				<section class="card">section 2</section>
				<section class="card">section 3</section> -->

			<!-- </main> -->

		<!-- FIN -->
		
			<!-- afficher la vue -->
			<!-- <php include $this->view ?> -->

		<!-- </div> -->
	

	

	<script type="text/javascript" src="../../dist/main.js"></script>
</body>
</html>