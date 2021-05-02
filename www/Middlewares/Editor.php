<?php

namespace App\Middleware;

use App\Core\Request;

class Editor {

    // TODO : assigner les erreurs + vérifier la connexion
    public function handle() {
        $user = Request::getUser();
        if (!($user && $user->isLogged() && $user->isEditor() || $user->isAdmin())) {
            die("Il faut avoir les droits editor");
        }
    }



}