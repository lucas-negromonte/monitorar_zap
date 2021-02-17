<div class="container-fluid">
  <div class="login-page">
    <div class="form">
      <div class="img">
        <img src="<?php BASE_URL?>assets/image/ic_monitor.png" style="width: 50px">
      </div>
      <div id="loader"></div>
      <div id="msgerror"></div>
      <form class="register-form">
    <!--<input type="text" placeholder="name"/>
        <input type="password" placeholder="password" id="password"/>
        <input type="text" placeholder="email address" id="txtemailaddress" />-->
        <input type="text" placeholder="email" id="txtemailrecall" required="" />
        <button type="submit" id="btnsend">Send</button>
        <p class="message">Lembro da senha? <a href="#">Entrar</a></p>
      </form>
      <form class="login-form" action="javascript:void(0);">
        <input type="text" placeholder="email" id="txtusername" required="" />
        <input type="password" placeholder="senha" id="txtpassword" required="" />
        <button type="submit" id="btnlogin">login</button>
        <p class="message">Esqueceu a senha? <a href="#">Recuperar a senha aqui</a></p>
      </form>
    </div>
  </div>
</div>
<link rel="stylesheet" type="text/css" href="<?= BASE_URL; ?>assets/css/home.css?v=<?=$versao?>">
<script type="text/javascript" src=<?= BASE_URL;?>assets/js/home.js?v=<?=$versao?>></script>