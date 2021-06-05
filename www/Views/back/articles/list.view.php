<div class="grid-listing-datatables">

    <section>
        <div class="filtering-status">
            <div class="filtering-btn articleState" id="published">Publiés</div>
            <div class="filtering-btn articleState" id="draft">Brouillons</div>
            <div class="filtering-btn articleState" id="scheduled">Planifiés</div>
            <div class="filtering-btn articleState" id="trash">Corbeille</div>
        </div>

        <a class="btn" href="creer-un-article" >Ajouter un article</a>

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
                <!-- <?php
                    if (!empty($articles)) {
                        foreach ($articles as $article) {
                            echo "<td>$article->title</td>";
                            echo "<td>$article->author</td>";
                            echo "<td>$article->totalViews</td>";
                            echo "<td>$article->totalComments</td>";
                            echo "<td>$article->publicationDate</td>";
                            echo "<td>$article->publicationToggle</td>";
                            echo "<td>$article->actions</td>";
                        }
                    }
                ?> -->
            </tbody>
        </table>
    </section>

</div>