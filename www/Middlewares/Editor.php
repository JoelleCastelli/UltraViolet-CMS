<?php

namespace App\Middleware;

use App\Core\Helpers;
use App\Core\Request;

class Editor {

    public function handle() {
        $user = Request::getUser();
        if (!($user && $user->isLogged() && $user->isEditor() || $user->isAdmin())) {
            Helpers::setFlashMessage('error', "Accès interdit : vous n'avez pas les droits d'édition");
            Helpers::redirect(Helpers::callRoute('admin'));
        }
    }

}