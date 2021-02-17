<?php
include '../includes/config.php'; 

	$email = trim(filter_input(INPUT_POST, 'txtusername', FILTER_VALIDATE_EMAIL));
    $senha = trim(filter_input(INPUT_POST, 'txtpassword'));

    $senha_new = base64_encode($senha);

class LoginController
{

	public function getViewUser($email, $senha2)
	{
		$login = new Login($email, $senha2);

		$getuser = $login->getUser();
		$true = $getuser[0];

		if($true === true){
			$imei = $getuser[1];
			$dados['success'] = (string) 1;
			$dados['imei'] = $imei;
			echo json_encode($dados);
		
		}else{
			$dados['error'] = (string) 2;
			echo json_encode($dados);
		}

	}

}

$obj = new LoginController();
$obj->getViewUser($email, $senha_new);
