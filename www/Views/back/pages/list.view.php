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
 <div class="state-switch" onclick="toggleSwitch(this)"></div>

 <a href="ajout-d-une-page">add page</a>

 <div class="grid-listing-datatables">

     <section>
         <div class="filtering-status">
             <div class="filtering-btn" id="published">Publiés</div>
             <div class="filtering-btn" id="draft">Brouillons</div>
             <div class="filtering-btn" id="scheduled">Planifiés</div>
             <div class="filtering-btn" id="trash">Corbeille</div>
         </div>

         <button class="btn">Ajouter une page</button>
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
     <section>
         <div class="state-switch" onclick="toggleSwitch(this)"></div>

     </section>
 </div>