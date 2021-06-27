<div id="tableActions">
    <div class="filtering-status">
        <div class="filtering-btn active" id="poster">Poster</div>
        <div class="filtering-btn" id="vip">Portraits</div>
        <div class="filtering-btn" id="video">Vidéos</div>
        <div class="filtering-btn" id="other">Autres</div>
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


<section class="card">
    <article class="upload-medias-container">
        <?php App\Core\FormBuilder::render($form, true); ?>
    </article>
</section>





