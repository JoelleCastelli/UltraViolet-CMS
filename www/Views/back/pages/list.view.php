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

         <button id="btnPopup" class="btn btnPopup">Ajouter une page</button>

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




 <div id="modal" class="modal">
     <div id="overlay" class="overlay"></div>
     <div class="wrapper">
         <div class="header">
             <h2>
                 Exemple simple de popup
                 <span id="btnClose" class="btnClose">&times;</span>
             </h2>
         </div>
         <div class="content">
             <p>
                 Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas a nulla
                 a massa interdum imperdiet sed nec nibh. Proin porttitor euismod nulla ut
                 interdum. Cras elementum placerat aliquam.
             </p>
         </div>
         <div class="footer">
             <div>
                 <button class="btn">Fermer</button>
                 <button class="btn">Envoyer formulaire</button>
             </div>
         </div>
     </div>
 </div>

 <style>
     .modal {
         display: none;
         z-index: 200;
         position: fixed;
         left: 0px;
         top: 0px;
         width: 100%;
         height: 100%;

     }

     .overlay {


         filter: blur(20%);
         background-color: aquamarine;
         
     }

     .wrapper {
         margin: 10% auto;
         width: 50%;
         background-color: rgb(243, 243, 243);
         padding: 1em;
         box-shadow: 0 15px 20px rgba(0, 0, 0, 0.3);
         border-radius: 5px;
         z-index: 201;
     }

     .btnClose {
         float: right;
         font-size: 16pt;
         cursor: pointer;
         color: rgb(26, 26, 26);
     }
 </style>

 <script>
     var btnPopup = document.getElementById('btnPopup');
     var modal = document.getElementById('modal');
     btnPopup.addEventListener('click', modal);
     $(btnPopup).click(function() {

         console.log(this);
         modal.style.display = 'block';

     })


     function openMoadl() {
         console.log('click');
         console.log(this);
         modal.style.display = 'block';
     }
 </script>