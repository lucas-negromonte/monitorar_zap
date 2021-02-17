<?php $this->layout("_auth-theme", array("title" => $title)) ?>

<main class="main d-flex justify-content-center w-100 container-login">
    <div class="container d-flex flex-column">
        <div class="row h-100">
            <div class="col-sm-10 col-md-8 col-lg-6 mx-auto d-table h-100">
                <div class="d-table-cell align-middle">
                    <div class="card">
                        <div class="card-body">
                            <div class="m-sm-4">
                                <div class="text-center">
                                    <img src="<?= theme("assets/images/logo.png") ?>" width="50" class="img-fluid mb-5">
                                </div>
                                <form action="<?= url("/login/") ?>" method="post" enctype="multipart/form-data">
                                    <input type="hidden" name="redir" value="<?= $redir ?>">
                                    <?= csrf() ?>
                                    <div class="form-group">
                                        <input class="form-control form-control-lg" type="text" id="email" name="email" placeholder="<?= label('user') ?>" value="<?= (!empty($cookie) ? $cookie : null) ?>" required>
                                    </div>
                                    <div class="form-group">
                                        <span class="float-right">
                                            <a href="<?= url("/forget/") ?>"><?= label('forgot_pass') ?></a>
                                        </span>
                                        <input class="form-control form-control-lg" type="password" id="password" name="password" placeholder="<?= label('password') ?>" required>
                                    </div>
                                    <div>
                                        <div class="custom-control custom-checkbox align-items-center">
                                            <input type="checkbox" class="custom-control-input" id="remember-me" name="remember-me">
                                            <label class="custom-control-label text-small" for="remember-me"><?= label('keep_logged_in') ?></label>
                                            <!-- <label class="custom-control-label text-small" for="remember-me">Lembrar-me</label> -->
                                        </div>
                                    </div>
                                    <div class="text-right mt-3">
                                        <button type="submit" class="btn btn-lg btn-primary px-6"><?= label('login') ?></button>
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