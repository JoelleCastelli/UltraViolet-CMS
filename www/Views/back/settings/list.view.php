<?php
if(isset($errors)) {
    echo "<div class='error-message-form'>";
    foreach ($errors as $error) {
        if(count($errors) == 1)
            echo "$error";
        else
            echo "<li>$error</li>";
    }
    echo "</div>";
}

App\Core\FormBuilder::render($form);