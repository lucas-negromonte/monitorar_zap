<?php

class Registro
{
	private $imei;
	private $licenca;

	public function __construct($imei = '', $licenca = '')
	{
		$this->imei = $imei;
		$this->licenca = $licenca;
	}

	public function verificaLicenca()
	{
		$model = new Conn();
		$db = $model->getGoodShop();

		try{

			$getregistro = "SELECT c.nome,s.client,e.email,s.uso,s.validade FROM software s 
			INNER JOIN item i on(i.id_item = s.id_item) 
			INNER JOIN pedido p on (p.id_pedido = i.id_pedido) 
			INNER JOIN cliente c on (c.id_cliente = p.id_cliente) 
			INNER JOIN cliente_email e on (c.id_cliente = e.id_cliente) 
			WHERE s.ativo = 1 AND s.licenca = :licenca GROUP BY i.id_item";
			$getregistro2 = $db->prepare($getregistro);
			$getregistro2->bindValue(':licenca', $this->licenca, PDO::PARAM_STR);
			$return = $getregistro2->execute();
			$rows   = $getregistro2->rowCount();

			if($rows > 0)
			{
				while($tbl = $getregistro2->fetch(PDO::FETCH_ASSOC))
				{
					$nome     = $tbl['nome'];
					$email    = $tbl['email'];
					$client   = $tbl['client'];
					$uso      = $tbl['uso'];
					$validade = $tbl['validade'];

					if($uso === '0'){
						
						$this->getRegistro($validade, $this->licenca, $this->imei, 1);
						$this->getUpdate($this->licenca, $nome, $email);
						$this->getUpdateLicenca($this->licenca, 1);
						return true;
					}else{
						return 1;
					}	
				}

				}else{
					return false;
				}

			}catch(PDOException $e){
				LogHelper::logErro($e->getMessage(), basename(__FILE__), $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'], $e->getLine());
				return false;
			}
		}
	

	public function getRegistro($validade, $licenca, $imei, $uso)	
	{
		$model = new Conn();
		$db = $model->getBase();
		try{
			$getupdate = "UPDATE account SET licenca = :licenca, uso_lic = :uso, vencimento = :data_vencimento WHERE imei = :imei";
			$getupdate2 = $db->prepare($getupdate);
			$getupdate2->bindValue(':licenca', $licenca, PDO::PARAM_STR);
			$getupdate2->bindValue(':imei',    $imei, PDO::PARAM_STR);
			$getupdate2->bindValue(':data_vencimento', $validade, PDO::PARAM_STR);
			$getupdate2->bindValue(':uso', 		$uso, PDO::PARAM_INT);
			$return = $getupdate2->execute();
			$rows   = $getupdate2->rowCount();

			if($return === true)
				return true; //array($getdashboardCount, $rows);
			else
				return false;
			

		}catch(PDOException $e){
			LogHelper::logErro($e->getMessage(), basename(__FILE__), $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'], $e->getLine());
			return false;
		}
	}

	public function getUpdate($licenca, $nome, $email)
	{
		$model = new Conn();
		$db = $model->getGoodShop();
		try{

			$getupdate = "INSERT INTO registro(licenca, nome, email) VALUES (:licenca, :nome, :email)";
			$getupdate2 = $db->prepare($getupdate);
			$getupdate2->bindValue(':licenca', $licenca, PDO::PARAM_STR);
			$getupdate2->bindValue(':nome',    $nome,    PDO::PARAM_STR);
			$getupdate2->bindValue(':email',   $email,   PDO::PARAM_STR);
			$return = $getupdate2->execute();
			$rows   = $getupdate2->rowCount();

			if($return === true)
				return true; //array($getupdate2, $rows);
			else
				return false;

		}catch(PDOException $e){
			LogHelper::logErro($e->getMessage(), basename(__FILE__), $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'], $e->getLine());
			return false;
		}
	}

	public function getUpdateLicenca($licenca, $uso)
	{
		$model = new Conn();
		$db = $model->getGoodShop();
		try{

			$getupdatelicenca  = "UPDATE software SET uso = :uso WHERE licenca = :licenca";
			$getupdatelicenca2 = $db->prepare($getupdatelicenca);
			$getupdatelicenca2->bindValue(':licenca', $licenca, PDO::PARAM_STR);
			$getupdatelicenca2->bindValue(':uso', $uso, PDO::PARAM_INT);
			$return = $getupdatelicenca2->execute();
			$rows   = $getupdatelicenca2->rowCount();

			if($return === true)
				return true; //array($getupdatelicenca2, $rows);
			else
				return false;

		}catch(PDOException $e){
			LogHelper::logErro($e->getMessage(), basename(__FILE__), $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'], $e->getLine());
			return false;
		}
	}

	public function exibirLicenca()
	{
		$model = new Conn();
		$db = $model->getBase();
		try{

			$exibirlicenca  = "SELECT licenca FROM account WHERE imei = :imei";
			$exibirlicenca2 = $db->prepare($exibirlicenca);
			$exibirlicenca2->bindValue(':imei', $this->imei, PDO::PARAM_STR);
			$return = $exibirlicenca2->execute();

			if($return === true)
				return array($exibirlicenca2);
			else
				return false;

		}catch(PDOException $e){
			LogHelper::logErro($e->getMessage(), basename(__FILE__), $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'], $e->getLine());
			return false;
		}
	}
}