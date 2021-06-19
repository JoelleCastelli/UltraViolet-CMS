<div class="grid-listing-datatables">

    <section>
        <div class="filtering-status">
            <div class="filtering-btn active" id="movie">Films</div>
            <div class="filtering-btn" id="series">Séries</div>
            <div class="filtering-btn" id="season">Saisons</div>
            <div class="filtering-btn" id="episode">Episodes</div>
        </div>

        <a href="<?= \App\Core\Helpers::callRoute('productions_creation_tmdb') ?>">
            <button class="btn">Nouvelle production</button>
        </a>
    </section>

    <section>
        <table id="datatable" class="display">
            <thead>
                <tr>
                    <?php
                        foreach ($columnsTable as $key => $value) {
                            echo "<th>$value</th>";
                        }
                    ?>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </section>

</div>
