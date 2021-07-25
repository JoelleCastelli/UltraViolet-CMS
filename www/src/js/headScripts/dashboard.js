
// Delete article
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

// Delete Comment
$(".commentCard .delete").click(function (event) {
    event.preventDefault();
    if (confirm("Êtes-vous sûr de vouloir supprimer ce commentaire ?")) {
        const id = this.id.substring(this.id.lastIndexOf("-") + 1);
        $.ajax({
        type: "POST",
        url: callRoute("comments_delete"),
        data: { id: id },
        dataType: "json",
        success: function (response) {
            let divId = '#comment-' + id;
            $(divId).remove();
        },
        error: function (response) {
            alert("Erreur dans la suppression du commentaire ID: " + commentId);
        },
        });
    }
});