<?php

if(isset($errors)) {
    foreach ($errors as $error) {
        echo "<li>$error</li>";
    }
}

App\Core\FormBuilder::render($form); ?>

<div id="production-preview"></div>