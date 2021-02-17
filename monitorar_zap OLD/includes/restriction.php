<?php

/*if($pag != '_restrito' || $pag != 'home'){
	if(isset($_SESSION['imei']))
	{
		header('Location: http://admin.online-mania.net/?page=restrito');	
		exit();
	}
}*/

if(!isset($_COOKIE['_hashimei'])){
	if(($pag != "home") && ($pag != "index") && ($pag != "forgot-password") && ($pag != "resetpassword")  && ($pag != "changepassword"))
	{
		//header("Location: http://localhost/afiliado.onlinemania/home");
	    header("Location: http://zap.monitorar.info/?page=home");
		exit;	
	}
}