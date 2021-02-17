<?php $this->layout("_auth-theme", array("title" => $title)) ?>

<main class="main d-flex justify-content-center w-100"> 
    <div class="container d-flex flex-column">
        <div class="row h-100">
            <div class="col-sm-10 col-md-8 col-lg-6 mx-auto d-table h-100">
                <div class="d-table-cell align-middle">
                    <div class="text-center mt-4">
                        <h1 class="h2"><?= label("new_password") ?></h1>
                        <p class="lead opacity-70">
                            <?= msg("new_password") ?>
                        </p> 
                    </div>
                    <div class="card">
                        <div class="card-body">
                            <div class="m-sm-4">
                                <form action="<?= url("/recuperar/") ?>" method="post" enctype="multipart/form-data" data-reset="true">
                                    <div class="ajax_response"><?= flash_message(); ?></div>
                                    <input type="hidden" name="code" value="<?= $code ?>">
                                    <?= csrf() ?>
                                    <div class="form-group">
                                        <span class="float-right">
                                            <a href="<?= url("/login/") ?>"><?= label("login_back") ?></a>
                                        </span>
                                        <input class="form-control form-control-lg" type="password" name="password"
                                            placeholder="<?= label("password") ?>" required>
                                    </div>
                                    <div class="form-group">
                                        <input class="form-control form-control-lg" type="password"
                                            name="confirm_pass" placeholder="<?= label("confirm_pass") ?>"
                                            required>
                                    </div>
                                    <div class="text-center mt-3">
                                        <button type="submit"
                                            class="btn btn-lg btn-primary"><?= label("save_changes") ?></button>
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
