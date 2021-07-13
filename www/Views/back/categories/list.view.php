<div id="tableActions">
    <div class="filtering-status">
        <div class="filtering-btn" id="visible">Visible</div>
        <div class="filtering-btn" id="hidden">Cachée</div>
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
    <tbody>
        <?php foreach ($categories as $category) { ?>
            <tr>
                <td><?= $category->getName() ?></td>
                <td><?= $category->getPosition() ?></td>
                <td><?= $category->generateActionsMenu() ?></td>
            </tr>
        <?php } ?>
    </tbody>
</table>