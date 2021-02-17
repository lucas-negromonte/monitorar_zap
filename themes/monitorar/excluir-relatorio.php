<?php $this->layout('_theme', array('title' => $title, 'filter' => (!empty($filter) ? $filter : false))); ?>
<div class="mb-2 mb-xl-4">
    <div class="col-auto d-sm-block">
        <h1><?= $title ?> </h1>
    </div>
</div>

<div class="card-body bg-white rounded">
    <table class="table table-striped">
        <thead class="thead">
            <tr>
                <th scope="col" style="width: 25%;"><?= label('reports') ?></th>
                <th scope="col" style="width: 25%;">Total</th>
                <th scope="col" style="width: 25%;"><?= label('period') ?></th>
                <th scope="col" style="width: 25%;"><?= label('delete') ?></th>
            </tr>
        </thead>
        <tbody>
            <input type="hidden" name="data1" value="<?= (isset($filter->data1) ? $filter->data1 : '') ?>">
            <input type="hidden" name="data2" value="<?= (isset($filter->data2) ? $filter->data2 : '') ?>">

            <tr>
                <td>Whatsapp</td>
                <td><?= (isset($total->mensagens) ? $total->mensagens : 0) ?></td>
                <td><b><?= label('start') . ': ' . (!empty($filter->data1) ? date('d.m.Y', strtotime($filter->data1)) : label('general')) . '<br>' . label('end') . ': ' . (!empty($filter->data2) ? date('d.m.Y', strtotime($filter->data2)) : label('general')) ?></b></td>
                <td><button class="btn shadow-none" data-toggle="modal" data-target="#modal-remove" data-url="<?= url("configuracao/excluir-relatorio/mensagens/" . (!empty($filter->data1) ? $filter->data1 : '0') . '/' . (!empty($filter->data2) ? $filter->data2 : '0') . '/') ?>">
                        <svg data-toggle="tooltip" data-placement="left" data-original-title="<?= label('delete') ?>" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash-2 align-middle mr-2">
                            <polyline points="3 6 5 6 21 6"></polyline>
                            <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                            <line x1="10" y1="11" x2="10" y2="17"></line>
                            <line x1="14" y1="11" x2="14" y2="17"></line>
                        </svg>
                    </button></td>
            </tr>


        </tbody>
    </table>
</div>


<?php $this->insert("includes/modal/remove"); ?>

<?php $this->start("scripts"); ?>
<script src="<?= theme("./assets/js/scripts/mostrar-mensagem.js") ?>"></script>
<?php $this->stop(); ?>