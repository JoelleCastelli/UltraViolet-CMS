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
							<button class="filter-tag">
								<span>Oeuvre 3 avec un nom vachement plus long</span>
								<i class="fas fa-times"></i>
							</button>
							<button class="filter-tag">
								<span>Oeuvre 4 moyenne</span>
								<i class="fas fa-times"></i>
							</button>
						</div>
				
					</div>
				</section>
				
				<section class="card">
					<div class="grid-publication-modify-article">

						<span class="label-card-1">Publier :</span>
						<div class="radio-group">

							<label class="radio-line">
								<label class="radio-btn-outline">
									<input type="radio" name="publication-radio" value="now" checked>
									<span class="radio-btn-checkmark">
										<span class=radio-btn-checkmark-inside></span>
									</span>
								</label>
								<span class="radio-label">Maintenant</span>
							</label>
							
							<label class="radio-line">
								<label class="radio-btn-outline">
									<input type="radio" name="publication-radio" value="later" checked>
									<span class="radio-btn-checkmark">
										<span class=radio-btn-checkmark-inside></span>
									</span>
								</label>
								<span class="radio-label">Le</span>
								<div class="search-bar">
									<i class="far fa-calendar-alt"></i>
									<input type="date" placeholder="Selectionner un item...">
								</div>
								<span class="radio-label">à</span>
								<div class="search-bar">
									<i class="far fa-clock"></i>
									<input type="time" placeholder="Selectionner un item...">
								</div>
								
							</label>
							



						</div>
						

						<span class="label-card-2">Ajouter des pages</span>
						<select class="select" name="page" id="page-select">
							<option value="">Selectionner une page...</option>
							<option value="page1">Page 1</option>
							<option value="page2">Page 2</option>
							<option value="page3">Page 3</option>
						</select>
						<div class="filter-container">
							<button class="filter-tag">
								<span>Page 1 avec un nom un peu plus long</span>
								<i class="fas fa-times"></i>
							</button>
							<button class="filter-tag">
								<span>Page 2 avec un nom plus court</span>
								<i class="fas fa-times"></i>
							</button>
						</div>
						<button class="btn">Publier</button>
						<button class="btn">Prévisualiser</button>

					</div>
				</section>


				<section class="card">
					<textarea name="tinymce-text-area" id="tinymce-text-area"></textarea>
				</section>

				<section>
					<button class="btn btn--remove">Supprimer</button>
					<button class="btn">Sauvegarder</button>
				</section>

			</div>

		</main>

	</div>
	<script type="text/javascript" src="../../dist/main.js"></script>
	<script src="https://cdn.tiny.cloud/1/itne6ytngfhi89x71prh233w7ahp2mgfmc8vwnjxhvue2m6h/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>
	<script>
    tinymce.init({
      selector: '#tinymce-text-area'
    });
  </script>
</body>
</html>