<div class="container">
		
	<?php include "components/sidebar.php"; ?>

    <main class="main">
        
        <div class="grid-display-collection">

        <header class="header">
            <h1>Ma Collection</h1>
            <div class=left-controls>
                <i class="fas fa-bell"></i>
                <div class="user-label">
                    <span>Kamal Hennou</span>
                    <img src="https://randomuser.me/api/portraits/men/93.jpg" alt="profile-picture">
                </div>
            </div>
        </header>

        <section class="card">
            <div class="grid-display-collection-sorter">

            <h2 class="label-card-1">Trier par :</h2>
            
            <div class="radio-group-aligned">

                <label class="radio-line">
                    <label class="radio-btn-outline">
                        <input type="radio" name="sorter-radio" value="title" checked>
                        <span class="radio-btn-checkmark">
                            <span class=radio-btn-checkmark-inside></span>
                        </span>
                    </label>
                    <span class="radio-label">Titre</span>
                </label>

                <label class="radio-line">
                    <label class="radio-btn-outline">
                        <input type="radio" name="sorter-radio" value="author">
                        <span class="radio-btn-checkmark">
                            <span class=radio-btn-checkmark-inside></span>
                        </span>
                    </label>
                    <span class="radio-label">Auteur</span>
                </label>

                <label class="radio-line">
                    <label class="radio-btn-outline">
                        <input type="radio" name="sorter-radio" value="genre">
                        <span class="radio-btn-checkmark">
                            <span class=radio-btn-checkmark-inside></span>
                        </span>
                    </label>
                    <span class="radio-label">Genre</span>
                </label>

                <label class="radio-line">
                    <label class="radio-btn-outline">
                        <input type="radio" name="sorter-radio" value="type">
                        <span class="radio-btn-checkmark">
                            <span class=radio-btn-checkmark-inside></span>
                        </span>
                    </label>
                    <span class="radio-label">Type</span>
                </label>

            </div>

            <h2 class="label-card-1">Filtre</h2>

            <select class="select" name="type" id="type-select">
                <option value="">Selectionner un type...</option>
                <option value="page1">Type 1</option>
                <option value="page2">Type 2</option>
                <option value="page3">Type 3</option>
            </select>

            <select class="select" name="genre" id="genre-select">
                <option value="">Selectionner un genre...</option>
                <option value="page1">Genre 1</option>
                <option value="page2">Genre 2</option>
                <option value="page3">Genre 3</option>
            </select>

            <div class="search-bar">
                <i class="fas fa-search"></i>
                <input type="text" placeholder="Chercher une oeuvre, un auteur, un type">
            </div>

            <div class="filter-container">
                <button class="filter-tag">
                    <span>Type 1</span>
                    <i class="fas fa-times"></i>
                </button>
                <button class="filter-tag">
                    <span>Type 2 plus long</span>
                    <i class="fas fa-times"></i>
                </button>
                <button class="filter-tag">
                    <span>Type 2 plus long</span>
                    <i class="fas fa-times"></i>
                </button>
                <button class="filter-tag">
                    <span>Type 2 plus long</span>
                    <i class="fas fa-times"></i>
                </button>
                <button class="filter-tag">
                    <span>Type 2 plus long</span>
                    <i class="fas fa-times"></i>
                </button>
                <button class="filter-tag filter-tag--genre">
                    <span>Genre 2 plus long</span>
                    <i class="fas fa-times"></i>
                </button>
                <button class="filter-tag filter-tag--genre">
                    <span>Genre 1</span>
                    <i class="fas fa-times"></i>
                </button>

            </div>

            </div>
            
        </section>


        <section class="card">section 2</section>

        </div>

    </main>

</div>