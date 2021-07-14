<div id="tableActions">
    <div class="filtering-status">
        <div class="filtering-btn" id="visible">Visibles</div>
        <div class="filtering-btn" id="hidden">Cachées</div>
    </div>

    <div class="buttons">
        <a href="<?= \App\Core\Helpers::callRoute('category_creation') ?>">
            <button class="btn">Ajouter une catégorie</button>
        </a>
    </div>

</div>

<table id="datatable" class="display">
    <thead>
        <tr>
            <?php if (isset($columnsTable)) {
                foreach ($columnsTable as $key => $value) {
                    echo "<th>$value</th>";
                }
            } ?>
        </tr>
    </thead>
    <tbody></tbody>
</table>