<?php $this->layout('_theme', array('title' => $title, 'filter' => (!empty($filter) ? $filter : false))) ?>

<div class="mb-2 mb-xl-4">
    <div class="col-auto d-sm-block">
        <h1><?= $title ?> </h1>

        <div style="position: absolute;top: 2px;right: 15px;">
            <?php if (isset($rows)) : ?>
                <?php if ($rows > 0) : ?>
                    <a class="badge badge-danger" href="<?= url("relatorio/whatsapp/csv/?" . query_string()) ?>"><?= label('export') ?> CSV</a>
                <?php endif; ?>
                <span class="badge badge-info"><strong><?= label('transactions') ?>:</strong> <span class="rows"><?= $rows ?></span></span>
            <?php endif; ?>
        </div>
    </div>
</div>
<div class="card-body bg-white rounded">
    <?php if (!empty($rows)) : ?>
        <table class="table table-striped">
            <thead class="thead">
                <tr>
                    <!-- 'tipo,`data` as periodo,contato,mensagem' -->
                    <th scope="col" style="width: 20%;"><?= label('date') ?></th>
                    <th scope="col" style="width: 20%;"><?= label('message') ?></th>
                    <th scope="col" style="width: 20%;"><?= label('contact') ?></th>
                    <th scope="col" style="width: 20%;">Status</th>
                    <th scope="col" style="width: 20%;"><?= label('delete') ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($result  as $key => $value) :  ?>
                    <?php $msg = $value->mensagem; ?>
                    <?php (strlen($msg) > 75 ? $msg_exibe = substr($msg, 0, 75) . '...  <i title="'.label('view_message').'" style="font-size: large;" class="fas fa-eye text-info"></i>' : $msg_exibe = $msg); ?>
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
                        <td><button class="btn shadow-none" data-toggle="modal" data-target="#modal-remove" data-url="<?= url("relatorio/whatsapp/{$value->id}/") ?>">
                                <svg data-toggle="tooltip" data-placement="left" data-original-title="<?= label('delete') ?>" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash-2 align-middle mr-2">
                                    <polyline points="3 6 5 6 21 6"></polyline>
                                    <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                    <line x1="10" y1="11" x2="10" y2="17"></line>
                                    <line x1="14" y1="11" x2="14" y2="17"></line>
                                </svg>
                            </button></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <?= (!empty($rows) ? show_pagination($rows)  : '') ?>
    <?php else : ?>
        <h4 class="text-center"><?= msg("no_data_found") ?></h4>
    <?php endif; ?>

</div>

<?php $this->insert("includes/modal/remove"); ?>

<?php $this->start("scripts"); ?>
<script src="<?= theme("./assets/js/scripts/mostrar-mensagem.js") ?>"></script>
<?php $this->stop(); ?>