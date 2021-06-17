<div class="grid-listing-datatables">

    <section>
        <div class="filtering-status">
            <div class="filtering-btn" id="published">Publiés</div>
            <div class="filtering-btn" id="scheduled">Planifiés</div>
            <div class="filtering-btn" id="draft">Brouillons</div>
            <div class="filtering-btn" id="deleted">Corbeille</div>
        </div>

        <button id="btnPopup" class="btn btnPopup" data-toggle="modal" data-target="#add-page-modal">Ajouter une
            page</button>

    </section>

    <section>
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
            <tbody>
            </tbody>
        </table>
    </section>
</div>

<!-- MODAL -->
<div id="add-page-modal" class="modal modal-hidden">
    <div class="wrapper-modal">
        <div class="header-modal">
            <h2 class="title-modal">
                Ajouter une page
            </h2>
            <span class="btn-close-modal"><i class="fas fa-times"></i></span>
        </div>
        <div class="content-modal">
            <?php App\Core\FormBuilder::render($formCreatePage);  ?>
        </div>
        <div class="footer-modal">

            <div class="container-message">

            </div>

        </div>
    </div>
</div>