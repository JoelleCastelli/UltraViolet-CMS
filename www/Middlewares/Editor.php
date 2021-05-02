<?php

namespace App\Middleware;

use App\Core\Helpers;
use App\Core\Request;

class Editor {

    // TODO : assigner les erreurs
    public function handle() {
        $user = Request::getUser();
        if (!($user && $user->isLogged() && $user->isEditor() || $user->isAdmin())) {
            Helpers::redirect($_SERVER['HTTP_REFERER']);
        }
    }



}