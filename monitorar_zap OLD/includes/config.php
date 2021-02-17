<?php
$versao = '1.6.52.67';
date_default_timezone_set('america/sao_paulo');
$script_tz = date_default_timezone_get();

define('BASE_URL', 'http://zap.monitorar.info/'); //http://zap.monitorar.info/
define('APP_ROOT', $_SERVER['DOCUMENT_ROOT'].'/');

require_once $_SERVER['DOCUMENT_ROOT'].'/includes/autoload.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/model/Conn.php';

//$imei = $_SESSION['imei'];

