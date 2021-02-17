<?php $this->layout("_auth-theme", array("title" => $title)) ?>

<main class="main d-flex justify-content-center w-100">
    <div class="container d-flex flex-column">
        <div class="row h-100">
            <div class="col-sm-10 col-md-8 col-lg-6 mx-auto d-table h-100">
                <div class="d-table-cell align-middle">
                    <div class="text-center mt-4">
                        <h1 class="h2"><?= label('reset_password') ?></h1>
                        <p class="lead opacity-70">
                            <?= msg('reset_password') ?>
                        </p>
                    </div>
                    <div class="card">
                        <div class="card-body">
                            <div class="m-sm-4">
                                <form action="<?= url("/forget/") ?>" method="post" enctype="multipart/form-data" data-reset="true">
                                    <div class="ajax_response"><?= flash_message(); ?></div>
                                    <?= csrf() ?>
                                    <div class="form-group">
                                        <span class="float-right">
                                            <a href="<?= url("/login/") ?>"><?= label("login_back") ?></a>
                                        </span>
                                        <input class="form-control form-control-lg" type="email" id="email" name="email" placeholder="<?= label("email") ?>" required>
                                    </div>
                                    <div class="text-center mt-3">
                                        <button type="submit" class="btn btn-lg btn-primary"><?= label('reset_password') ?></button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</main>