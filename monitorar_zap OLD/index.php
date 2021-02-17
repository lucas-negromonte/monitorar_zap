<?php 
include 'includes/config.php';  
require 'includes/pages.php';
include 'includes/restriction.php';
?>
<!DOCTYPE html>
<html>
<head>
	<title></title>
	<noscript><meta http-equiv="refresh" content="1; url=error/error404.html"></noscript>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta name="robots" content="noindex, nofollow" />
	<link rel="shortcut icon" href="<?= BASE_URL; ?>assets/image/ic_monitor.png" type="image/x-icon">
	
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css"> 
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"/>
	<link href="https://cdn.datatables.net/1.10.20/css/dataTables.bootstrap4.min.css" rel="stylesheet">
	<!--<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">-->
	<link rel='stylesheet' href='https://use.fontawesome.com/releases/v5.7.0/css/all.css'>
	<link href="https://cdn.datatables.net/1.10.20/css/dataTables.bootstrap4.min.css" rel="stylesheet">
	<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/1.6.0/css/buttons.dataTables.min.css">
	<link href="https://cdn.datatables.net/responsive/2.2.3/css/responsive.bootstrap.min.css" rel="stylesheet">
	<!--<link rel='stylesheet' href='https://use.fontawesome.com/releases/v5.7.0/css/all.css'>-->
	<link rel="stylesheet" type="text/css" href="<?= BASE_URL; ?>assets/css/menu.css?v=<?=$versao?>">
	<link rel="stylesheet" type="text/css" href="<?= BASE_URL; ?>assets/css/ballowWhatsapp.css?v=<?=$versao?>">
    <link rel="stylesheet" type="text/css" href="<?= BASE_URL; ?>assets/css/loader.css?v=<?=$versao?>">
    <link rel="stylesheet" type="text/css" href="<?= BASE_URL; ?>assets/css/dashboard.css?v=<?=$versao?>">
    <link rel="stylesheet" type="text/css" href="<?= BASE_URL; ?>assets/css/table.css?v=<?=$versao?>">
    <link rel="stylesheet" type="text/css" href="<?= BASE_URL; ?>assets/css/whatsapp.css?v=<?=$versao?>">


    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.js"></script>
    <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>


	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
	<script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
	<script src="https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap4.min.js"></script>
	<script src="https://cdn.datatables.net/responsive/2.2.3/js/dataTables.responsive.min.js"></script>
	<script src="<?= BASE_URL; ?>assets/lib/jquery-cookie/jquery.cookie.js"></script>
	<script src="<?= BASE_URL; ?>assets/js/index.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js"></script>
	<script type="text/javascript">var path = '<?=BASE_URL;?>';</script>
</head>
<body>	
<?php	
if($pag != 'home'):
	echo '<div class="l-main">
  			<div class="">';
endif;

	if(isset($pag)):
		if(($pag != "") && ($pag != 'home') && ($pag != 'resetpassword')):
			require_once APP_ROOT.'includes/menu.php';
		endif;
	endif;

require loadPage(); 

if($pag != 'home'):
	echo '  </div>
		</div>';
endif;
?>
<!--Fim do container-fluid-->
<div id="preloader">
	<div class="inner">
		<div class="bolas">
			<div></div>
			<div></div>
			<div></div>                    
		</div>
	</div>
</div>
</body>
</html>