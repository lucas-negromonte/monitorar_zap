<?php

class Dashboard
{
	private $imei;

	public function __construct($imei = '')
	{
		$this->imei = $imei;
	}

	public function getDashboardDados()
	{
			$model = new Conn();
			$db = $model->getBase();

	try{
		$getdashboard = "SELECT COUNT(DISTINCT(nome_contato)) AS contato, COUNT(imei) AS mensagem FROM mensagens WHERE imei = :imei";
		$getdashboardCount = $db->prepare($getdashboard);
		$getdashboardCount->bindValue(':imei', $this->imei, PDO::PARAM_STR);
		$return = $getdashboardCount->execute();
		$rows   = $getdashboardCount->rowCount();

		if($return === true)
			return array($getdashboardCount, $rows);
		else
			return false;

	   }catch(PDOException $e){
		 LogHelper::logErro($e->getMessage(), basename(__FILE__), $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'], $e->getLine());
		 return false;
	   }
	}

	public function getTableDashboardVisualiza($imei, $ult_7_dias, $ontem)
	{
	    $count_conversions=0;
	    $total_conversion=0;
	    $total_valor_array=0; 
	    $clicks_total=0;

		$model = new Conn();
		$db = $model->getBase();

		try{

			$query = "SELECT DATE_FORMAT(m.data,'%Y-%m-%d') AS data, COUNT(m.imei) AS total_mensagem FROM mensagens AS m WHERE data BETWEEN :data1 AND :data2 AND m.imei = :imei GROUP BY DATE_FORMAT(m.data,'%Y-%m-%d')"; 
			$query2 = $db->prepare($query);
			$query2->bindValue(':imei', $imei, PDO::PARAM_STR);
			$query2->bindValue(':data1', $ontem, PDO::PARAM_STR);
			$query2->bindValue(':data2', $ult_7_dias, PDO::PARAM_STR);
			$query2->execute();
			$rows_query = $query2->rowCount();
			while($tbl = $query2->fetch(PDO::FETCH_OBJ))
			{

				$data_venda     	      = $tbl->data;
				$data_venda_array[]  	  = $tbl->data;
				$total_mensagem_array[]   = $tbl->total_mensagem;
				$total_mensagem           = $tbl->total_mensagem;

				$total_conversion   = array_sum($total_mensagem_array);
				
				$clicks_total = (!isset($total_mensagem_array))? '0': array_sum($total_mensagem_array);

				$array_produto_venda[] 			   = $total_mensagem;
				$array_data_venda[]    			   = $data_venda;
				$array_clicks_venda[$data_venda][] = $total_mensagem;

			}

			$count_conversions = (!isset($total_conversion))? '0': $total_conversion;

			return array($array_data_venda, $array_clicks_venda, $array_produto_venda);

		}catch(PDOException $e){
			LogHelper::logErro($e->getMessage(), basename(__FILE__), $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'], $e->getLine());
			return false;
		}
	}
}