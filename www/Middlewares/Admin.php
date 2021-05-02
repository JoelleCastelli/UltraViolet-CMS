<?php

namespace App\Middleware;

use App\Core\Helpers;
use App\Core\Request;

class Admin {

    // TODO : assigner les erreurs
    public function handle() {
        $user = Request::getUser();
        if (!($user && $user->isLogged() && $user->isAdmin())) {
            Helpers::redirect($_SERVER['HTTP_REFERER']);
        }
    }

}