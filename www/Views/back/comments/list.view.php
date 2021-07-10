<div class="grid-list-comments">
    <section class="card">
        <div class="grid-filter-list-comments">

            <!-- FILTER -->
            <article class="grid-filter-container-list-comments">

                <span class="label-card-1">Par article(s) : </span>
                <div class="search-bar">
                    <i class="fas fa-search"></i>
                    <input type="text" placeholder="Taper un article...">
                </div>

                <span class="label-card-1">Depuis le : </span>
                <div class="search-bar">
                    <i class="fas fa-search"></i>
                    <input type="text" placeholder="00/00/0000">
                </div>

                <span class="label-card-1">Jusqu'au : </span>
                <div class="search-bar">
                    <i class="fas fa-search"></i>
                    <input type="text" placeholder="00/00/0000">
                </div>

            </article>

            <!-- TAGS -->
            <article class="grid-tag-container-list-comments">
                <button class="filter-tag">
                    <span>Nom de l'oeuvre</span>
                    <i class="fas fa-times"></i>
                </button>
                <button class="filter-tag">
                    <span>Oeuvre 2</span>
                    <i class="fas fa-times"></i>
                </button>
                <button class="filter-tag">
                    <span>Oeuvre 3 avec un nom vachement plus long</span>
                    <i class="fas fa-times"></i>
                </button>
            </article>
        </div>
    </section>
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
        <?php foreach($comments as $comment) { ?>
            <tr>
                <td><?= $comment->getPerson()->getFullName() ?></td>
                <td><?= $comment->getCreatedAt() ?></td>
                <td><?= $comment->getArticle()->getTitle() ?></td>
                <td><?= $comment->getContent() ?></td>
                <td><?= $comment->getVisible() ?></td>
                <td><?= $comment->generateActionsMenu() ?></td>
            </tr>
        <?php } ?>
    </tbody>
</table>