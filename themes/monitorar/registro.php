<?php $this->layout('_theme', array('title' => $title, 'filter' => (!empty($filter) ? $filter : false))) ?>

<?php if ($cadastrar || $renovar) : ?>
    <div class="shadow-lg p-3 mb-5 rounded text-center" style="background: #f8d7da;">
        <div class="row">
            <div class="col-12">
                <h4 class="text-danger"><?= label('attention') ?>!</h4>
            </div>
            <div class="col-12">
                <p style="font-size: large;"><b> <?= ($cadastrar ? msg('register_license')  : msg('renove_license')) ?> </b> </p>
            </div>
        </div>
    </div>
<?php endif; ?>


<div class="mb-2 mb-xl-4">
    <div class="col-auto d-sm-block">
        <h1><?= $title ?> </h1>
    </div>
</div>

<form action="<?= url("configuracao/registro/") ?>" method="post" enctype="multipart/form-data" autocomplete="off">
    <?= csrf() ?>

    <div class="shadow-lg p-3 mb-5 bg-white rounded">
        <div class="row">
            <div class="col-sm-6 m-auto">
                <div class="form-group"> 
                    <label><b><?= label('license') ?>:</b></label>
                    <input type="text" name="licenca" id="licenca" value="<?= $licenca ?>" class="text-left form-control" required />
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-6 m-auto">
                <div class="form-group">
                    <label><b><?= trim(label('expires_in')) ?>:</b></label>
                    <input type="text" name="validade" id="validade" disabled value="<?= $dias ?> <?= label('day') . 's' ?>" class="form-control" />
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-6 m-auto">
                <div class="form-group text-center">
                    <?php if ($diferenca <= 5) : ?>
                        <a type="buttom" class="btn btn-warning text-white" href="https://www.timonitor.com.br/mobile/" target="_BLANCK" style="width: 48%;"> <?= label('renew_license') ?></a>
                    <?php endif; ?>
                    <button type="buttom" class="btn btn-success " style="width: 48%;"> <?= label('change_license') ?></button>
                </div>
            </div>
        </div>
    </div>
</form>

<?php $this->start("scripts") ?>
<?php /*$this->insert("includes/datepicker");*/ ?>
<?php $this->stop() ?>