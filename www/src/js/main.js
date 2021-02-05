$(document).ready(function(){
	// si des éléments avec la class .slider existent
	if($('.slider').length){
		$('.slider').each(function(){
			sliderInit($(this));
		})
	}
})

function sliderInit(slider){
	// Créer un container de slides
	var container = $('<div></div>');
	container.addClass('slides-container');

	// Mettre le contenu du slider dans notre container de slides
	var sliderContent = slider.html();
	container.html(sliderContent);

	// Ajouter la classe slide à toutes les images
	container.children('img').addClass('slide');

	// Mettre le container de slides dans slider
	slider.html(container);

	// Ajouter une nav au slider
	var nav = $('<nav></nav>');
	nav.append('<button class="prev">prev</button>');
	nav.append('<button class="next">next</button>');
	slider.append(nav);

	slider.attr('data-currentSlide', '0');

	//Ajouter un écouteur de clic sur les boutons prev et next
	slider.find('.prev').click(function(){
		prev(slider);
	})
	slider.find('.next').click(function(){
		next(slider);
	})
// Ces boutons lancent les fonctions prev(slider) et next(slider)
// Ces fonctions modifient 'attribut data-currentSlide du slider

}

function next(slider){
	var currentSlide = Number(slider.attr('data-currentSlide'));
	slider.attr('data-currentSlide', currentSlide + 1);
	slide(slider);
}

function prev(slider){
	var currentSlide = Number(slider.attr('data-currentSlide'));
	slider.attr('data-currentSlide', currentSlide - 1);
	slide(slider);
}


function slide(slider){
	var currentSlide = Number(slider.attr('data-currentSlide'));
	slider.children('.slides-container').css('left', currentSlide * slider.width() * -1);


	if(currentSlide == -1){
		// Dupliquer la dernière image
		var duplicate = slider.find('img:last').clone();
		duplicate.css({
			'position' : 'absolute',
			'top': 0,
			'left': 0,
			'transform': 'translateX(-100%)'
		})
		slider.find('.slides-container').prepend(duplicate);

		slider.find('.slides-container').on('transitionend', function(){
			$(this).off('transitionend');
			var lastImageIndex = slider.find('img').length - 2;
			slider.attr('data-currentSlide', lastImageIndex);
			$(this).css('transition', 'none');
			slide(slider);
			setTimeout(function(){
				slider.find('img:first').remove();
				slider.find('.slides-container').css('transition', 'left 1s');
			},10);
			
		});

	}

}

const sayHello = () => console.log("WEBPACK FONCTIONNE");
sayHello();















