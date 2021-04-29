<a href="/nouvelle-production">Ajouter une production manuellement</a>
<a href="/nouvelle-production-tmdb">Ajouter une production via TMDB</a>

<div class="grid-listing-datatables">

    <section>
        <div class="filtering-status">
            <div class="filtering-btn productionType" id="movie">Films</div>
            <div class="filtering-btn productionType" id="series">Séries</div>
            <div class="filtering-btn productionType" id="season">Saisons</div>
            <div class="filtering-btn productionType" id="episode">Episodes</div>
        </div>
        <button class="btn">Ajouter une production</button>
    </section>

    <section>
        <table id="datatable" class="display">
            <thead>
            <tr>
                <th>Titre</th>
                <th>Titre original</th>
                <th>Date de sortie</th>
                <th>Durée</th>
                <th>Résumé</th>
                <th>Actions</th>
            </tr>
            </thead>
            <tbody>
            <?php
            if (!empty($productions)) {

                foreach ($productions as $production) {
                    echo "<td>$production->getTitle()</td>";
                    echo "<td>$production->getOriginalTitle()</td>";
                    echo "<td>$production->getreleaseDate()</td>";
                    echo "<td>$production->getRuntime()</td>";
                    echo "<td>$production->getOverview()</td>";
                }
            }
            ?>
            </tbody>
        </table>
    </section>

</div>
