<?php

function loadPage(){

	$page = filter_input(INPUT_GET, 'page', FILTER_SANITIZE_STRING);
	
	$page = (!$page) ? 'view/home.php' : "view/{$page}.php"; 
	if(!file_exists($page)){

		$page = "view/error/404.php"; 	
	}
	return $page;
}

$pg   = explode('/',$_SERVER['SCRIPT_NAME']);
$pg   = end($pg);
$file = $pg;
$pg   = str_replace(".php","",$pg);

$pag   = (!isset($_GET['page'])) ? $page : $_GET['page'];

$_SESSION['pg'] = $pag;

function title($pag){
	$tte = explode('-',$pag);
	$tt  = count($tte);
	if($tt > 1){
		$title = ucfirst($tte[0]).' '.ucfirst($tte[1]);
	}else{
		$title = ucfirst($tte[0]);
	}
	return $title;
}

