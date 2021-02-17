<?php

class Login
{
	public $email;
	public $senha;
	public $token;

	public function __construct($email='', $senha='', $token='')
	{
		$this->email = $email;
		$this->senha = $senha;
		$this->token = $token;
	}

	public function getUser()
	{
		$conn = new Conn();
		$db = $conn->getBase();

		try{
			$sqlGetUser = "SELECT email, senha, imei FROM account WHERE email = :email AND senha = :senha LIMIT 1";
			$sqlGetUser2 = $db->prepare($sqlGetUser);
			$sqlGetUser2->bindValue(':email', $this->email, PDO::PARAM_STR);
			$sqlGetUser2->bindValue(':senha', $this->senha, PDO::PARAM_STR);
			$sqlGetUser2->execute();
			$return = $sqlGetUser2->rowCount();

			if($return > 0){

				while($tbl = $sqlGetUser2->fetch(PDO::FETCH_ASSOC)){
					$imei = $tbl['imei'];
					//setcookie('_hashimei', $imei, (time() + (3 * 24 * 3600)));
				}

				return array(true, $imei);

			}else{
				return false;
			}

		}catch(PDOException $e){
			LogHelper::logErro($e->getMessage(), basename(__FILE__), $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'], $e->getLine());
			return false;
		}
	}

	public function getEmailExist()
	{
		$conn = new Conn();
		$db = $conn->getBase();

		try{
			$getEmailExist = "SELECT email FROM torecover WHERE email = :email LIMIT 1";
			$getEmailExist2 = $db->prepare($getEmailExist);
			$getEmailExist2->bindValue(':email', $this->email, PDO::PARAM_STR);
			$getEmailExist2->execute();
			$return = $getEmailExist2->rowCount();

			if($return > 0){
				return true;
			}else{
				return false;
			}

		}catch(PDOException $e){
			LogHelper::logErro($e->getMessage(), basename(__FILE__), $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'], $e->getLine());
			return false;
		}
	}

	public function getUserEmail()
	{
		$conn = new Conn();
		$db = $conn->getBase();

		try{
			$sqlGetEmail = "SELECT email FROM account WHERE email = :email LIMIT 1";
			$sqlGetEmail2 = $db->prepare($sqlGetEmail);
			$sqlGetEmail2->bindValue(':email', $this->email, PDO::PARAM_STR);
			$sqlGetEmail2->execute();
			$return = $sqlGetEmail2->rowCount();

			if($return > 0){

				while($tbl = $sqlGetEmail2->fetch(PDO::FETCH_ASSOC)){
					$email = $_SESSION['email'] = $tbl['email'];
				}

				return true;

			}else{
				return false;
			}

		}catch(PDOException $e){
			LogHelper::logErro($e->getMessage(), basename(__FILE__), $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'], $e->getLine());
			return false;
		}
	}

	public function getCreateToken($email)
	{	
		$conn = new Conn();
		$db = $conn->getBase();

		// gerar a chave
		$token = sha1(uniqid( mt_rand(), true));
		$data_expiracao = date('Y-m-d H:i:s', strtotime('+30 minutes'));

		try{
			$query_recupera = "INSERT INTO torecover (email, token, datetime) VALUES (:email, :token, :datetime)";
			$query_recupera2 = $db->prepare($query_recupera);
			$query_recupera2->bindValue(':email', $email, PDO::PARAM_STR);
			$query_recupera2->bindValue(':token', $token, PDO::PARAM_STR);
			$query_recupera2->bindValue(':datetime', $data_expiracao, PDO::PARAM_STR);
			$query_recupera2->execute();

			return array($email, $token);

		}catch(PDOException $e){
			LogHelper::logErro($e->getMessage(), basename(__FILE__), $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'], $e->getLine());
			return false;
		}
	}

	public function toRecoverPassword()
	{	
		$conn = new Conn();
		$db = $conn->getBase();

		try{
			$torecoverpassword = "UPDATE account SET senha = TO_BASE64(:senha) WHERE email = :email";
			$torecoverpassword2 = $db->prepare($torecoverpassword);
			$torecoverpassword2->bindValue(':email', $this->email, PDO::PARAM_STR);
			$torecoverpassword2->bindValue(':senha', $this->senha, PDO::PARAM_STR);
			$return = $torecoverpassword2->execute();

			if($return === true)
				return true;
			else
				return false;

		}catch(PDOException $e){
			LogHelper::logErro($e->getMessage(), basename(__FILE__), $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'], $e->getLine());
			return false;
		}
	}

	public function toChangePassword()
	{	
		$conn = new Conn();
		$db = $conn->getBase();

		try{
			$tochangepassword = "SELECT * FROM torecover WHERE token = :token";
			$torecoverpassword2 = $db->prepare($tochangepassword);
			$torecoverpassword2->bindValue(':token', $this->token, PDO::PARAM_STR);
			$torecoverpassword2->execute();
			$rows = $torecoverpassword2->rowCount();

			if($rows > 0)
				return array($torecoverpassword2);
			else
				return false;

		}catch(PDOException $e){
			LogHelper::logErro($e->getMessage(), basename(__FILE__), $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'], $e->getLine());
			return false;
		}
	}

	public function toChangePasswordDelete(){
		
		try{

			$conn = new Conn();
			$db = $conn->getBase();

			$tochangepassworddelete  = "DELETE FROM torecover WHERE token = :token";
			$tochangepassworddelete2 = $db->prepare($tochangepassworddelete);
			$tochangepassworddelete2->bindValue(':token', $this->token, PDO::PARAM_STR);
			$return = $tochangepassworddelete2->execute();
			$rows   = $tochangepassworddelete2->rowCount();

			/*if($return === true)
				return true;
			else
				return false;*/

		}catch(PDOException $e){
			LogHelper::logErro($e->getMessage(), basename(__FILE__), $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'], $e->getLine());
			return false;	
		}
	}

	public function getEmail(){
		return $this->email;
	}

	public function setEmail($email){
		$this->email = $email;
	}

	public function getSenha(){
		return $this->senha;
	}

	public function setSenha($senha){
		$this->senha = $senha;
	}

	public function getToken(){
		return $this->token;
	}
	public function setToken($token){
		$this->token = $token;
	}

}

$obj = new Login();