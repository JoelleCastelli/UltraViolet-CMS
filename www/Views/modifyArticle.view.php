<div class="container">
		
		<?php include "components/sidebar.php"; ?>

		<main class="main">
			
			<div class="grid-modify-article">

				<?php include "components/header.php"; ?>

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