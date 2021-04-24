// OUR CODE HERE :

function toggleSidebar() {
	console.log("Toggle side bar pressed !");
	
	const btn = document.querySelector('#cta-toggle-sidebar');
	const panel = document.querySelector('#sidebar');

	btn.addEventListener("click", () => panel.classList.toggle("wrapped"));
}

// TODO: Voir comment l'exécuter seulement quand la side bar est présente sur la page ?
toggleSidebar(); 














