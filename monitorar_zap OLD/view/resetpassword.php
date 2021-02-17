<div class="container-fluid">
  <div class="login-page">
    <div class="form">
      <div id="loader"></div>
      <div id="msgerror"></div>
      <input type="hidden" id="txtget" value="<?= $_GET['token']?:''?>">
      <form class="torecover-form" action="javascript:void(0);">
        <input type="hidden" id="page" value="torecover" />
        <input type="text" placeholder="email" id="txtuseremail" readonly="readonly" />
        <input type="password" placeholder="senha" id="txtpassword" required="required" />
        <input type="password" placeholder="confirmar senha" id="txtconfirmpassword" required="required" />
        <button type="submit" id="btnReset">Enviar</button>
      </form>
    </div>
  </div>
</div>
<link rel="stylesheet" type="text/css" href="<?= BASE_URL; ?>assets/css/home.css?v=<?=$versao?>">
<script type="text/javascript" src=<?= BASE_URL;?>assets/js/home.js?v=<?=$versao?>></script>
<script type="text/javascript" src=<?= BASE_URL;?>assets/js/recall.js?v=<?=$versao?>></script>