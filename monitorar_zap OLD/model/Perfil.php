<?php

class Perfil
{
	private $imei;
	public  $emailnovo;
	public  $senhanova;

	public function __construct($imei = '', $emailnovo = '', $senhanova = '')
	{
		$this->imei      = $imei;
		$this->emailnovo = $emailnovo;
		$this->senhanova = $senhanova;

	}
	public function getPerfil()
	{
		$conn = new Conn();
		$db = $conn->getBase();
		try
		{
			$getperfil  = "SELECT imei, device, email, senha, licenca, vencimento FROM account WHERE imei = :imei";
			$getperfil2 = $db->prepare($getperfil); 
			$getperfil2->bindValue(':imei', $this->imei, PDO::PARAM_STR);
			$return = $getperfil2->execute();
			$rows = $getperfil2->execute();

			if($return === true){
				return array($getperfil2);
			}else{
				return false;
			}

		}catch(PDOException $e)
		{
			LogHelper::logErro($e->getMessage(), basename(__FILE__), $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'], $e->getLine());
			return false;
		}
	}

	public function submitPerfildados()
	{
		$conn = new Conn();
		$db = $conn->getBase();
		try
		{
			$getperfil  = "UPDATE account SET email = :email, senha = TO_BASE64(:senha) WHERE imei = :imei";
			$getperfil2 = $db->prepare($getperfil); 
			$getperfil2->bindValue(':imei',  $this->imei, PDO::PARAM_STR);
			$getperfil2->bindValue(':email', $this->emailnovo, PDO::PARAM_STR);
			$getperfil2->bindValue(':senha', $this->senhanova, PDO::PARAM_STR);
			$return = $getperfil2->execute();

			if($return === true){
				return true;
			}else{
				return false;
			}

		}catch(PDOException $e)
		{
			LogHelper::logErro($e->getMessage(), basename(__FILE__), $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'], $e->getLine());
			return false;
		}
	}
}