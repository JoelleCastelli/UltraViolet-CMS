<?php

namespace App\Middleware;

use App\Core\Request;

class Moderator {

    // TODO : assigner les erreurs + vérifier la connexion
    public function handle() {
        $user = Request::getUser();
        if (!($user && $user->isLogged() && $user->isModerator() || $user->isEditor() || $user->isAdmin())) {
            die("Il faut avoir les droits moderator");
        }
    }

}