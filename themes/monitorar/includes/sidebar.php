<?php $url = current_url(); ?>

<nav id="sidebar" class="sidebar sidebar-sticky">
    <div class="sidebar-content js-simplebar">
        <a class="sidebar-brand d-block shadow-sm text-center" href="<?= url("home") ?>">
            <!-- <i class="align-middle fas fa-sitemap text-primary"></i> -->
            <!-- <span class="align-middle">Monitorar Mobile</span> -->
            <img src="<?= theme("assets/images/logo.png") ?>" class="img-fluid w-25">
        </a>
        <ul class="sidebar-nav mt-4">


            <li class="sidebar-item <?= (stristr($url, 'home') ? 'active' : '') ?>">
                <a href="<?= url("home") ?>" data-load="true" class="sidebar-link ">
                    <!-- <i class="fas fa-home"></i> -->
                    <i class="align-middle fas fa-th-large border rounded border-dark p-2"></i>
                    <!-- <img src="<?= theme("assets/images/menu-home.png") ?>" class="img-menu"> -->
                    <span class="align-middle"><?= label('dashboard') ?></span>
                </a>
            </li>
            <hr>


            <!-- Relatorios -->
            <li class="sidebar-item <?= (stristr($url, 'relatorio/') ? 'active' : '') ?>">
                <span data-toggle="collapse" class="sidebar-link collapsed" aria-expanded="">
                    <i class="align-middle fas fa-list border rounded border-dark p-2"></i>
                    <span class="align-middle"><?= label('reports') ?></span>
                </span>
                <ul class="sidebar-dropdown list-unstyled collapse" <?= (stristr($url, 'relatorio/') ? 'style="display: block;"' : '') ?>>

                    <!-- whatsapp -->
                    <li class="sidebar-subitem ">
                        <a class=" sidebar-link <?= (stristr($url, 'relatorio/whatsapp') ? 'active' : '') ?>" data-load="true" href="<?= url("relatorio/whatsapp") ?>">
                            <!-- <i class="fas fa-circle<?= (stristr($url, 'relatorio/whatsapp') ? '' : '-notch') ?>"></i> -->
                            <!-- <i class="fab fa-whatsapp"></i> -->
                            <i class="fas fa-comments"></i>
                            WhatsApp
                        </a>
                    </li>
                </ul>
            </li>

            <!-- Configuração -->
            <li class="sidebar-item <?= (stristr($url, 'configuracao/') ? 'active' : '') ?>">
                <span data-toggle="collapse" class="sidebar-link collapsed" aria-expanded="">
                    <i class="align-middle fas fa-wrench border rounded border-dark p-2"></i>
                    <span class="align-middle"><?= label('settings') ?></span>
                </span>
                <ul class="sidebar-dropdown list-unstyled collapse" <?= (stristr($url, 'configuracao/') ? 'style="display: block;"' : '') ?>>
                  

                    <!-- registro -->
                    <li class="sidebar-subitem ">
                        <a class=" sidebar-link <?= (stristr($url, 'configuracao/registro') ? 'active' : '') ?>" data-load="true" href="<?= url("configuracao/registro") ?>">
                            <!-- <i class="fas fa-circle<?= (stristr($url, 'configuracao/registro') ? '' : '-notch') ?>"></i> -->
                            <i class="fas fa-clipboard-list"></i>
                            <?= label('record') ?>
                        </a>
                    </li>


                    <!--  alterar senha -->
                    <li class="sidebar-subitem ">
                        <a class=" sidebar-link <?= (stristr($url, 'configuracao/alterar-senha') ? 'active' : '') ?>" data-load="true" href="<?= url("configuracao/alterar-senha") ?>">
                            <!-- <i class="fas fa-circle<?= (stristr($url, 'configuracao/alterar-senha') ? '' : '-notch') ?>"></i> -->
                            <i class="fas fa-lock"></i>
                            <?= label('change_password') ?>
                        </a>
                    </li>


                    <!-- troca-aparelho -->
                    <li class="sidebar-subitem ">
                        <a class=" sidebar-link <?= (stristr($url, 'configuracao/excluir-relatorio') ? 'active' : '') ?>" data-load="true" href="<?= url("configuracao/excluir-relatorio") ?>">
                            <!-- <i class="fas fa-circle<?= (stristr($url, 'configuracao/excluir-relatorio') ? '' : '-notch') ?>"></i> -->
                            <i class="fas fa-trash-alt"></i>
                            <?= label('delete_reports') ?>
                        </a>
                    </li>

                    <!-- troca-aparelho -->
                    <li class="sidebar-subitem ">
                        <a class=" sidebar-link <?= (stristr($url, 'configuracao/trocar-aparelho') ? 'active' : '') ?>" data-load="true" href="<?= url("configuracao/trocar-aparelho") ?>">
                            <!-- <i class="fas fa-circle<?= (stristr($url, 'configuracao/trocar-aparelho') ? '' : '-notch') ?>"></i> -->
                            <i class="fas fa-sync-alt"></i>
                            <?= label('device_exchange') ?>
                        </a>
                    </li>
                </ul>
            </li>

        </ul>

        <!-- <div class="px-4 text-light opacity-50">
            <hr>
            <p class="p-0 m-0 font-weight-bold">
                <span class="badge badge-secondary">
                    Usuário
                </span>
            </p>
            <p class="p-0 m-0 text-sm"><?= session()->user->nome ?></p>
            <p class="p-0 m-0 text-sm"><i class="fas fa-user"></i> <?= session()->user->usuario ?></p>
            <br>

            <p class="p-0 m-0 mb-1 font-weight-bold">
                <span class="badge badge-secondary">
                    ID
                </span>
                <small class="border radius px-1 border-secondary"><?= session()->user->id ?></small>
            </p>
        </div> -->
        <!-- Informações Adicionais -->
        <!-- <div class="px-4 text-white opacity-50" style="position: absolute;bottom: 0;">
            <hr>
            <p class="p-0 m-0 font-weight-bold">
                <span class="badge badge-secondary"><i class="fas fa-envelope"></i> </span>
                <small><?= session()->user->usuario ?></small>
            </p>
        </div> -->
    </div>


</nav>