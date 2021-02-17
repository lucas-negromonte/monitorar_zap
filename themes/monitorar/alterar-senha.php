<?php $this->layout('_theme', array('title' => $title, 'filter' => (!empty($filter) ? $filter : false))) ?>

<div class="mb-2 mb-xl-4">
    <div class="col-auto d-sm-block">
        <h1><?= $title ?> </h1>
    </div>
</div>

<form action="<?= url("configuracao/alterar-senha/") ?>" method="post" enctype="multipart/form-data" autocomplete="off">
    <?= csrf() ?>

    <div class="shadow-lg p-3 mb-5 bg-white rounded">
        <div class="row">
            <div class="col-sm-6 m-auto">
                <div class="form-group">
                    <label><?= label('current_pass') ?>:</label>
                    <input type="password" name="senhaAtual" id="senhaAtual" class="text-left form-control" placeholder="<?= label('current_pass') ?>.." required />
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-6 m-auto">
                <div class="form-group">
                    <label><?= label('new_password') ?>:</label>
                    <input type="password" name="senha" id="senha" class="text-left form-control" placeholder="<?= label('new_password') ?>.." required />
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-6 m-auto">
                <div class="form-group">
                    <label><?= label('confirm_pass') ?>:</label>
                    <input type="password" name="confirmacao" id="confirmacao" placeholder="<?= label('confirm_pass') ?>..." class="form-control" />
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-6 m-auto">
                <div class="form-group text-right">
                    <button type="buttom" class="btn btn-info " style="width: 48%;"> <?= label('update') ?></button>
                </div>
            </div>
        </div>
    </div>
</form>

<?php $this->start("scripts") ?>
<?php /*$this->insert("includes/datepicker");*/ ?>
<?php $this->stop() ?>