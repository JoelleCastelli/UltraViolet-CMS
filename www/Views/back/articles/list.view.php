<div class="grid-listing-datatables">

    <section>
        <div class="filtering-status">
            <div class="filtering-btn articleState" id="published">Publiés</div>
            <div class="filtering-btn articleState" id="draft">Brouillons</div>
            <div class="filtering-btn articleState" id="scheduled">Planifiés</div>
            <div class="filtering-btn articleState" id="hidden">Cachés</div>
            <div class="filtering-btn articleState" id="trash">Corbeille</div>
        </div>

        <a class="btn" href="<?= \App\Core\Helpers::callRoute('article_creation') ?>" >Ajouter un article</a>

    </section>

    <section>
        <table id="datatable" class="display">
            <thead>
                <tr>
                    <th>Titre</th>
                    <th>Auteur</th>
                    <th>Vues</th>
                    <th>Commentaires</th>
                    <th>Date</th>
                    <th>Publication</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </section>

</div>