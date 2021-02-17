<?php $this->layout('_theme', array('title' => $title)) ?>

<div class="row mb-2 mb-xl-4">
    <div class="col-auto d-sm-block">
        <h1 class="mb-0 pb-0"><?= $title ?> </h1>
    </div>
</div>

<div class="row">
    <div class="col-12 col-sm-4 col-xl d-flex">
        <div class="card flex-fill">
            <div class="card-body">
                <span class="badge badge-info float-right"><?= label('today') ?></span>
                <div class="mb-0"><a class="btn p-0 shadow-none" href="<?= url('relatorio/whatsapp') ?>">Mensagens</a></div>
                <h3 class="mb-2">
                    <?php if (!empty($total->whatsToday)) : ?>
                        <?= format_number($total->whatsToday) ?>
                    <?php else : ?>
                        0
                    <?php endif ?>
                </h3>
            </div>
        </div>
    </div>

    <div class="col-12 col-sm-4 col-xl d-flex">
        <div class="card flex-fill">
            <div class="card-body">
                <span class="badge badge-warning float-right"><?= label('last_7_days') ?></span>
                <div class="mb-0"><a class="btn p-0 shadow-none" href="<?= url('relatorio/whatsapp') ?>">Mensagens</a></div>
                <h3 class="mb-2">
                    <?php if (!empty($total->sevenDays)) : ?>
                        <?= format_number($total->sevenDays) ?>
                    <?php else : ?>
                        0
                    <?php endif ?>
                </h3>
            </div>
        </div>
    </div>


    <div class="col-12 col-sm-4 col-xl d-flex">
        <div class="card flex-fill">
            <div class="card-body">
                <span class="badge badge-dark float-right">Total</span>
                <div class="mb-0"><a class="btn p-0 shadow-none" href="<?= url('relatorio/sms') ?>">Mensagens</a></div>
                <h3 class="mb-2">
                    <?php if (!empty($total->whats)) : ?>
                        <?= format_number($total->whats) ?>
                    <?php else : ?>
                        0
                    <?php endif ?>
                </h3>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12 col-sm-6 col-xl d-flex">
        <div class="card flex-fill">
            <div class="card-body">
                <h5 class="card-title"><?= label('device_information') ?></h5>
                <br>
                <h6 class="card-subtitle mb-2 text-muted"> <i class="fas fa-mobile-alt"></i> <?= label('device_model') ?>: <b><?= (!empty($account->device) ? $account->device : '-') ?></b> </h6>
                <p></p>
                <h6 class="card-subtitle mb-2 text-muted"> <i class="fas fa-code-branch"></i> <?= label('current_version') ?>: <b><?= (!empty($account->versao_app) ? $account->versao_app : '-') ?></b> </h6>
                <p></p>
                <h6 class="card-subtitle mb-2 text-muted"> <i class="fas fa-robot"></i> Android: <b><?= (!empty($account->android) ? $account->android : '-') ?></b> </h6>
            </div>
        </div>
    </div>
    <div class="col-12 col-sm-6 col-xl d-flex">
        <div class="card flex-fill">
            <div class="card-body">

                <h5 class="card-title"><?= label('license') ?></h5>
                <br>
                <h6 class="card-subtitle mb-2 text-muted"> <i class="fas fa-signal"></i> Status: <?= ($account->uso_lic == 1 ? '<b class="text-success">' . label('active') . '</b>' : '<b class="text-warning">' . label('pending') . '</b>') ?> </h6>
                <br>
                <h6 class="card-subtitle mb-2 text-muted"> <i class="fas fa-portrait"></i> <?= label('due_date') ?>: <b><?= date("d/m/Y", strtotime($account->vencimento)) ?></b> </h6>
                <br>
                <h6 class="card-subtitle mb-2 text-muted"> <i class="fas fa-calendar-alt"></i> <?= label('shelf_life') ?> :
                    <?php
                    $data_inicio = new DateTime(date('Y-m-d'));
                    $data_fim = new DateTime($account->vencimento);
                    // Resgata diferença entre as datas
                    $dateInterval = $data_inicio->diff($data_fim); ?>
                    <?php if (date('Ymd') > date('Ymd', strtotime($account->vencimento))) : ?>
                        <b class="text-danger"><?= label('expired') ?> <?= $dateInterval->days ?> <?= label('day') ?><?= ($dateInterval->days > 1 ? 's' : '') ?></b>
                    <?php else : ?>
                        <?php if ($dateInterval->days == 0) : ?>
                            <b class="text-danger"><?= label('expires_today') ?></b>
                        <?php else : ?>
                            <b class="text-success"><?= label('expires_in') ?> <?= $dateInterval->days ?> <?= label('day') ?><?= ($dateInterval->days > 1 ? 's' : '') ?></b>
                        <?php endif; ?>
                    <?php endif; ?>
                </h6>
            </div>
        </div>
    </div>
</div>


<div class="container-fluid rounded bg-white p-4 overflow-auto">
    <h5 class="card-title">Ultimas mensagens</h5>
    <br>
    <?php if (!empty($whats)) : ?>

        <table class="table table-sm">
            <thead class="thead">
                <tr>
                    <th scope="col" style="width: 20%;"><?= label('date') ?></th>
                    <th scope="col" style="width: 20%;"><?= label('message') ?></th>
                    <th scope="col" style="width: 20%;"><?= label('contact') ?></th>
                    <th scope="col" style="width: 20%;">Status</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($whats  as $key => $value) :  ?>
                    <?php $msg = $value->mensagem; ?>
                    <?php (strlen($msg) > 75 ? $msg_exibe = substr($msg, 0, 75) . '...  <i title="' . label('view_message') . '" style="font-size: large;" class="fas fa-eye text-info"></i>' : $msg_exibe = $msg); ?>
                    <tr class="btn-mostrar-msg" data-seq="<?= $key ?>" data-msg="<?= $msg ?>">
                        <td><b><?= date('d/m/Y H:i', strtotime($value->periodo)) ?></b></td>
                        <td>
                            <p class="mostrar-msg-<?= $key ?>"><?= (strlen($msg) > 75 ? $msg_exibe : $msg) ?></p>
                        </td>
                        <td><?= $value->contato ?></td>
                        <td>
                            <?php
                            if ($value->tipo == 0) : ?>
                                <b><i class="fas fa-check-double text-danger"></i> <?= label('received') ?> </b>
                            <?php elseif ($value->tipo == 1) : ?>
                                <b> <i class="fas fa-check-double text-success"></i> <?= label('sent') ?> </b>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>

            </tbody>
        </table>
    <?php else : ?>
        <small> <?= msg('no_data_found') ?></small>
    <?php endif; ?>
</div>


<?php $this->start("scripts") ?>

<?php /*$this->insert("includes/datepicker");*/ ?>
<?php $this->stop() ?>