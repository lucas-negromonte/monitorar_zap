<div class="modal fade" id="modalregistrar" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">Registro</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div id="msg"></div>
        <div class="loader2"></div>
        <form action="javacript:void(0);" id="formlicenca" name="formlicenca">
          <label>Informe a licença</label>
          <input class="form-control" id="txtlicenca" name="txtlicenca">
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-success btnsavelicenca">Save Changes</button>
      </div>
    </div>
  </div>
</div>
<script type="text/javascript" src="<?= BASE_URL;?>assets/js/registro.js?v=<?=$versao?>"></script>