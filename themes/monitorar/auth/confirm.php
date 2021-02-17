<?php $this->layout("_auth-theme", array("title" => $title)) ?>

<main class="main d-flex justify-content-center w-100">
    <div class="container d-flex flex-column">
        <div class="row h-100">
            <div class="col-sm-10 col-md-8 col-lg-6 mx-auto d-table h-100">
                <div class="d-table-cell align-middle">
                    <div class="text-center">
                        <div class="text-center">
                            <img width="200" src="<?= theme("assets/images/logo.png") ?>" class="img-fluid mb-4">
                        </div>
                        <hr>
                        <p class="h1 text-primary"><?= label("little_missing") ?></p>
                        <p class="h2 font-weight-normal mt-3 mb-4"><?= msg("confirm") ?> :)</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>