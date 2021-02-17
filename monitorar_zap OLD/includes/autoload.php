<?php
function __autoload($class_name) {
	$file = APP_ROOT.'/model/' . $class_name . '.php';
	
	if ( ! file_exists( $file ) ) {
		require_once APP_ROOT . 'view/error/404.php';
	}
	
    require_once $file;
} 
