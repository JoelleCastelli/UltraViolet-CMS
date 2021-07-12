<div class="grid-create-article">

    <?php if (isset($errors)) {
        echo "<div class='error-message-form'>";
        foreach ($errors as $error) {
            if (count($errors) == 1)
                echo "$error";
            else
                echo "<li>$error</li>";
        }
        echo "</div>";
    }
    ?>


    <button class="btn" id="media-cta">POP Media modal</button> 

    <section>

        <?php App\Core\FormBuilder::render($form, true); ?>

    </section>

    <div class="background-modal">

        <div class="clickable-bg"></div>

        <div class="modal-media">
            <h1>Selectionnez l'image de votre article</h1>

            <table id="datatable" class="display">
                <thead>
                <tr>
                    <th>Miniature</th>
                    <th>Nom</th>
                    <th>Date d'ajout</th>
                </tr>
                </thead>
                <tbody></tbody>
            </table>        
        </div>

    </div>




</div>