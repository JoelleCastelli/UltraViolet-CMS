<?php

namespace App\Middleware;

use App\Core\Helpers;
use App\Core\Request;

class Moderator {

    public function handle() {
        $user = Request::getUser();
        if (!($user && $user->isLogged() && $user->isModerator() || $user->isEditor() || $user->isAdmin())) {
            Helpers::setFlashMessage('error', "Accès interdit : vous n'avez pas les droits de modération");
            Helpers::redirect(Helpers::callRoute('admin'));
        }
    }

}