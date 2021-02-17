<?php $this->layout('_theme', array('title' => $title, 'filter' => (!empty($filter) ? $filter : false))) ?>

<div class="shadow-lg p-3 mb-5 rounded text-center" style="background: #f8d7da;">
    <form action="<?= url('configuracao/trocar-aparelho/') ?>" name="form_troca" id="form_troca" method="post">
        <?= csrf() ?>
        <div class="row">
            <div class="col-12">
                <h4 class="text-danger"><?= strtoupper(label('attention')) ?>!!!</h4>
            </div>
            <div class="col-12">
                <p style="font-size: large;">
                    <?= msg('confirm_device_exchange') ?>
                </p>
            </div>
            <div class="col-12">
                <hr>
                <button type="submit" class="btn btn-danger"><?= label('device_exchange') ?> </button>
            </div>
        </div>
    </form>
</div>

<?php $this->start("scripts") ?>
<?php /*$this->insert("includes/datepicker");*/ ?>
<?php $this->stop() ?>