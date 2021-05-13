<div class="grid-list-medias">

    <section class="card">
        <div class="grid-filter-list-medias">

            <!-- FILTER -->
            <article class="grid-filter-container-list-medias">
                <span class="label-card-1">Filtre: </span>

            </article>

            <!-- SEARCH BAR -->
            <article class="grid-search-bar-container-list-medias">
                <label class="radio-line">
                    <label class="radio-btn-outline">
                        <input class="media-type" type="checkbox" name="publication-radio" checked>
                        <span class="radio-btn-checkmark">
							<span class=radio-btn-checkmark-inside></span>
						</span>
                    </label>
                    <span class="radio-label">Image</span>
                </label>

                <label class="radio-line">
                    <label class="radio-btn-outline">
                        <input class="media-type" type="checkbox" name="publication-radio" checked>
                        <span class="radio-btn-checkmark">
							<span class=radio-btn-checkmark-inside></span>
						</span>
                    </label>
                    <span class="radio-label">Vidéo</span>
                </label>

            </article>

        </div>
    </section>

    <section class="card">

        <!-- Upload form-->
        <article class="upload-medias-container">
            <?php App\Core\FormBuilder::render($form, true); ?>

        </article>
    </section>

    <section class="card">
        LIST
    </section>
</div>





