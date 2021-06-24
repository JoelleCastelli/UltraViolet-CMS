<div id="tableActions">
    <div class="filtering-status">
        <div class="filtering-btn active" id="movie">Films</div>
        <div class="filtering-btn" id="series">Séries</div>
        <div class="filtering-btn" id="season">Saisons</div>
        <div class="filtering-btn" id="episode">Episodes</div>
    </div>

    <div class="buttons">
        <a href="<?= \App\Core\Helpers::callRoute('productions_creation_tmdb') ?>">
            <button class="btn">Ajouter une production</button>
        </a>
    </div>
</div>

<table id="datatable" class="display">
    <thead>
        <tr>
            <?php
                if (isset($columnsTable)) {
                    foreach ($columnsTable as $key => $value) {
                        echo "<th>$value</th>";
                    }
                }
            ?>
        </tr>
    </thead>
    <tbody></tbody>
</table>