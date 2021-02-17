<?php

include_once '../includes/config.php';

$email    = trim(filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL));
$page     = filter_input(INPUT_POST, 'page');
$token    = base64_decode(filter_input(INPUT_POST, 'token'));
$password = filter_input(INPUT_POST, 'password');

class RecallController
{
	public function getEmail($email)
	{
		$login = new Login($email,'','');

		if($login->getEmailExist() === false){

			if($login->getUserEmail() === true)
			{
				$gettoken = $login->getCreateToken($email);
				$user_email = $gettoken[0];
				$token      = $gettoken[1];

				$this->getLink($user_email, $token);

			}else{
				$dados['Noexistemail'] = (string) 1;
				echo json_encode($dados);
			}
		}else{
			$dados['existemail'] = (string) 2;
			echo json_encode($dados);
		}	
	}

	public function getLink($user_email, $token)
	{

		$criptografiaEmail = base64_encode($user_email);
		$criptografiaToken = base64_encode($token);
		$link     = "http://zap.monitorar.info/?page=resetpassword&us=$criptografiaEmail&token=$criptografiaToken";	

		$this->getSubmitEmail($link, $user_email, $token);

	}

	public function getSubmitEmail($link, $user_email, $token)
	{
		set_time_limit(0);
		
		require_once APP_ROOT.'assets/lib/PHPMailer/PHPMailer/class.phpmailer.php';
		require_once APP_ROOT.'assets/lib/PHPMailer/PHPMailer/class.smtp.php';

		$mail              = new PHPMailer(true);
        //$mail->SMTPDebug   = 1;
        //$mail->Debugoutput = 'html';
        //$mail->setLanguage('pt');
        $mail->CharSet     = 'UTF-8';
		$mail->isSMTP();
		$mail->Host        = 'mail.onlinemania.com.br';
      //$mail->SMTPAuth = false; 
        $mail->Port        = 587; // ou 25
        $mail->SMTPAuth    = true;
        $mail->SMTPAutoTLS = false;
        $mail->Username    = 'nao-responder@onlinemania.com.br';
        $mail->Password    = '(35UBTQRGQdL';
        $mail->setFrom('nao-responder@onlinemania.com.br', 'MonitorZap');
        $mail->AddAddress($user_email);

        $mail->IsHTML(true);
        $mail->Subject = 'Recuperação de Senha';
        $mail->Body    = '
			<div style="font-size: 13px;">
				<div style="font-size: 13px; width: 50%; margin: 0 auto;">
					<h3 align="left" style=" border-radius:5px; padding:15px 25px 15px 25px; background-color: #075E54 !important; font-size: 20px;">
						<label style="color:#fff;">MonitorZap</label>&nbsp;
						<img width="30" src="http://zap.monitorar.info/assets/image/ic_monitor.png" />
					</h3>
					<div>
							<b>Olá, Tudo bem!</b>

						<p >Recebemos uma solicitação para redefinir sua senha do MonitorZap. Se você solicitou isso, clique no link a seguir para alterar sua senha.<br>
						<a style=" border: none; border-radius:5px; color: #f2711c; padding: 1px 1px; text-align: center;text-decoration: underline; display: inline-block; font-size: 16px; margin: 7px 0px; cursor: pointer;" href=' . $link . '>Criar nova senha</a><br>
						Caso não tenha solicitado, desconsidere este email, sua senha não será alterada.</p>
						<p >Obrigado.</p>
					</div>
				</div>
			</div>';
        $mail->AltBody = 'Este é o corpo em texto sem formatação para clientes de email não HTML';

        if (!$mail->send()) {

        	echo 'Erro: ' . $mail->ErrorInfo;
        } else {

        	$dados['success'] = (string) 3;
        	echo json_encode($dados);
        }
    }

    public function toRecoverPassword($email, $password, $token)
    {
    	$login = new Login($email, $password, $token);

    	if($login->toRecoverPassword() === true){
    		$dados['sucesso'] = (string) 1;
    		echo json_encode($dados);	
    		$login->toChangePasswordDelete();

    	}else{
    		$dados['error'] = (string) 2;
    		echo json_encode($dados);
    	}
    }

    public function toChangePassword($token)
    {
    	$login = new Login('','',$token);

    	if($token != '')
    	{
    		$tochangepassword = $login->toChangePassword();
			$query = $tochangepassword[0];

			$dataAtual = date('Y-m-d H:i:s');

	    	if($tochangepassword !== false)
	    	{
	    		$dd = $query->fetch(PDO::FETCH_ASSOC);

				$dd['token'];
				$email = $dd['email'];
				$datatime = $dd['datetime'];

	    		if($datatime < $dataAtual)
				{
	    			$dados['valido'] = (string) 3;
	    			$dados['email'] = $email;
	    			echo json_encode($dados);

			    }else{
				    $dados['expired'] = (string) 6;
					echo json_encode($dados);
				}

	    		}else{
	    			$dados['invalido'] = (string) 4;
	    			echo json_encode($dados);
	    		}
	    }else{
	    	$dados['Noexist'] = (string) 5;
	    	echo json_encode($dados);
	    }
    }
}

    $obj = new RecallController();

    if(filter_input(INPUT_POST, 'page') === 'torecover'){
    	$obj->toRecoverPassword($email, $password, $token);
    }else if(filter_input(INPUT_POST, 'page') === 'changepassword'){
    	$obj->toChangePassword($token);
    }else if(filter_input(INPUT_POST, 'page') === 'trocasenha'){
    	$obj->getEmail($email);
    }


