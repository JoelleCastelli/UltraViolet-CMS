<?php

?>

<div class="grid-listing-datatables">


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