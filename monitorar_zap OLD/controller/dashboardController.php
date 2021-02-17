<?php

include '../includes/config.php'; 

$imei = filter_input(INPUT_GET, 'txtimei');
$page = filter_input(INPUT_GET, 'page');

class DashboardController
{
	
	public function getDashboard($imei)
	{
		$dashboard = new Dashboard($imei);

		if($dashboard->getDashboardDados() !== false){

			$getdashboard = $dashboard->getDashboardDados();
			$query = $getdashboard[0];
			$rows  = $getdashboard[1];
			
			$dd = $query->fetchAll(PDO::FETCH_ASSOC);

			echo json_encode($dd);

		}else{
			$dados['error'] = (string) 2;
			echo json_encode($dados);
		}
	}

	public function graph($imei)
	{
		$dashboard = new Dashboard($imei);

		$lastsevenday = Info::lastSevenDay();
		$ontem      = $lastsevenday[0];
		$ult_7_dias = $lastsevenday[1]; 
		$dia 	    = $lastsevenday[2]; 
		$mes 		= $lastsevenday[3];
		$ano 		= $lastsevenday[4];
		$hora 		= $lastsevenday[5];
		$min 		= $lastsevenday[6];
		$seg 		= $lastsevenday[7];

		$gov = $dashboard->getTableDashboardVisualiza($imei, $ontem, $ult_7_dias);
		$array_data_venda     = $gov[0];
		$array_clicks_venda	  = $gov[1];
		$array_produto_venda  = $gov[2];

		$grafico_content = '';
		$grafico_clicks_content = '';
		$grafico_clicks_venda = '';

		for($i=6; $i >= 0; $i--)
		{
			$data = date('Y-m-d',mktime($hora, $min, $seg, $mes, $dia-$i, $ano));
			$data_venda = array($data);	
			$grafico_data[] = date('d/M', strtotime($data));	
		}

		for($i=6; $i >= 0; $i--)
		{
			$data = date('Y-m-d',mktime($hora, $min, $seg, $mes, $dia-$i, $ano));

			$payout_total_dia = !isset($array_clicks_venda[$data]) ? 0 : array_sum($array_clicks_venda[$data]);
			$grafico_venda[] = $payout_total_dia;
		}

	$return = array('retorno' => 'sucesso', 'dadoMsg' => $grafico_venda, 'dataGraph' => $grafico_data);
	echo json_encode($return);
	}
}
$obj2 = new DashboardController();

if($page === 'card'){
	$obj2->getDashboard($imei);
}else{
	$obj2->graph($imei);
}


