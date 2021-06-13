<?php

?>

<div class="grid-listing-datatables">

<section>
        <div class="filtering-status">
            <div class="filtering-btn active" id="user">Utilisateurs</div>
            <div class="filtering-btn" id="moderator">Modérateurs</div>
            <div class="filtering-btn" id="editor">Editeurs</div>
            <div class="filtering-btn" id="admin">Adminstateurs</div>
            
            
        </div>
        <button class="btn">Ajouter une production</button>
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



<div class='bubble-actions'>actions</div>