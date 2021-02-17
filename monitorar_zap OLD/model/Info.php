<?php

class Info
{
	public function getTipoMensagem($tipo)
	{
		if($tipo === 1):
			$info = '<i class="fas fa-caret-square-left" style="color:#FC5E5E;"></i>';
		else:
			$info = '<i class="fas fa-caret-square-right" style="color:#4186C6;"></i>';
		endif;
	}

	public static function lastsevenday()
	{

		$data = date( 'Y-m-d' );
		$dia = date('d', strtotime($data));
		$mes = date('m', strtotime($data));
		$ano = date('Y', strtotime($data));

		$hora = date('H', strtotime($data));
		$min  = date('i', strtotime($data));
		$seg  = date('s', strtotime($data));

		$ontem      = date('Y-m-d',mktime($hora, $min, $seg, $mes, $dia+1, $ano));
		$ult_7_dias = date('Y-m-d',mktime($hora, $min, $seg, $mes, $dia-6, $ano));	

		return array($ontem,$ult_7_dias,$dia,$mes,$ano,$hora,$min,$seg);
	}

}