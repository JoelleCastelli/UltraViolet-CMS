$(document).ready(function() {
    $('#delete-account').on('click', function(e) {
        if(confirm('Êtes-vous sûr.e de vouloir supprimer votre compte ? Aucune possibilité de retour en arrière !'))
            return;
        else 
            e.preventDefault();
    });
});
