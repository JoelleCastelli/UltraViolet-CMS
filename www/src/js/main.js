$(document).ready(function(){
	// Vérifier qu'il y a des ékéléments avec la classe .slider
	if($('.slider').length){
		$('.slider').each(function(){
			sliderInit($(this));
		})
	}
})


function sliderInit(slider){
	var container = $('<div></div>'); // div vide
	container.addClass('slides-container'); 
	container.html(slider.html()); 
	container.children('img').addClass('slide');

	slider.html(container);

	// Ajouter la navigation
	var nav = $('<nav></nav>');
	nav.append('<button class="prev"></button>');
	nav.append('<button class="next"></button>');
	slider.append(nav);

	// ajouter un attribut data-currentSlide au slider
	slider.attr('data-currentSlide', 0);
	slider.find('.prev').click(function(){
		prev(slider);
	})
	slider.find('.next').click(function(){
		next(slider);
	})
	startAutoPlay(slider);
}

function next(slider){
	var currentSlide = slider.attr('data-currentSlide');
	var nextSlide = Number(currentSlide) + 1;
	slider.attr('data-currentSlide', nextSlide);
	slide(slider);
}

function prev(slider){
	var currentSlide = slider.attr('data-currentSlide');
	var prevSlide = Number(currentSlide) - 1;
	slider.attr('data-currentSlide', prevSlide);
	slide(slider);
}

function slide(slider){

	var currentSlide = slider.attr('data-currentSlide');
	var container = slider.children('.slides-container');
	var left = slider.width() * currentSlide * -1;
	container.css('left', left);

	disableNav(slider);
	container.on('transitionend',function(){
		container.off('transitionend');
		enableNav(slider);
	})

	if(currentSlide == -1){
		//Cloner la dernière image pour la mettre avant la première
		var clone = container.find('.slide:last').clone();
		clone.css({
			'position':'absolute',
			'top': 0,
			'left': 0,
			'transform': 'translateX(-100%)'
		})
		container.prepend(clone); // Ajouter le clone au début du container de slides

		// Ecouter la fin de la transition
		container.on('transitionend', function(){

			container.off('transitionend'); // Supprimer l'écouteur car cette fonction ne doit être lancée qu'une fois

			// Enlever la transition du container
			container.css('transition', 'none');

			// Ramener le slider sur la dernière image
			var lastImageIndex = container.find('.slide').length - 1 - 1; // On retire 1 pour avoir l'index et encore un pour ne pas compter le clone présent dans le container
			slider.attr('data-currentSlide', lastImageIndex);
			slide(slider);


			setTimeout(function(){

				enableNav(slider);

				// Supprimer le clone situé au début du container
				container.find('.slide:first').remove();

				// Rétablir la transition du container
				container.css('transition', 'left 1s');
			}, 10);

		})

	}

	if(currentSlide == container.find('.slide').length){

		//Cloner la première image pour la mettre après la dernière
		var clone = container.find('.slide:first').clone();
		container.append(clone); // Ajouter le clone à la fin du container de slides

		// Ecouter la fin de la transition
		container.on('transitionend', function(){

			container.off('transitionend'); // Supprimer l'écouteur car cette fonction ne doit être lancée qu'une fois

			// Enlever la transition du container
			container.css('transition', 'none');

			// Ramener le slider sur la première image
			slider.attr('data-currentSlide', 0);
			slide(slider);


			setTimeout(function(){
				enableNav(slider);

				// Supprimer le clone situé à la fin du container
				container.find('.slide:last').remove();

				// Rétablir la transition du container
				container.css('transition', 'left 1s');
			}, 10);

		})
	}

	stopAutoPlay(slider); // pas vraiment nécessaire
	startAutoPlay(slider);
}


function disableNav(slider){
	slider.find('nav').css({
		'pointer-events':'none',
		'opacity':'0.5'
	})
}

function enableNav(slider){
	slider.find('nav').css({
		'pointer-events':'auto',
		'opacity':'1'
	})
}


var interval;

function startAutoPlay(slider){
	interval = setInterval(function(){
		next(slider)
	}, 4000);
}

function stopAutoPlay(slider){
	clearInterval(interval)
}




// OUR CODE HERE :


function toggleSidebar() {
	const btn = document.querySelector('#cta-toggle-sidebar');
	const panel = document.querySelector('#sidebar');

	btn.addEventListener("click", () => panel.classList.toggle("wrapped"));
}

// TODO: Voir comment l'exécuter seulement quand la side bar est présente sur la page ?
toggleSidebar(); 














