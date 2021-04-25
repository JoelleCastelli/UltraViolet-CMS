<div>
    <div class="articleState" id="published">Publiés</div>
    <div class="articleState" id="draft">Brouillons</div>
    <div class="articleState" id="scheduled">Planifiés</div>
    <div class="articleState" id="trash">Corbeille</div>
</div>

<a class="btn" href="ajout-d-une-page">add page</a>

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
            if (empty($pages)) {
                foreach ($pages as $page) {
                    echo "<td>Titre : ".$page->getTitle()."</td>";
                    echo "<td>Type : ".$page->getSlug()."</td>";
                    echo "<td>Titre original : ".$page->getPosition() ?? "inconnue"."</td>";
                    echo "<td>Durée : ".$page->getPublictionDate() ?? "y'a rien"."</td>";
                    echo "<td>Résumé : ".$page->getState() ?? "y'a rien"."</td>";
                }
            }
        ?>
    </tbody>
</table>


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
                <th>Titre</th>
                <th>ULR de la page</th>
                <th>Ordre</th>
                <th>Nombre d'articles</th>
                <th>Date</th>
                <th>Visibilité</th>
                <th>Actions</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td>Option 1</td>
                <td>Option 2</td>
                <td>Option 3</td>
                <td>Option 4</td>
                <td>Option 5</td>
                <td>
                    <div class="state-switch" onclick="toggleSwitch(this)"></div>
                </td>
                <td>
                    <div class="buble-actions">
                        <div></div>
                        <div></div>
                        <div></div>
                    </div>
                </td>
            </tr>
            <tr>
                <td>Option 6</td>
                <td>Option 7</td>
                <td>Option 8</td>
                <td>Option 9</td>
                <td>Option 10</td>
                <td>
                    <div class="state-switch switched-on" onclick="toggleSwitch(this)"></div>
                </td>

                <td>
                    <div class="buble-actions">
                        <div></div>
                        <div></div>
                        <div></div>
                    </div>
                </td>

            </tr>
            <tr>
                <td>Option 6</td>
                <td>Option 7</td>
                <td>Option 8</td>
                <td>Option 9</td>
                <td>Option 10</td>
                <td>
                    <div class="state-switch" onclick="toggleSwitch(this)"></div>
                </td>
                <td>
                    <div class="buble-actions">
                        <div></div>
                        <div></div>
                        <div></div>
                    </div>
                </td>

            </tr>
            <tr>
                <td>Option 6</td>
                <td>Option 7</td>
                <td>Option 8</td>
                <td>Option 9</td>
                <td>Option 10</td>
                <td>
                    <div class="state-switch" onclick="toggleSwitch(this)"></div>
                </td>
                <td>
                    <div class="buble-actions">
                        <div></div>
                        <div></div>
                        <div></div>
                    </div>
                </td>

            </tr>
            <tr>
                <td>Option 6</td>
                <td>Option 7</td>
                <td>Option 8</td>
                <td>Option 9</td>
                <td>Option 10</td>
                <td>
                    <div class="state-switch" onclick="toggleSwitch(this)"></div>
                </td>
                <td>
                    <div class="buble-actions">
                        <div></div>
                        <div></div>
                        <div></div>
                    </div>
                </td>

            </tr>
            <tr>
                <td>Option 6</td>
                <td>Option 7</td>
                <td>Option 8</td>
                <td>Option 9</td>
                <td>Option 10</td>
                <td>
                    <div class="state-switch" onclick="toggleSwitch(this)"></div>
                </td>
                <td>
                    <div class="buble-actions">
                        <div></div>
                        <div></div>
                        <div></div>
                    </div>
                </td>

            </tr>
            <tr>
                <td>Option 6</td>
                <td>Option 7</td>
                <td>Option 8</td>
                <td>Option 9</td>
                <td>Option 10</td>
                <td>
                    <div class="state-switch" onclick="toggleSwitch(this)"></div>
                </td>
                <td>
                    <div class="buble-actions">
                        <div></div>
                        <div></div>
                        <div></div>
                    </div>
                </td>

            </tr>
            </tbody>
        </table>
    </section>

</div>