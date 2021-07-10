<div id="tableActions">
    <div class="filtering-status">
        <div class="filtering-btn active" id="user">Utilisateurs</div>
        <div class="filtering-btn" id="moderator">Modérateurs</div>
        <div class="filtering-btn" id="editor">Rédacteurs</div>
        <div class="filtering-btn" id="admin">Administrateurs</div>
<<<<<<< HEAD
=======
        <div class="filter-delete" id="admin">Supprimer</div>
>>>>>>> Test -> front for deleted user
    </div>
</div>

<table id="datatable" class="display">
    <thead>
        <tr>
            <?php
            use App\Core\Helpers;
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