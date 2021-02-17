<!-- Modal Inserir|Atualizar relatório -->
<div class="modal fade" id="modal-remove" role="dialog" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
        <div class="modal-content">
            <input type="hidden" name="param" id="param">
            <div class="modal-header">
                <h5 class="modal-title"><?= label('remove') ?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <?= msg('confirm_remove') ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                    <?= label('close') ?>
                </button>
                <button type="button" class="btn btn-danger" id="confirm_remove" data-remove="">
                    <?= label('remove') ?>
                </button>
            </div>
        </div>
    </div>
</div>