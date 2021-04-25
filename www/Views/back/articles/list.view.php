<div>
    <div class="articleState" id="published">Publiés</div>
    <div class="articleState" id="draft">Brouillons</div>
    <div class="articleState" id="scheduled">Planifiés</div>
    <div class="articleState" id="trash">Corbeille</div>
</div>


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
        <?php
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
        ?>
    </tbody>
</table>