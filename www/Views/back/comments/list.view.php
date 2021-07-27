<div id="tableActions">
    <div class="filtering-status">
        <div class="filtering-btn active" id="visible">Visibles</div>
        <div class="filtering-btn" id="invisible">Supprimés</div>
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