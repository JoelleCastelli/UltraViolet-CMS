<?php

namespace App\Middleware;

use App\Core\Helpers;
use App\Core\Request;

class Admin {

    public function handle() {
        $user = Request::getUser();
        if (!($user && $user->isLogged() && $user->isAdmin())) {
            Helpers::setFlashMessage('errors', "Accès interdit : vous n'avez pas les droits d'administration");
            Helpers::redirect($_SERVER['HTTP_REFERER']);
        }
    }

}