<?php

include_once '../includes/config.php';

$imei      = $_COOKIE['_hashimei'];
$senhanova = filter_input(INPUT_POST, 'txtsenhanova');
$emailnovo = filter_input(INPUT_POST, 'txtemail', FILTER_VALIDATE_EMAIL);

class PerfilController
{
	public static function getPerfil($imei)
	{
		$perfil = new Perfil($imei);
		
		if($perfil->getPerfil() !== false){

			$getperfil = $perfil->getPerfil();
			$query = $getperfil[0];

			//$rows = $whatsappmessage[1];
			
			$dd = $query->fetchAll(PDO::FETCH_ASSOC);

			echo json_encode($dd);
					
		}else{
			$dados['error'] = (string) 2;
			echo json_encode($dados);
		}
	}

	public static function getProfileData($imei, $emailnovo, $senhanova)
	{
		if(filter_input(INPUT_POST, 'txtemail', FILTER_VALIDATE_EMAIL) === false){
			$dados['email'] = (string) 2;
			echo json_encode($dados);
			return false;
		}

		$perfil = new Perfil($imei, $emailnovo, $senhanova);
		
		if($perfil->submitPerfildados() !== false){

			$dados['success'] = (string) 1;
			echo json_encode($dados);
					
		}else{
			$dados['error'] = (string) 2;
			echo json_encode($dados);
		}
	}
}

if(filter_input(INPUT_POST, 'page')  === 'dados'){
	PerfilController::getProfileData($imei, $emailnovo, $senhanova);
}else{
	PerfilController::getPerfil($imei);
}

