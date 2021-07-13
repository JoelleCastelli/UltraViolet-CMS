<div id="tableActions">
    <div class="buttons">
        <a href="<?= \App\Core\Helpers::callRoute('category_creation') ?>">
            <button class="btn">Ajouter une catégorie</button>
        </a>
    </div>
</div>

<p class="error-message-form">Une position 0 équivaut à ne pas afficher la catégorie sur le site !</p>

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