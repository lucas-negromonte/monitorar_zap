<?php
include_once '../includes/config.php';

$licenca = filter_input(INPUT_POST, 'licenca'); //ctype_alnum()
$imei    = filter_input(INPUT_POST, 'imei');  //FILTER_VALIDATE_INT

class RegistroController
{
	public static function inserirLicenca($imei, $licenca)
	{
		$registro = new Registro($imei, $licenca);

		if($registro->verificaLicenca() === true){
			$dados['registra'] = (string) 1;
			$dados['msg'] = 'Licença registrada';
			echo json_encode($dados);
		}else if($registro->verificaLicenca() === 1){
			$dados['uso'] = (string) 1;
			$dados['msg'] = 'Licença já está em uso';
			echo json_encode($dados);
		}else if($registro->verificaLicenca() === false){
			$dados['existe'] = (string) 1;
			$dados['msg'] = 'Licença não existe';
			echo json_encode($dados);
		}
	}

	public static function renovarLicenca()
	{
			
	}

	public static function exibirLicenca($imei)
	{
		$registro = new Registro($imei);
		
		if($registro->exibirLicenca() !== false){

			$exibirlicenca = $registro->exibirLicenca();
			$query = $exibirlicenca[0];
			
			$dd = $query->fetch(PDO::FETCH_ASSOC);

			if($dd['licenca']  !== null ){
				echo json_encode($dd);
			}else{
				$dados['not'] = (string) 2;
				echo json_encode($dados);
			}

		}else{
			$dados['error'] = (string) 2;
			echo json_encode($dados);
		}	
	}
}

if(filter_input(INPUT_POST, 'page') === 'inserir'){
	RegistroController::inserirLicenca($imei, $licenca);
}if(filter_input(INPUT_POST, 'page') === 'exibir'){
	RegistroController::exibirLicenca($imei);
}else{
	RegistroController::renovarLicenca($imei, $licenca);
}