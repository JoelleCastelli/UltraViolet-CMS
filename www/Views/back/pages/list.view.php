<?php use App\Core\Helpers; ?>

<div id="response"></div>

<div id="tableActions">
    <div class="filtering-status">
        <div class="filtering-btn" id="published">Publiées</div>
        <div class="filtering-btn" id="scheduled">Planifiées</div>
        <div class="filtering-btn" id="draft">Brouillons</div>
        <div class="filtering-btn" id="deleted">Corbeille</div>
    </div>

   <div class="buttons">
        <a href="<?= Helpers::callRoute('page_creation') ?>">
            <button id="btnPopup" class="btn btnPopup">
                    Ajouter une page
            </button>
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