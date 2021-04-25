<?php

if (empty($pages)) {
    echo "Déso y'a rien dans la DB";
} else {
    foreach ($pages as $page) {
        echo "<div>";
        echo "<div>Titre : ".$page->getTitle()."</div>";
        echo "<div>Type : ".$page->getSlug()."</div>";
        echo "<div>Titre original : ".$page->getPosition() ?? "inconnue"."</div>";
        echo "<div>Durée : ".$page->getPublictionDate() ?? "y'a rien"."</div>";
        echo "<div>Résumé : ".$page->getState() ?? "y'a rien"."</div>";
        echo "</div>";
        echo "&nbsp;";
    }
}
?>
<a href="ajout-d-une-page">add page</a>

<div class="grid-listing-datatables">

    <section>
        <div class="filtering-status">
            <div class="filtering-btn" id="published">Publiés</div>
            <div class="filtering-btn" id="draft">Brouillons</div>
            <div class="filtering-btn" id="scheduled">Planifiés</div>
            <div class="filtering-btn" id="trash">Corbeille</div>
        </div>

        <button class="btn">Ajouter une page</button>
    </section>

    <section>
        <table id="datatable" class="display">
            <thead>
            <tr>
                <th>Colonne 1</th>
                <th>Colonne 2</th>
                <th>Colonne 3</th>
                <th>Colonne 4</th>
                <th>Colonne 5</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td>Option 1</td>
                <td>Option 2</td>
                <td>Option 3</td>
                <td>Option 4</td>
                <td>Option 5</td>
            </tr>
            <tr>
                <td>Option 6</td>
                <td>Option 7</td>
                <td>Option 8</td>
                <td>Option 9</td>
                <td>Option 10</td>
            </tr>
            <tr>
                <td>Option 6</td>
                <td>Option 7</td>
                <td>Option 8</td>
                <td>Option 9</td>
                <td>Option 10</td>
            </tr>
            <tr>
                <td>Option 6</td>
                <td>Option 7</td>
                <td>Option 8</td>
                <td>Option 9</td>
                <td>Option 10</td>
            </tr>
            <tr>
                <td>Option 6</td>
                <td>Option 7</td>
                <td>Option 8</td>
                <td>Option 9</td>
                <td>Option 10</td>
            </tr>
            <tr>
                <td>Option 6</td>
                <td>Option 7</td>
                <td>Option 8</td>
                <td>Option 9</td>
                <td>Option 10</td>
            </tr>
            <tr>
                <td>Option 6</td>
                <td>Option 7</td>
                <td>Option 8</td>
                <td>Option 9</td>
                <td>Option 10</td>
            </tr>
            </tbody>
        </table>
    </section>

</div>