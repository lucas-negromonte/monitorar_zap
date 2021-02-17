<div class="modal fade" id="modalalteradados" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">Editar Dados</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div id="msgerro"></div>
        <div id="loader"></div>
        <form action="javascript:void(0);" id="formeditardados" name="formeditardados">
          <input type="hidden" id="page" name="page" value="dados">
          <input type="hidden" id="txtimei2" name="txtimei2">
          <div class="row">
            <div class="col-md pt-1 mb-1 ">
              <label for="txtlicenca">Email</label>
              <input type="text" class="form-control" id="txtemail" name="txtemail" required="required">
            </div>
          </div>
          <div class="row">
            <div class="col-md pt-1 mb-1">
              <label for="txtsenhaatual">Senha Atual</label>
              <input type="password" class="form-control" id="txtsenhaatual" name="txtsenhaatual" readonly="readonly">
            </div>
          </div>
          <div class="row">
            <div class="col-md pt-1 mb-1">
              <label for="txtsenhanova">Senha Nova</label>
              <input type="password" class="form-control" id="txtsenhanova" name="txtsenhanova" required="required">
            </div>
          </div>
          <div class="row">
            <div class="col-md pt-1 mb-1">
              <label for="txtsenhaconfirma">Confirmar Senha</label>
              <input type="password" class="form-control" id="txtsenhaconfirma" name="txtsenhaconfirma" required="required">
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
          <button type="submit" class="btn btn-success">Salvar</button>
        </div>
      </form>
    </div>
  </div>
</div>
