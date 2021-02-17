<?php

session_start();

class Conn{

	protected $fullBd;

	public function __construct() {

		$this->fullBd = $this->getBase();
		$this->fullBd = $this->getGoodShop();
	}
	public function getBase(){
		$host     = '104.238.136.203';
		$login    = 'tisoftware';
		$password = '$Edivan2018';
		$db       = 'monitor_zap';

		try{
			$fullBd = new PDO('mysql:host='.$host.';dbname='.$db,$login,$password,array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
			$fullBd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			return $fullBd;

		}catch(PDOException $e){
			LogHelper::logErro($e->getMessage(), basename(__FILE__), $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'], $e->getLine());
			return false;
		}
	}
	public function getGoodShop(){
		$host     = 'gstv-rdbms.cnqdpix5zbfx.sa-east-1.rds.amazonaws.com';
		$login    = 'tisoftwa_system';
		$password = 'GoodShop611';
		$db       = 'tisoftwa_system';

		try{
			$fullBd = new PDO('mysql:host='.$host.';dbname='.$db,$login,$password,array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
			$fullBd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			return $fullBd;

		}catch(PDOException $e){
			LogHelper::logErro($e->getMessage(), basename(__FILE__), $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'], $e->getLine());
			return false;
		}
	}
}
$obj = new Conn();
