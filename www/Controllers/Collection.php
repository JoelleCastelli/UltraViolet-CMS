<?php

namespace App\Controller;

use App\Core\View;

class Collection {
    
    public function displayCollectionAction() {
        $view = new View("displayCollection", "back");
    }

}