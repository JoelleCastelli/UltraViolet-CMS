<?php
if(isset($errors)) {
    echo "<div class='error-message-form'>";
    foreach ($errors as $error) {
        if(count($errors) == 1)
            echo $error;
        else
            echo "<li>$error</li>";
    }
    echo "</div>";
}
?>
<div id="tableActions">
    <div class="filtering-status">
        <div class="filtering-btn active" id="poster">Poster</div>
        <div class="filtering-btn" id="vip">Portraits</div>
        <div class="filtering-btn" id="video">Vidéos</div>
        <div class="filtering-btn" id="other">Autres</div>
    </div>
</div>

<div class="card uploadBanner">
    <?php App\Core\FormBuilder::render($form); ?>
    <div id="filesList"></div>
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





