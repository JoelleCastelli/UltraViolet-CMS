<!DOCTYPE html>
<html lang="fr">
<head>
	<meta charset="UTF-8">
	<title>Template de front</title>
	<meta name="description" content="description de la page de front">
	<link rel="stylesheet" href="../../dist/main.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.2/css/all.min.css" integrity="sha512-HK5fgLBL+xu6dm/Ii3z4xhlSUyZgTT9tuc/hSrtw6uzJOvgRr2a9jyxxT1ely+B+xFAmJKVSTbpM/CuL7qxO8w==" crossorigin="anonymous" />
	<style>

		.grid-front {
			display: grid;
			height: 100vh;
			grid-template-columns: minmax(15rem, auto) 1fr; /* auto dos not work here ? */
			grid-template-rows: 1fr;
			grid-column-gap: 2rem;
			grid-template-areas: "sidebar main-content";
		}

		#sidebar {
			grid-area: sidebar;
		}

		#main-content {
			grid-area: main-content;
		}

	</style>
</head>
<body>

	<div class="grid-front">
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
		<div id="main-content">
			<!-- afficher la vue -->
			<?php include $this->view ?>
		</div>
	</div>

	

	<script type="text/javascript" src="../../dist/main.js"></script>
</body>
</html>