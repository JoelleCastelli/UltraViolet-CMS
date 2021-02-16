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


        <section class="card">
            <div class="collection-container">
                <article class="collection-item">
                    <img src="https://m.media-amazon.com/images/I/51cw3aOJmOL._AC_.jpg" alt="affiche-de-film">
                    <div>
                        <div class="tag-group">
                            <!-- TODO : Faire les composants tag indépendemment du ce composant -->
                            <label class="tag tag--type">Film</label>
                            <label class="tag">Aventure</label>
                            <label class="tag">Fantastique</label>
                        </div>
                        <h1 class="label-item-collection">Titre de l'affiche</h1>
                        <label class=label-date>JJ / MM / AAAA</label>
                    </div>
                </article>

                <article class="collection-item">
                    <img src="https://lh3.googleusercontent.com/proxy/uz7lD3dfD_qj6l37c8bm8OEC00id79I6ZiBzZkUeeZ_mgnnB9Xnch-jtdGIHkBOm2LjAnrtdIIHu47V16UPtwBiHpss7EQPz8li_-ZnH3nY1i70uO7Q3IgRFOCOyL43S0yPpz2r8-wMTQzs" alt="affiche-de-film">
                    <div>
                        <div class="tag-group">
                            <!-- TODO : Faire les composants tag indépendemment du ce composant -->
                            <label class="tag tag--type">Film</label>
                            <label class="tag">Aventure</label>
                            <label class="tag">Fantastique</label>
                        </div>
                        <h1 class="label-item-collection">Titre de l'affiche</h1>
                        <label class=label-date>JJ / MM / AAAA</label>
                    </div>
                </article>

                <article class="collection-item">
                    <img src="https://images-na.ssl-images-amazon.com/images/I/51K4w8DY5cL._AC_.jpg" alt="affiche-de-film">
                    <div>
                        <div class="tag-group">
                            <!-- TODO : Faire les composants tag indépendemment du ce composant -->
                            <label class="tag tag--type">Film</label>
                            <label class="tag">Aventure</label>
                            <label class="tag">Fantastique</label>
                        </div>
                        <h1 class="label-item-collection">Titre de l'affiche</h1>
                        <label class=label-date>JJ / MM / AAAA</label>
                    </div>
                </article>

                <article class="collection-item">
                    <img src="https://www.themoviedb.org/t/p/w500/uU32j2eWvoHIIVyn7QfBg5UaKue.jpg" alt="affiche-de-film">
                    <div>
                        <div class="tag-group">
                            <!-- TODO : Faire les composants tag indépendemment du ce composant -->
                            <label class="tag tag--type">Film</label>
                            <label class="tag">Aventure</label>
                            <label class="tag">Fantastique</label>
                        </div>
                        <h1 class="label-item-collection">Titre de l'affiche</h1>
                        <label class=label-date>JJ / MM / AAAA</label>
                    </div>
                </article>

                <article class="collection-item">
                    <img src="https://i.pinimg.com/originals/8f/9b/d9/8f9bd9178ac91804f876d9f967d7bf8c.jpg" alt="affiche-de-film">
                    <div>
                        <div class="tag-group">
                            <!-- TODO : Faire les composants tag indépendemment du ce composant -->
                            <label class="tag tag--type">Film</label>
                            <label class="tag">Aventure</label>
                            <label class="tag">Fantastique</label>
                        </div>
                        <h1 class="label-item-collection">Titre de l'affiche</h1>
                        <label class=label-date>JJ / MM / AAAA</label>
                    </div>
                </article>
            </div>
            <nav class="item-navigation">
                <span>1 - 8 of 1276</span>
                <button>
                    <i class="fas fa-chevron-left"></i>
                </button>
                <button>
                    <i class="fas fa-chevron-right"></i>
                </button>
           
            </nav>
            

        </section>

        </div>

    </main>

</div>