<div id="tableActions">
    <div class="filtering-status">
        <div class="filtering-btn articleState active" id="published">Publiés</div>
        <div class="filtering-btn articleState" id="scheduled">Planifiés</div>
        <div class="filtering-btn articleState" id="draft">Brouillons</div>
        <div class="filtering-btn articleState" id="removed">Supprimés</div>
    </div>

    <div class="buttons">
        <a href="<?= \App\Core\Helpers::callRoute('article_creation') ?>">
            <button class="btn">Ajouter un article</button>
        </a>
    </div>
</div>


<table id="datatable" class="display">
    <thead>
        <tr>
            <th>Titre</th>
            <th>Slug</th>
            <th>Auteur</th>
            <th>Vues</th>
            <th>Commentaires</th>
            <th>Date creation</th>
            <th>Date publication</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
    </tbody>
</table>


