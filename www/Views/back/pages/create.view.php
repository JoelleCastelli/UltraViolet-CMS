<div class="grid-add-page">

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
    ?> 

    <section class="grid-form-add-page">

        <section class="card">

            <article>
                <article class="container-form">
                    <?php App\Core\FormBuilder::render($form); ?>
                </article><br>
            </article>
        </section>

    </section>
</div>
