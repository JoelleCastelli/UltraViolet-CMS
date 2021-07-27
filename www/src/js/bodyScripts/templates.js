$(document).ready(function () {
  $("#restore").click(function() {
      if (confirm("Êtes-vous sûr.e de vouloir restaurer les valeurs par défaut ?")) {
        $.ajax({
          type: "POST",
          url: callRoute("restore_templates"),
          success: function () {
            document.location.reload();
          },
          error: function () {
            console.log("Erreur dans la restauration des valeurs par défaut");
          },
        });
      }
    });
});
