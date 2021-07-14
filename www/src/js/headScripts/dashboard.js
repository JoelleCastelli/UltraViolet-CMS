$('.articleCard .delete').click(function() {
    if (confirm("Êtes-vous sûr.e de vouloir supprimer cet article ?")) {
        const id = this.id.substring(this.id.lastIndexOf("-") + 1);
        $.ajax({
            type: "POST",
            url: callRoute("article_delete"),
            data: { id: id },
            success: function () {
                let divId = '#article-' + id;
                $(divId).remove();
            },
            error: function () {
                alert("Erreur : impossible de supprimer l'article avec l'ID suivant :  " + id)
            },
        });
    }
});