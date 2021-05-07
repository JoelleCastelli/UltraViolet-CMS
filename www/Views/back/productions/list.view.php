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

                <?php foreach ($columnsTable as $key => $value): ?>
                    <th><?= $value ?></th>
                <?php endforeach; ?>

            </tr>
        </thead>
            <!-- <tbody>
            <?php
/*            if (!empty($productions)) {

               /* foreach ($productions as $production) {
                    echo "<td>" . $production->getType() . "</td>";
                    echo "<td>" . $production->getOriginalTitle() ?? $production->getTitle() . "</td>";
                    echo "<td>" . ($production->getReleaseDate() ?? '') . "</td>";
                    echo "<td>" . ($production->getRuntime() ?? '') . "</td>";
                    echo "<td>" . ($production->getOverview() ?? '') . "</td>";
                }
            }
            */?>
            </tbody>-->
        </table>
    </section>

</div>
