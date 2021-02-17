<?php $this->layout("_auth-theme", array("title" => $title)) ?>

<main class="main d-flex justify-content-center w-100">
    <div class="container d-flex flex-column">
        <div class="row h-100">
            <div class="col-sm-11 col-md-8 mx-auto d-table h-100">
                <div class="d-table-cell align-middle">
                    <div class="text-center mt-4">
                        <h1 class="h2"><?= label("get_started") ?></h1>
                        <p class="lead opacity-70">
                            <?= msg("get_started") ?>
                        </p>
                    </div>
                    <div class="card">
                        <div class="card-body">
                            <div class="m-sm-4">
                                <form class="auth_form" action="<?= url('/register/') ?>" method="post"
                                    enctype="multipart/form-data">
                                    <div class="ajax_response"><?= flash_message(); ?></div>
                                    <?= csrf() ?>
                                    <h5><?= label("user_info") ?></h5>
                                    <div class="form-row">
                                        <div class="form-group col-sm-12 col-md-6">
                                            <input class="form-control" type="text" name="first_name"
                                                placeholder="<?= label("first_name") ?>" required>
                                        </div>
                                        <div class="form-group col-sm-12 col-md-6">
                                            <input class="form-control" type="text" name="last_name"
                                                placeholder="<?= label("last_name") ?>" required>
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-group col-sm-12 col-md-8">
                                            <input class="form-control" type="email" name="email"
                                                placeholder="<?= label("email") ?>" required>
                                        </div>
                                        <div class="form-group col-sm-12 col-md-4">
                                            <input class="form-control" type="tel" name="phone"
                                                placeholder="<?= label("phone") ?>" maxlength="20" required>
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-group col-sm-12 col-md-6">
                                            <input class="form-control" type="password" name="password"
                                                placeholder="<?= label("password") ?>" required>
                                        </div>
                                        <div class="form-group col-sm-12 col-md-6">
                                            <input class="form-control" type="password"
                                                name="confirm_pass" placeholder="<?= label("confirm_pass") ?>"
                                                required>
                                        </div>
                                    </div>
                                    <hr>
                                    <h5><?= label("company_info") ?></h5>
                                    <div class="form-group">
                                        <input type="text" class="form-control" id="company" name="company" placeholder="<?= label("company") ?>" required>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-group col-12">
                                            <input type="text" class="form-control" id="address" name="address" placeholder="<?= label("address") ?>"  required>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <input type="text" class="form-control" id="city" name="city" placeholder="<?= label("city") ?>" required>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <input type="text" class="form-control" id="province" name="province" placeholder="<?= label("state") ?>"
                                                required>
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-group col-md-8">
                                            <select name="country" id="country" class="form-control" required>
                                                <option value=""><?= label("country") ?></option>
                                                <?php foreach ($countries as $country) : ?>
                                                <option value="<?= $country->abbreviation ?>">
                                                    <?= $country->country ?>
                                                </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                        <div class="form-group col-md-4">
                                            <input type="tel" class="form-control" id="zipcode" name="zipcode" placeholder="<?= label("zipcode") ?>" required>
                                        </div>
                                    </div>
                                    <div class="text-center mt-3">
                                        <button type="submit"
                                            class="btn btn-lg btn-primary"><?= label("create_account") ?></button>
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
