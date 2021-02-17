<?php

include '../includes/config.php'; 

	$imei = filter_input(INPUT_POST, 'txtimei');
	$page = filter_input(INPUT_POST, 'page');
	$nomecontato = filter_input(INPUT_POST, 'txtnomecontato');

class WhatsappController
{
	public function getMessage($imei)
	{
		$whatsapp = new Whatsapp($imei);
		
		if($whatsapp->getWhatsappMessage() !== false){

			$whatsappmessage = $whatsapp->getWhatsappMessage();
			$query = $whatsappmessage[0];
			$rows  = $whatsappmessage[1];
			
			$dd = $query->fetchAll(PDO::FETCH_ASSOC);

			echo json_encode($dd);
					
		}else{
			$dados['error'] = (string) 2;
			echo json_encode($dados);
		}
	}

	public function getMessageUser($imei, $nomecontato)
	{
		$whatsapp = new Whatsapp($imei, $nomecontato);
		
		if($whatsapp->getMessageUser() !== false){

			$messageuser = $whatsapp->getMessageUser();
			$query = $messageuser[0];
		  //$rows  = $messageuser[1];
			
			$dd = $query->fetchAll(PDO::FETCH_ASSOC);

			echo json_encode($dd);
					
		}else{
			$dados['error'] = (string) 2;
			echo json_encode($dados);
		}
	}
}

$obj2 = new WhatsappController();

if($page === '1'):
	$obj2->getMessage($imei);
elseif($page === '0'):
	$obj2->getMessageUser($imei, $nomecontato);
endif;
