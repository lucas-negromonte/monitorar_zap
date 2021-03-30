<!-- start nav top -->
<nav class="navbar navbar-expand navbar-light bg-white sticky-top">
    <a class="sidebar-toggle d-flex mr-2">
        <i class="hamburger align-self-center"></i>
    </a>

    <div class="navbar-collapse collapse">
        <ul class="navbar-nav ml-auto">
            <li class="nav-item dropdown">
                <a class="nav-flag dropdown-toggle" id="languageDropdown" data-toggle="dropdown">
                </a>
                <div class="dropdown-menu dropdown-menu-right languageDropdown" aria-labelledby="languageDropdown">

                    <div class="dropdown-menu-header">
                        <div class="position-relative">
                            <?= label("language") ?>
                        </div>
                    </div>

                    <a class="dropdown-item" data-language="pt" data-section="top" data-update="true" data-url="<?= url('/account/profile/') ?>">
                        <img src="<?= theme("assets/images/languages/pt.png") ?>" width="20" class="align-middle mr-1" />
                        <span class="align-middle"><?= label("portuguese") ?></span>
                    </a>
                </div>
            </li>
            <!-- Usuario  -->
            <!-- <li class="nav-item dropdown">
                <strong><?= label('you_welcome') ?>, <?= session()->user->device ?></strong>
            </li> -->

            <!-- Language  -->
            <!-- <li class="nav-item dropdown">
                <button type="button" class="btn shadow-none dropdown-toggle" id="languageDropdown" data-toggle="dropdown">
                    <img class="rounded" src="<?= theme("assets/images/languages/" . session()->user->language . ".png") ?>" style="height: 19px;width: 29px;" />
                </button>

                <div class="dropdown-menu dropdown-menu-right languageDropdown" aria-labelledby="languageDropdown">

                    <div class="dropdown-menu-header">
                        <div class="position-relative">
                            <?= label("language") ?>
                        </div>
                    </div>

                    <?php if (session()->user->language != "en") : ?>
                        <a class="dropdown-item" data-language="en" data-section="top" data-update="true" data-url="<?= url('/account/profile/') ?>">
                            <img src="<?= theme("assets/images/languages/en.png") ?>" width="20" class="align-middle mr-1" />
                            <span class="align-middle"><?= label("english") ?></span>
                        </a>
                    <?php endif; ?>

                    <?php if (session()->user->language != "pt") : ?>
                        <a class="dropdown-item" data-language="pt" data-section="top" data-update="true" data-url="<?= url('/account/profile/') ?>">
                            <img src="<?= theme("assets/images/languages/pt.png") ?>" width="20" class="align-middle mr-1" />
                            <span class="align-middle"><?= label("portuguese") ?></span>
                        </a>
                    <?php endif; ?>

                    <?php if (session()->user->language != "es") : ?>
                        <a class="dropdown-item" data-language="es" data-section="top" data-update="true" data-url="<?= url('/account/profile/') ?>">
                            <img src="<?= theme("assets/images/languages/es.png") ?>" width="20" class="align-middle mr-1" />
                            <span class="align-middle"><?= label("spanish") ?></span>
                        </a>
                    <?php endif; ?>

                </div>
            </li> -->

            <!-- filtro  -->
            <li class="nav-item div-btn-filtro" id="div-btn-filtro" style="display:none;">
                <a type="button" class="btn btn shadow-none " id="btnOpenFilter" data-toggle="modal" data-target="#modalFilter" style="background:  #075e54;"><i class="fas fa-filter text-white"></i></a>
            </li>


            <!-- Sair  -->
            <li class="nav-item">
                <a type="button" class="btn btn-danger shadow-none" href="<?= url("/logout/") ?>"><?= label('logout') ?></a>
            </li>

        </ul>
    </div>
</nav>
<!-- end nav top -->