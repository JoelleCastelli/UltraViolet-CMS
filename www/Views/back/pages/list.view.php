 <?php

    // if (empty($pages)) {
    //     echo "Déso y'a rien dans la DB";
    // } else {
    //     foreach ($pages as $page) {
    //         echo "<div>";
    //         echo "<div>Titre : " . $page->getTitle() . "</div>";
    //         echo "<div>Type : " . $page->getSlug() . "</div>";
    //         echo "<div>Titre original : " . $page->getPosition() ?? "inconnue" . "</div>";
    //         echo "<div>Durée : " . $page->getPublicationDate() ?? "y'a rien" . "</div>";
    //         echo "<div>Résumé : " . $page->getState() ?? "y'a rien" . "</div>";
    //         echo "</div>";
    //         echo "&nbsp;";
    //     }
    // }
    ?>

 <div class="grid-listing-datatables">

     <section>
         <div class="filtering-status">
             <div class="filtering-btn" id="published">Publiés</div>
             <div class="filtering-btn" id="draft">Brouillons</div>
             <div class="filtering-btn" id="scheduled">Planifiés</div>
             <div class="filtering-btn" id="trash">Corbeille</div>
         </div>

         <button id="btnPopup" class="btn btnPopup" data-toggle="modal" data-target="#add-page-modal">Ajouter une page</button>

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

 <div id="add-page-modal" class="modal modal-hidden">
     <div class="wrapper-modal">
         <div class="header-modal">
             <h2 class="title-modal">
                 Exemple simple de popup
             </h2>
             <span class="btn-close-modal"><i class="fas fa-times"></i></span>
         </div>
         <div class="content-modal">
             <p>
                 Modal 1
             </p>
         </div>
         <div class="footer-modal">
             <div>
                 <button class="btn">Fermer</button>
                 <button class="btn">Envoyer formulaire</button>
             </div>
         </div>
     </div>
 </div>