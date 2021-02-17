<!-- Modal Excluir Audio -->
<div class="modal fade" id="modalExcluirAudio" tabindex="-1" role="dialog" aria-labelledby="deleteAudio" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle"><?= msg('confirm_delete_audio') ?> </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <b class="text-danger"><?= msg('attention_audio') ?></b>
                <div class="modal-audio-mostrar"></div>
                <div class="modal-audio-msg text-center" role="alert"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal"><?=label('close')?></button>
                <button type="button" class="btn btn-danger btn-excluir-audio" idAudio="0"><?= label('remove')?></button>
            </div>
        </div>
    </div>
</div>