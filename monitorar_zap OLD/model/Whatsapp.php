<?php

class Whatsapp
{
	public $imei;
	public $nomeContato;

	public function __construct($imei = '', $nomeContato = '')
	{
		$this->imei = $imei;
		$this->nomeContato = $nomeContato;
	}
	public function getWhatsappMessage()
	{
		try
		{
			$model = new Conn();
			$db = $model->getBase();

			$sqlWhatsapp  = "SELECT DISTINCT nome_contato FROM mensagens WHERE imei = :imei";
			$sqlWhatsappM = $db->prepare($sqlWhatsapp);
			$sqlWhatsappM->BindValue(':imei', $this->imei, PDO::PARAM_STR);
			$return = $sqlWhatsappM->execute();
			$rows   = $sqlWhatsappM->rowCount();

			if($return === false)
				return false;
			else
				return array($sqlWhatsappM, $rows);

		}catch(PDOException $e){
		 LogHelper::logErro($e->getMessage(), basename(__FILE__), $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'], $e->getLine());
		 return false;

		}
	}

	public function getMessageUser()
	{
		try
		{
			$model = new Conn();
			$db = $model->getBase();

			$sqlMessage  = "SELECT nome_contato, mensagem, DATE_FORMAT(STR_TO_DATE(data, '%Y-%m-%d %H:%i:%s'),'%d/%m/%Y %H:%i:%s') AS data, tipo FROM mensagens WHERE imei = :imei AND nome_contato = :nome_contato";
			$sqlMessageUser = $db->prepare($sqlMessage);
			$sqlMessageUser->BindValue(':imei', $this->imei, PDO::PARAM_STR);
			$sqlMessageUser->BindValue(':nome_contato', $this->nomeContato, PDO::PARAM_STR);
			$return = $sqlMessageUser->execute();
			$rows   = $sqlMessageUser->rowCount();

			if($return === false)
				return false;
			else
				return array($sqlMessageUser, $rows);

		}catch(PDOException $e){
		 LogHelper::logErro($e->getMessage(), basename(__FILE__), $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'], $e->getLine());
		 return false;

		}
	}

	public function getImei(){
		return $this->imei;
	}

	public function setImei($imei){
		$this->imei = $imei;
	}
	public function getNomecontato(){
		return $this->nomeContato;
	}

	public function setNomecontato($nomeContato){
		$this->nomeContato = $nomeContato;
	}
		
}
$obj = new Whatsapp();